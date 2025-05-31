<?php
$erro_nome = "";
$erro_email = "";
$erro_email_format = "";
$erro_senha = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $erro_nome = verifica_Nulo("nome");
    $erro_email = verifica_Nulo("email");
    $erro_senha = verifica_Nulo("password");

    if (empty($erro_email)) {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $erro_email_format = "O e-mail informado não é válido.";
        }
    }

    if (empty($erro_nome) && empty($erro_email) && empty($erro_email_format) && empty($erro_senha)) {
        $nome = verifica_Campo($_POST["nome"]);
        $email = verifica_Campo($_POST["email"]);
        $senha = verifica_Campo($_POST["password"]);
        header("Location: Home.php");
        exit();
    }
}
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

    <!--
    <img src="images/logo.png"/>
    ^ No momento falta o logotipo...
    -->

    <div class="Login">
        <h1>Login</h1>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="formulario">

            <label for="name">Nome</label>
            <input type="text" name="nome" id="inputName" required
                value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
            <span id="erroNome" style="color: red;"><?php echo $erro_nome; ?></span><br />

            <label for="inputEmail">Email</label>
            <input type="email" name="email" id="inputEmail" required
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <span id="erroEmail" style="color: red;">
                <?php
                echo $erro_email;
                if (!empty($erro_email_format))
                    echo "<br>" . $erro_email_format;
                ?>
            </span><br />

            <label for="password">Senha</label>
            <input type="password" name="password" id="inputPassword" required>
            <span id="erroSenha" style="color: red;"><?php echo $erro_senha; ?></span><br />

            <input type="submit" value="Entrar">

        </form>
    </div>

    <a href="signup" id="signupLink">Criar nova conta</a>

</body>

</html>