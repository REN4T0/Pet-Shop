let hambur = document.querySelector(".menu");
let fecha = document.querySelector(".fechaMenu");
let menu = document.querySelector(".responsive");
var perfil = document.querySelector(".menu-perfil");
let req = document.querySelector(".requisitos")
let contato = document.querySelector(".contatoHeader")

// abre menu hamburguer
function abreMenu() {
  menu.style.left = "0%";
}

// fecha menu hamburguer
function fechaMenu() {
  menu.style.left = "-100%";
}

// mostra valores e formas de pagamento
function formaPagamento() {
  document
    .querySelector("#primeiro img")
    .setAttribute("src", "../img-estatico/debito.svg");
  document.querySelector("#primeiro p").innerHTML = "Débito";

  document
    .querySelector("#segundo img")
    .setAttribute("src", "../img-estatico/pix.svg");
  document.querySelector("#segundo p").innerHTML = "Pix";

  document
    .querySelector("#terceiro img")
    .setAttribute("src", "../img-estatico/credito.svg");
  document.querySelector("#terceiro p").innerHTML = "Crébito";

  document
    .querySelector("#quarto img")
    .setAttribute("src", "../img-estatico/boleto.svg");
  document.querySelector("#quarto p").innerHTML = "Boleto";
}

function formaValores() {
  document
    .querySelector("#primeiro img")
    .setAttribute("src", "../img-estatico/Dog.svg");
  document.querySelector("#primeiro p").innerHTML = "Banho <br> R$ 49,90";

  document
    .querySelector("#segundo img")
    .setAttribute("src", "../img-estatico/Shaving.svg");
  document.querySelector("#segundo p").innerHTML = "Tosa <br> R$79.99";

  document
    .querySelector("#terceiro img")
    .setAttribute("src", "../img-estatico/Veterinarian.svg");
  document.querySelector("#terceiro p").innerHTML = "Consulta <br> R$ 89,99";

  document
    .querySelector("#quarto img")
    .setAttribute("src", "../img-estatico/Fast delivery.svg");
  document.querySelector("#quarto p").innerHTML = "Delivery <br> R$ 25,80";
}

// abre ou fecha menu do perfil
var perfilAberto = 0;
function menuPerfil() {
  if (perfilAberto == 0) {
    perfilAberto = 1;
    perfil.style.opacity = "1";
    perfil.style.zIndex = "1";
    perfil.style.top = "80px";
  } else {
    perfilAberto = 0;
    perfil.style.opacity = "0";
    perfil.style.zIndex = "-1";
    perfil.style.top = "-500px";
  }
}

// sumir tela de carregamento
function delay(){
  setTimeout(fechaCarregando, 2000)
}

function fechaCarregando(){
  document.querySelector(".loading").style.display= "none"
}

// inicio lógica do olho para mostrar a senha
function mostrarSenha(botao, input){
  const passwordInput = document.getElementById(`${input}`);
  const toggleButton = document.getElementById(`${botao}`);
  
  if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleButton.className = "bi-eye-slash-fill";
  } else {
      passwordInput.type = "password";
      toggleButton.className = "bi-eye-fill";
  }

}

// fecha modal senha
function fechaReq(){
  req.style.display = "none"
}

// abre modal senha
function reqSenha(){
  req.style.display = "flex"
}