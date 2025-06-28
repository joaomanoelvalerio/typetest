<?php
session_start();
require_once 'src/db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$idliga = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($idliga === 0) {
    die("Liga inválida.");
}

$idusuario = (int)$_SESSION['idusuario'];


$sqlLiga = "SELECT nomeliga FROM ligatable WHERE idliga = ?";
$stmtLiga = $conn->prepare($sqlLiga);
$stmtLiga->bind_param("i", $idliga);
$stmtLiga->execute();
$resultLiga = $stmtLiga->get_result();
$liga = $resultLiga->fetch_assoc();
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
</head>
<body>
    <div class="background"></div>
    <div class="retangulo">
      <h1><?php echo htmlspecialchars($liga['nomeliga']); ?></h1>
      <p>Sua pontuação total na liga: <strong><?php echo $pontuacaoTotal; ?></strong></p>
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
              <tr><td colspan="2" class="mensagem-vazia">Nenhum jogador na liga ainda.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <a href="ligas.php" class="btn-voltar">Voltar</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
