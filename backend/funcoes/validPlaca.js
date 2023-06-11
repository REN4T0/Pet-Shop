function validarPlaca() {
    let placa = document.querySelector('#placa').value;

    if (placa.length == 7) {
        document.querySelector('#cadastrar').disabled = false
    } else {
        document.querySelector('#cadastrar').disabled = true
    }
}

// Deixando o input da placa totalmente em maiusculo
function converterParaMaiusculas() {
    var placa = document.getElementById("placa");
    var valor = placa.value;

    // Converter para maiúsculas
    valor = valor.toUpperCase();

    // Defina o valor convertido de volta no campo de entrada
    placa.value = valor;
}