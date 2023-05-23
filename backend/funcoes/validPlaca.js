function validarPlaca(){
    let placa = document.querySelector('#placa').value;

    if(placa.length == 7){
        document.querySelector('#cadastrar').disabled = false
    }else{
        document.querySelector('#cadastrar').disabled = true
    }
}