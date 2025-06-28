let tempoRestante = 60;
let score = 0;
let paragrafoAtual = '';
let autorAtual = '';
let paragrafos = [];

const textoElem = document.getElementById('texto');
const autorElem = document.getElementById('autor');
const inputElem = document.getElementById('inputTexto');
const timerElem = document.getElementById('timer');
const scoreElem = document.getElementById('score');

fetch('JS/words.json')
  .then(res => res.json())
  .then(data => {
    paragrafos = data.paragrafos;
    sortearParagrafo();
    startTimer();
  });

function sortearParagrafo() {
  const aleatorio = Math.floor(Math.random() * paragrafos.length);
  paragrafoAtual = paragrafos[aleatorio].texto;
  autorAtual = paragrafos[aleatorio].autor;

  textoElem.innerHTML = '';
  for (let i = 0; i < paragrafoAtual.length; i++) {
    const span = document.createElement('span');
    span.innerText = paragrafoAtual[i];
    textoElem.appendChild(span);
  }

  autorElem.innerText = `— ${autorAtual}`;
  inputElem.value = '';
  inputElem.disabled = false;
  inputElem.focus();
  scoreElem.innerText = `Pontuação: ${score}`;
}

function startTimer() {
  const interval = setInterval(() => {
    tempoRestante--;
    timerElem.innerText = `Tempo: ${tempoRestante}s`;

    if (tempoRestante <= 0) {
      clearInterval(interval);
      inputElem.disabled = true;

      const tempoFormatado = new Date(60 * 1000).toISOString().substr(11, 8);

      fetch('salvar_pontuacao.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          pontuacao: score,
          tempo: tempoFormatado
        })
      })
        .then(res => res.json())
        .then(() => window.location.href = 'TelaScore.php')
        .catch(() => window.location.href = 'TelaScore.php');
    }
  }, 1000);
}

inputElem.addEventListener('input', () => {
  const input = inputElem.value;
  const spans = textoElem.querySelectorAll('span');

  spans.forEach(span => span.classList.remove('correta', 'errada'));

  for (let i = 0; i < paragrafoAtual.length; i++) {
    const letraUsuario = input[i];
    const letraOriginal = paragrafoAtual[i];

    if (letraUsuario === letraOriginal) {
      spans[i].classList.add('correta');
      if (!spans[i].classList.contains('pontuado')) {
        spans[i].classList.add('pontuado');
        score++;
        scoreElem.innerText = `Pontuação: ${score}`;
      }
    } else {
      if (letraUsuario !== undefined) {
        spans[i].classList.add('errada');
      }
    }
  }

  if (input.length >= paragrafoAtual.length) {
    setTimeout(sortearParagrafo, 300);
  }
});
