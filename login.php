<?php
require_once 'PHP/validate.php';
$existencia = true;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="Utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypeTest</title>
    <link rel="stylesheet" type="text/css" href="CSS/stylesheet.css" media="screen" />
</head>

<body>

    <div class="Login">
        <h1>Login</h1>

        <form method="post" id="dadosLogin">

            <div class='camposLogin'>
                <label for="name">Nome</label>
                <input type="text" name="nome" id="inputName" required
                    value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nome = $_POST['nome'];
                    $erroNome = verifica_Nulo($nome, $existencia);
                    if ($erroNome !== '') {
                        echo "<p class='erro'>* {$erroNome}</p>";
                    }
                }
                ?>
            </div>

            <div class='camposLogin'>
                <label for="email">Email</label>
                <input type="email" name="email" id="inputEmail" required
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $email = verifica_Campo($_POST['email']); 
                    $erroEmail = verifica_Nulo($email, $existencia);

                    if ($erroEmail === true) { 
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            echo "<p class='erro'>* Email inválido.</p>";
                            $existencia = false;
                        }
                    } else { 
                        echo "<p class='erro'>* {$erroEmail}</p>";
                    }
                }
                ?>
            </div>


            <div class='camposLogin'>
                <label for="password">Senha</label>
                <input type="password" name="senha" id="inputSenha" value="" required>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $senha = $_POST['senha'];
                    $erroSenha = verifica_Nulo($senha, $existencia);
                    if ($erroSenha !== '') {
                        echo "<p class='erro'>* {$erroSenha}</p>";
                    }
                }
                ?>
            </div>

            <input type="submit" value="Entrar">

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $existencia) {
                echo "<script>window.location.href = 'home.php';</script>";
            }
            ?>
        </form>
    </div>

    <a href="signup" id="signupLink">Criar nova conta</a>

</body>

</html>