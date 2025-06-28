<?php
session_start();
require_once 'src/validate.php';
require_once 'src/db.php';

$existencia = true;
$erroEmail = '';
$erroSenha = '';
$email = '';
$senha = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = verifica_Campo($_POST['email']);
    $senha = $_POST['senha'];

    $erroEmail = verifica_Nulo($email, $existencia);
    $erroSenha = verifica_Nulo($senha, $existencia);

    if ($erroEmail === '' && $erroSenha === '') {
        $stmt = $conn->prepare("SELECT idusuario, nomeusuario, senha FROM cadastrotable WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($idusuario, $nomeusuario, $senhaHash);
            $stmt->fetch();

            if (password_verify($senha, $senhaHash)) {
                $_SESSION['idusuario'] = $idusuario;
                $_SESSION['user_id'] = $idusuario;
                $_SESSION['nomeusuario'] = $nomeusuario;

                echo "<script>window.location.href = 'TelaInicio.php';</script>";
                exit;
            } else {
                $erroSenha = "* Senha incorreta.";
                $existencia = false;
            }
        } else {
            $erroEmail = "* Email não encontrado.";
            $existencia = false;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="Utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/login.css">
</head>
<body>
    <div class="background"></div>
    <div class="retangulo">
        <div class="Login">
            <h1>Login</h1>
            <form method="post" id="dadosCadastro">
                <div id="texto">
                    <div class='camposCadastro'>
                        <label class="email" for="email">Email:</label>
                        <input type="email" name="email" id="inputEmail" required value="<?php echo htmlspecialchars($email); ?>">
                        <?php if ($erroEmail && $erroEmail !== '') echo "<p class='erro'>{$erroEmail}</p>"; ?>

                        <label class="password" for="password">Senha:</label>
                        <input type="password" name="senha" id="inputSenha" required>
                        <?php if ($erroSenha !== '') echo "<p class='erro'>{$erroSenha}</p>"; ?>
                    </div>
                    </div>
                    <div class="Botoes">
                    <input type="submit" id="Botao" value="Entrar">
                    <button type="button" id="Botao" onclick="window.location.href='cadastro.php'">Cadastrar</button>
                    </div>
                </div>
            </form>
        </div>
</body>
</html>
