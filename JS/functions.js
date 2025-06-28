function validarEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
  if (!regex.test(email.value)) {
    email.nextElementSibling.innerHTML = "<p class='erro'>* Endereço de e-mail inválido.</p>";
    return false;
  } else {
    email.nextElementSibling.innerHTML = "";
    return true;
  }
}

function validaGeral(campo) {
  if (!campo.checkValidity()) {
    campo.nextElementSibling.innerHTML = "<p class='erro'>* Este campo é obrigatório.</p>";
    return false;
  } else if (!campo.value.trim()) {
    campo.nextElementSibling.innerHTML = "<p class='erro'>* Valor inválido!</p>";
    return false;
  } else {
    campo.nextElementSibling.innerHTML = "";
    return true;
  }
}
