<?php
session_start();
require_once 'src/db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT c.nomeusuario, h.criado_em, h.tempo_jogo, h.pontuacaoPartida
        FROM historicotable h
        INNER JOIN cadastrotable c ON h.idusuario = c.idusuario
        ORDER BY h.criado_em DESC";

$result = $conn->query($sql);

$historico = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
} else {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tela Histórico</title>
    <link rel="stylesheet" href="CSS/telahistorico.css" />
</head>
<body>
    <div class="background"></div>
    <div class="retangulo">
        <h1>HISTÓRICO</h1>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Jogador</th>
                    <th>Data</th>
                    <th>Tempo</th>
                    <th>Pontuação</th>
                </tr>
            </thead>
            <tbody id="historico-body">
            </tbody>
        </table>
        <div class="paginacao">
            <button id="prevPage">←</button>
            <span id="paginaAtual">1</span>
            <button id="nextPage">→</button>
        </div>

        <div class="Voltar">
            <button id="BotaoVoltar" onclick="window.history.back()">Voltar</button>
        </div>
    </div>

    <script>
        const historicoData = <?php echo json_encode($historico); ?>;
    </script>
    <script src="JS/historico.js"></script>
</body>
</html>

<?php $conn->close(); ?>
