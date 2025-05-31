function validarEmail(email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

var formulario = document.getElementById("formulario");
var inputName = document.getElementById("inputName");
var inputEmail = document.getElementById("inputEmail");
var inputPassword = document.getElementById("inputPassword");

var erroNome = document.getElementById("erroNome");
var erroEmail = document.getElementById("erroEmail");
var erroSenha = document.getElementById("erroSenha");

formulario.addEventListener("submit", function (e) {
    e.preventDefault();

    var valid = true;

    erroNome.textContent = "";
    erroEmail.textContent = "";
    erroSenha.textContent = "";

    if (!inputName.value.trim()) {
        erroNome.textContent = "O campo nome é obrigatório.";
        valid = false;
    }

    if (!validarEmail(inputEmail.value)) {
        erroEmail.textContent = "O campo e-mail é obrigatório.";
        valid = false;
    }


    if (!inputPassword.value.trim()) {
        erroSenha.textContent = "O campo senha é obrigatório.";
        valid = false;
    }

    if (valid) {
        formulario.submit();
    }
});