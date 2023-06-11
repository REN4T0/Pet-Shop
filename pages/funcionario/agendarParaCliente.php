<?php
include_once("../../rotas.php"); // Inclui o arquivo de rotas
include_once($connRoute); // Inclui o arquivo de conexao
require_once $funcoesRoute;

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'Secretaria') {
  $_SESSION['msgRotaProibida'] = "Você Não possui permissão para entrar nessa página";
  header("Location: " . $homeRoute);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendamento para cliente</title>

  <link rel="icon" href="../img-dinamico/dog-icon.png">
  <link rel="stylesheet" href="../css-estatico/header.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../css-dinamico/table.css">
  <link rel="stylesheet" href="../css-dinamico/agendamento-funcionario-cliente.css">
  <link rel="stylesheet" href="../css-dinamico/paginacao.css">


</head>

<body onload="filtros('agendarParaCliente'),activateToast(<?php echo verificarSession(['agenCliFun']); ?>)">
  

  <div class="box-informacoes">
    <img src="../img-estatico/iconCachorro.svg" alt="ícone de imagens">
    <h1>Faça o agendamento da consulta!</h1>
  </div>

  <div class="linha-campos">


    <div class="box-maior-input">

      <div class="box-input">
        <input type="text" id="cpf" name="cpf" pattern="\d{3}[.]?\d{3}[.]?\d{3}[-]?\d{2}" placeholder="Digite o CPF do cliente" onchange="queryBanco2('animais')" required>
        <!-- <button onclick="queryBanco2('animais')">Verificar</button> -->
      </div>

      <div class="box-input">
        <select name="animais" id="animais" style="display: none;" required>
          <option value="" disabled selected hidden>Verifique o CPF primeiro</option>
        </select>
        
      </div>


    </div>


    <div class="box-maior-input ">

      <div class="box-input">
        <select name="servico" id="servico" onchange="paginacao('tabelaFunAgenCli')" required style="display: none;">
          <option value="" disabled selected hidden>Selecione o serviço</option>
          <option value="Veterinario">Veterinário</option>
          <option value="Banho">Banho</option>
          <option value="Tosa">Tosa</option>
          <option value="Banho e Tosa">Banho e Tosa</option>
        </select>
      </div>


      <div class="box-input box-pesquisa" id="divpesq" style="display: none;">
        <input type="text" placeholder="Pesquise por um Funcionário" id="pesq" onkeydown="paginacao('tabelaFunAgenCli')">
      </div>
    </div>

  
    <div id="checkTransport">
      <input type="checkbox" name="optDelivery" id="optDelivery" onchange="aparecerSelect()">
      <label for="check">Deseja transportar o pet?</label>
    </div>
    
    <div class="box-maior-input ">
      <div class="box-input"  id="divTransporte" style="display: none;">
        <label for="transporte">Como deseja o transporte?</label><br>
        <select name="transporte" id="transporte">
          <option value="Nenhum" selected>Nenhum</option>
          <option value="Buscar">Buscar</option>
          <option value="Trazer">Trazer</option>
          <option value="Buscar/Trazer">Buscar/Trazer</option>
        </select>
      </div>
    </div>

    <?php
    if (isset($_SESSION['agenCliFun'])) {
      unset($_SESSION['agenCliFun']);
    }
    ?>

  </div>

  <div class="container box-total">

    <table id="tabela">
    </table>

    <div id="links"></div>
  
    <?php
    if (isset($_GET['pagina'])) {
        echo "<p id='pag' hidden>".$_GET['pagina']."</p>";
      } else {
        echo "<p id='pag' hidden>1</p>";
      }
    ?>

  <a href="<?php echo $agendamentoFunRoute; ?>">Voltar</a>

  
  <script>
    // Função que vai aparecer o select para selecionar o tipo de transporte
    function aparecerSelect(){
      let checkTransporte = document.querySelector('#optDelivery');
      if(checkTransporte.checked){
          document.querySelector('#divTransporte').style = "display: block;"
      }else{
          document.querySelector('#divTransporte').style = "display: none;"
      }
    }
  </script>

  <script src="<?php echo $functionsRoute ?>"></script>
  <script src="../../backend/funcoes/toast.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../backend/funcoes/toast.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>