<?php
session_start();
require_once 'src/db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Jogar</title>
  <link rel="stylesheet" href="CSS/telajogo.css" />
</head>
<body>
<div class="background"></div>
  <div class="container">
  <h1>TYPETEXT</h1>
  <div class="Botoes">
    <button id="BotaoNovaLiga" onclick="window.location.href='login.php'">JOGAR</button>
  </div>
</div>
</body>
</html>
<?php
$conn->close();
?>