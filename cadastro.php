<?php
require_once 'src/validate.php';
require_once 'src/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$sqlCreateTable = "
CREATE TABLE IF NOT EXISTS cadastrotable (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    nomeusuario VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    idliga INT DEFAULT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conn->query($sqlCreateTable) === FALSE) {
    die("Erro ao criar tabela: " . $conn->error);
}

$existencia = true;
$erroNome = $erroEmail = $erroSenha = $erroConfirmarSenha = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    $erroNome = verifica_Nulo($nome, $existencia);
    $erroEmail = verifica_Nulo(verifica_Campo($email), $existencia);
    $erroSenha = verifica_Nulo($senha, $existencia);
    $erroConfirmarSenha = verifica_Nulo($confirmarSenha, $existencia);

    if ($erroConfirmarSenha === '' && $senha !== $confirmarSenha) {
        $erroConfirmarSenha = "* As senhas não coincidem.";
        $existencia = false;
    }

    if ($erroNome !== '' || $erroEmail !== '' || $erroSenha !== '') {
        $existencia = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroEmail = "* Email inválido.";
        $existencia = false;
    }

    if ($existencia) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO cadastrotable (nomeusuario, email, senha, idliga) VALUES (?, ?, ?, NULL)");
        if ($stmt === false) {
            die("Erro na preparação da query: " . $conn->error);
        }
        $stmt->bind_param("sss", $nome, $email, $senhaHash);

        try {
            $stmt->execute();
            $stmt->close();
            echo "<script>
                    alert('Cadastro realizado com sucesso! Você será redirecionado para a tela de início.');
                    window.location.href = 'TelaInicio.php';
                  </script>";
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $erroEmail = "Este email já está cadastrado!";
            } else {
                die("Erro ao cadastrar: " . $e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="CSS/cadastro.css" />
</head>
<body>
    <div class="background"></div>
    <div class="retangulo">
        <h1>Cadastro</h1>
        <form method="POST" action="">
            <div id="texto">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required
                        value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>" />
                    <?php if (!empty($erroNome)) echo "<p class='erro'>$erroNome</p>"; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" />
                    <?php if (!empty($erroEmail)) echo "<p class='erro'>$erroEmail</p>"; ?>
                </div>

                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required />
                    <?php if (!empty($erroSenha)) echo "<p class='erro'>$erroSenha</p>"; ?>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha">Confirmar senha:</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required />
                    <?php if (!empty($erroConfirmarSenha)) echo "<p class='erro'>$erroConfirmarSenha</p>"; ?>
                </div>
            </div>
             <div class="acoes-form">
        <button type="submit" id="botaoCadastrar">Cadastrar</button>

        <div class="login-redirect">
            <p>Já possui cadastro?</p>
            <button type="button" id="botaoEntrar" onclick="window.location.href='login.php'">Entrar</button>
        </div>
        </div>
        </form>
</div>
</body>
</html>
