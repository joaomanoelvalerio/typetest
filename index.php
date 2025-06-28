<?php
session_start();

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Jogo de Digitação</title>
  <link rel="stylesheet" href="CSS/index.css" />
</head>

<body>
  <div class="background"></div>


  <main class="container">
  <div class="retangulo">
    <h1>Digite!</h1>
    <div id="timer">Tempo: 60s</div>
    <div id="score">Pontuação: 0</div>
    <div id="texto"></div>
    <div id="autor"></div>
  </div>
  </main>
  <input type="text" id="inputTexto" autocomplete="off" autofocus />
   <div class="botaoVoltar">
      <button id="voltar" onclick="window.history.back()">Voltar</button>
    </div>
  <script src="JS/game.js"></script>
</body>

</html>
