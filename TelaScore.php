<?php
session_start();
require_once 'src/db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$idusuario = $_SESSION['idusuario'];

$sql = "SELECT pontuacaoPartida, criado_em FROM historicotable WHERE idusuario = ? ORDER BY criado_em DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idusuario);
$stmt->execute();
$stmt->bind_result($pontuacaoPartida, $dataPartida);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="CSS/telascore.css" />
    <title>Tela Score</title>
</head>
<body>
    <div class="background"></div>
    <div class="retangulo">
        <h1>GAME OVER</h1>

        <div class="Pontuacao">
            <div id="texto">
                <p>Sua pontuação:</p>
                <?php if (isset($pontuacaoPartida)): ?>
                    <p><?php echo htmlspecialchars($pontuacaoPartida); ?> pts</p>
                    <small><p>Registrada em:<br> <?php echo date('d/m/Y H:i', strtotime($dataPartida)); ?></p></small>
                <?php else: ?>
                    <p>Nenhuma pontuação registrada.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="botoes">
        <div class="Classificacao">
            <button id="BotaoClassificacao" onclick="window.location.href='TelaClassificacao.php'">Classificação</button>
        </div>

        <div class="JogarNovamente">
            <button id="BotaoJogarNovamente" onclick="window.location.href='index.php'">Jogar Novamente</button>
        </div>

        <div class="Sair">
            <button id="BotaoSair" onclick="window.location.href='TelaInicio.php'">Voltar</button>
        </div>
        </div>
    </div>
    <script src="JS/TelaScore.js"></script>
</body>
</html>

<?php
$conn->close();
?>
