<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'src/db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$idliga = isset($_POST['idliga']) ? (int)$_POST['idliga'] : 0;
if ($idliga === 0) {
    die("Liga inválida.");
}

$idusuario = (int)$_SESSION['idusuario'];

$sqlIsMember = "SELECT 1 FROM usuario_liga WHERE idusuario = ? AND idliga = ?";
$stmtIsMember = $conn->prepare($sqlIsMember);
$stmtIsMember->bind_param("ii", $idusuario, $idliga);
$stmtIsMember->execute();
$stmtIsMember->store_result();
$isMember = $stmtIsMember->num_rows > 0;
$stmtIsMember->close();

if (!$isMember) {
    $sqlInsert = "INSERT INTO usuario_liga (idusuario, idliga) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii", $idusuario, $idliga);
    $stmtInsert->execute();
    $stmtInsert->close();
    $isMember = true;

    // Inserir partida automática ao entrar na liga
    $sqlInsertPartida = "INSERT INTO partidas (idusuario, idliga, pontuacao, descricao) VALUES (?, ?, 0, 'Partida automática ao entrar na liga')";
    $stmtPartida = $conn->prepare($sqlInsertPartida);
    $stmtPartida->bind_param("ii", $idusuario, $idliga);
    $stmtPartida->execute();
    $stmtPartida->close();
}

$sqlLiga = "SELECT nomeliga FROM ligatable WHERE idliga = ?";
$stmtLiga = $conn->prepare($sqlLiga);
$stmtLiga->bind_param("i", $idliga);
$stmtLiga->execute();
$resultLiga = $stmtLiga->get_result();
$liga = $resultLiga->fetch_assoc();
$stmtLiga->close();

if (!$liga) {
    die("Liga não encontrada.");
}

$sqlPontos = "SELECT COALESCE(SUM(pontuacao), 0) AS total FROM partidas WHERE idusuario = ? AND idliga = ?";
$stmtPontos = $conn->prepare($sqlPontos);
$stmtPontos->bind_param("ii", $idusuario, $idliga);
$stmtPontos->execute();
$resultPontos = $stmtPontos->get_result();
$pontos = $resultPontos->fetch_assoc();
$pontuacaoTotal = $pontos['total'] ?? 0;
$stmtPontos->close();

$sqlRanking = "
    SELECT u.nomeusuario, COALESCE(SUM(p.pontuacao), 0) AS total_pontos
    FROM usuario_liga ul
    JOIN cadastrotable u ON ul.idusuario = u.idusuario
    LEFT JOIN partidas p ON p.idusuario = u.idusuario AND p.idliga = ul.idliga
    WHERE ul.idliga = ?
    GROUP BY u.idusuario
    ORDER BY total_pontos DESC
";
$stmtRanking = $conn->prepare($sqlRanking);
$stmtRanking->bind_param("i", $idliga);
$stmtRanking->execute();
$resultRanking = $stmtRanking->get_result();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title><?php echo htmlspecialchars($liga['nomeliga']); ?> - Liga</title>
  <link rel="stylesheet" href="CSS/testeLigas.css" />
  <style>
    .background {
      /* Seu estilo para o background */
    }
    .retangulo {
      /* Seu estilo para o container */
    }
    .tabela-container {
      overflow-x: auto;
    }
    .tabela-clara {
      width: 100%;
      border-collapse: collapse;
    }
    .tabela-clara th, .tabela-clara td {
      border: 1px solid #ddd;
      padding: 8px;
    }
    .tabela-clara th {
      background-color: #f2f2f2;
      text-align: left;
    }
    .btn-voltar {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 15px;
      background-color: #333;
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }
    .btn-voltar:hover {
      background-color: #555;
    }
  </style>
</head>
<body>
  <div class="background"></div>
  <div class="retangulo">
    <h1><?php echo htmlspecialchars($liga['nomeliga']); ?></h1>

    <?php if (isset($_SESSION['msg'])): ?>
      <p style="text-align:center; background: rgba(0,0,0,0.6); color:white; padding: 10px;">
        <?php echo htmlspecialchars($_SESSION['msg']); unset($_SESSION['msg']); ?>
      </p>
    <?php endif; ?>

    <p>Sua pontuação total na liga: <strong><?php echo $pontuacaoTotal; ?></strong></p>

    <?php if ($isMember): ?>
      <p style="text-align:center; color: #080;">Você é membro desta liga.</p>
    <?php else: ?>
      <p style="text-align: center; color: #888;">Você não é membro desta liga.</p>
    <?php endif; ?>

    <h2>Ranking da Liga</h2>
    <div class="tabela-container">
      <table class="tabela-clara">
        <thead>
          <tr>
            <th>Jogador</th>
            <th>Pontuação</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($resultRanking->num_rows > 0): ?>
            <?php while ($row = $resultRanking->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['nomeusuario']); ?></td>
                <td><?php echo $row['total_pontos']; ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="2">Nenhum jogador na liga ainda.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <a href="ligas.php" class="btn-voltar">Voltar para Ligas</a>
  </div>
</body>
</html>

<?php
$conn->close();
?>
