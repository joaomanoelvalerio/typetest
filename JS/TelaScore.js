const BotaoPontuacao = document.getElementById("BotaoPontuacao");
const BotaoJogarNovamente = document.getElementById("BotaoJogarNovamente");
const BotaoSair = document.getElementById("BotaoSair");


BotaoPontuacao.addEventListener("click", function(event) {
 window.location.href = "TelaClassif.html";  
});

BotaoJogarNovamente.addEventListener("click", function(event) {
 window.location.href = "index.php";  
});

BotaoSair.addEventListener("click", function(event) {
 window.location.href = "TelaInicio.html"; 
});

function mostrarTela(tela) {
      document.getElementById("telaScore").style.display = (tela === 'score') ? 'block' : 'none';
      document.getElementById("telaInicio").style.display = (tela === 'Inicio') ? 'block' : 'none';
    }
