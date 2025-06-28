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
    <link rel="stylesheet" href="CSS/telainicial.css" />
    <title>Início</title>
</head>
<body>
    <div class="background"></div>

    <div class="retangulo">
        <h1>Início</h1>
       <div class="botoes">
    <button class="botao" onclick="location.href='index.php'">Jogar</button>
    <button class="botao" onclick="location.href='telaHistorico.php'">Histórico Geral</button>
    <button class="botao" onclick="location.href='TelaClassificacao.php'">Classificação</button>
    <button class="botao" onclick="location.href='pontuacaoSemanal.php'">Pontuação Semanal</button>
    <button class="botao" onclick="location.href='ligas.php'">Ligas</button>
    <button class="sair" onclick="location.href='logout.php'">Sair</button>
  </div>
    </div>

    <script src="JS/Telainicio.js"></script>
</body>
</html>
