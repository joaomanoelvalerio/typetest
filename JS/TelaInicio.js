const BotaoJogar = document.getElementById("BotaoJogar");
const BotaoClassificacao = document.getElementById("BotaoClassificacao");
const BotaoHistorico = document.getElementById("BotaoHistorico");

BotaoJogar.addEventListener("click", function(event) {
 window.location.href = "https://www.w3schools.com/css/css_colors.asp";    /*MUDAR ARQUIVO PARA PÁGINA DO JOGO*/
});

BotaoClassificacao.addEventListener("click", function(event) {
 window.location.href = "TelaClassif.html";
});

BotaoHistorico.addEventListener("click", function(event) {
 window.location.href = "telaHistorico.php";
});

function mostrarTela(tela) {
      document.getElementById("telaInicio").style.display = (tela === 'inicio') ? 'block' : 'none';
      document.getElementById("telaClassif").style.display = (tela === 'classif') ? 'block' : 'none';
    }
function mostrarTela(tela) {
      document.getElementById("telaInicio").style.display = (tela === 'inicio') ? 'block' : 'none';
      document.getElementById("telaHistorico").style.display = (tela === 'historico') ? 'block' : 'none';
    }
