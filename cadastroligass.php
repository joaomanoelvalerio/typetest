<?php
session_start();
require_once 'src/db.php';

$mensagem = '';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$idusuario = $_SESSION['idusuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nomeliga'], $_POST['palavrachave']) && !empty(trim($_POST['nomeliga'])) && !empty(trim($_POST['palavrachave']))) {
        $nomeliga = trim($_POST['nomeliga']);
        $palavrachave = trim($_POST['palavrachave']);

        $stmt = $conn->prepare("INSERT INTO ligatable (nomeliga, palavrachave) VALUES (?, ?)");
        $stmt->bind_param("ss", $nomeliga, $palavrachave);

        if ($stmt->execute()) {
            $idligaNova = $conn->insert_id;

            $update = $conn->prepare("UPDATE cadastrotable SET idliga = ? WHERE idusuario = ?");
            $update->bind_param("ii", $idligaNova, $idusuario);

            if ($update->execute()) {
                $mensagem = "Liga criada!";
            } else {
                $mensagem = "Erro ao associar a liga ao usuário: " . $conn->error;
            }

            $update->close();
        } else {
            $mensagem = "Erro ao cadastrar a liga: " . $conn->error;
        }

        $stmt->close();
    } else {
        $mensagem = "Todos os campos são obrigatórios.";
    }
}

$ligas = $conn->query("SELECT idliga, nomeliga FROM ligatable ORDER BY nomeliga ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Liga</title>
    <link rel="stylesheet" href="CSS/cadastroligas.css">
</head>
<body>
    <div class="background"></div>
    <div class="retangulo">
        <h1>Cadastrar Liga</h1>

        <?php if (!empty($mensagem)): ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
            <form method="POST" action="">
                <div id="texto">
                <div class="camposCadastro">
                <label for="nomeliga">Nome da nova liga:</label>
                <input type="text" name="nomeliga" id="nomeliga" required>

                <label for="palavrachave">Palavra-chave:</label>
                <input type="text" name="palavrachave" id="palavrachave" required>
                </div>
                </div>
                 <div class="Botoes">
                    <button type="submit" id="BotaoCadastrar">Cadastrar</button>
                    <button type="button" id="BotaoVoltar" onclick="window.location.href='ligas.php'">Voltar</button>
            </div>
            </form>
        </div>
</body>
</html>

<?php $conn->close(); ?>
