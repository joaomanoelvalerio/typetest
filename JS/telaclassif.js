const ArrayFrases = [
  "🧠 Jogue Novamente e faça da próxima partida a melhor de todas!",
  "💡 Jogue, conecte-se e seja o mais rápido de todos!",
  "🚀 Não importa que você vá devagar, contanto que você não pare!",
  "🎯 O caminho mais certo de vencer é tentar mais uma vez!",
  "🔥 Não há atalhos para nenhum destino onde se vale a pena chegar!"
];

const frase = document.getElementById('fraseMotivacional');
const indiceAleatorio = Math.floor(Math.random() * ArrayFrases.length);
frase.textContent = ArrayFrases[indiceAleatorio];

document.getElementById("BotaoJogar").addEventListener("click", () => {
  window.location.href = "index.php";  
});

document.getElementById("BotaoSair").addEventListener("click", () => {
  window.location.href = "logout.php";  
});

document.getElementById("BotaoHistorico").addEventListener("click", () => {
  window.location.href = "telaHistorico.php";
});
