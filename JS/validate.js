document.getElementById('formulario').addEventListener('submit', function(e) {
    e.preventDefault();

    var formulario = document.getElementById('dadosLogin')
    var nome = document.getElementById('inputName')
    var email = document.getElementById('inputEmail')
    var senha = document.getElementById('inputSenha')

    if (validaGeral(nome) && validaGeral(senha) && validaGeral(email) && validarEmail(email)) {
        formulario.submit();
    }
})