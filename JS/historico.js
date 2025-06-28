document.addEventListener("DOMContentLoaded", function () {
  const historicoBody = document.getElementById("historico-body");
  const paginaAtual = document.getElementById("paginaAtual");
  const prevBtn = document.getElementById("prevPage");
  const nextBtn = document.getElementById("nextPage");

  let pagina = 1;
  const porPagina = 10;

  function formatarData(dataStr) {
    const data = new Date(dataStr);
    return data.toLocaleDateString("pt-BR");
  }

  function formatarTempo(tempoStr) {
    const [h, m, s] = tempoStr.split(":");
    return `${m}:${s}`;
  }

  function carregarPagina() {
    historicoBody.innerHTML = "";
    const inicio = (pagina - 1) * porPagina;
    const fim = inicio + porPagina;
    const paginaDados = historicoData.slice(inicio, fim);

    paginaDados.forEach(item => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${item.nomeusuario}</td>
        <td>${formatarData(item.criado_em)}</td>
        <td>${formatarTempo(item.tempo_jogo)}</td>
        <td>${item.pontuacaoPartida} pts</td>
      `;
      historicoBody.appendChild(tr);
    });

    paginaAtual.textContent = pagina;
    prevBtn.disabled = pagina === 1;
    nextBtn.disabled = fim >= historicoData.length;
  }

  prevBtn.addEventListener("click", () => {
    if (pagina > 1) {
      pagina--;
      carregarPagina();
    }
  });

  nextBtn.addEventListener("click", () => {
    if (pagina * porPagina < historicoData.length) {
      pagina++;
      carregarPagina();
    }
  });

  carregarPagina();
});
