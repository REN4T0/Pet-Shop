<?php
include_once("../../rotas.php");
include_once($connRoute);
require_once $funcoesRoute;

if (!isset($_SESSION['tipo'])) {
  $_SESSION['msgRotaProibidaCli'] = "Você Não possui permissão para entrar nessa página";
  header("Location: " . $homeRoute);
} else {
  if ($_SESSION['tipo'] != 'admin' && $_SESSION['tipo'] != 'Secretaria') {
    $_SESSION['msgRotaProibida'] = "Você Não possui permissão para entrar nessa página";
    header("Location: " . $homeRoute);
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de veículos</title>



  <link rel="stylesheet" href="../css-dinamico/header-corporativo.css">
  <link rel="stylesheet" href="../css-dinamico/paginacao.css">
  <link rel="stylesheet" href="../css-dinamico/table.css">
  <link rel="stylesheet" href="../css-dinamico/pagina-inicial-corporativo.css">

  <link rel="icon" href="../img-dinamico/dog-icon.png">
  <script src="<?php echo $functionsRoute; ?>"></script>
</head>

<body
  onload="filtros('listarFuncionario'), paginacao('gerarTabelaVeiculos'), activateToast(<?php echo verificarSession(['msgCadCar']); ?>)">
  <?php
  if (isset($_GET['pagina'])) {
    echo "<p id='pag' hidden>" . $_GET['pagina'] . "</p>";
  } else {
    echo "<p id='pag' hidden>1</p>";
  }
  ?>


  <header class="header-corporativo">
    <div class="box-logo-barra-de-pesquisa-perfil">

      <a href="<?php echo $agendamentoFunRoute; ?>"><img src="../img-dinamico/logo-corporativo.svg"
          alt="logo hamtaro petshop corporativo"></a>

      <div class="box-pesquisar">
        <input type="text" placeholder="Pesquise por um veículo (placa)" id="pesq"
          onkeydown="if(event.keyCode==13){paginacao('gerarTabelaVeiculos');}">
        <button onclick="paginacao('gerarTabelaVeiculos')"><i class="bi bi-search"></i></button>
      </div>



      <div class='perfil-corpotativo' onmousedown='menuPerfil()'>
        <img src='../img-estatico/account_circle.svg'>
        <p>></p>
      </div>

    </div>

    <nav class="responsive menu-perfil" style="opacity: 0; z-index: -1;">

      <?php
      if (!loged()) {
        $_SESSION['msgloginFun'] = "Por favor, faça o login primeiro.";
        header("Location: " . $loginFunRoute);
      }

      if (!isset($_SESSION['tipo'])) {
        // $_SESSION['msgRotaProibidaCli'] = "Você Não possui permissão para entrar nessa página";
        header("Location: " . $homeRoute);
      }

      if ($_SESSION['tipo'] == 'Secretaria') {
        echo "<a href=" . $cadastradaDatasRoute . ">Cadastrar horário</a>";
        echo "<a href=" . $cadastroCliRoute . ">Cadastrar Cliente</a>";
        echo "<a href=" . $agendarParaClienteRoute . ">Agendar consulta</a>";
        echo "<a href=" . $cadAnimalParaClienteRoute . ">Cadastrar animal</a>";
        echo "<a href=" . $comentariosRoute . ">Comentarios dos Clientes</a>";
        echo "<a href=" . $cadVeiculoRoute . ">Cadastrar veículos</a>";
      } elseif ($_SESSION['tipo'] == 'admin') {
        echo "<a href=" . $cadastrarFunRoute . ">Cadastrar funcionário</a>";
        echo "<a href=" . $cadastradaDatasRoute . ">Cadastrar horário</a>";
        echo "<a href=" . $cadastroCliRoute . ">Cadastrar Cliente</a>";
        echo "<a href=" . $listarFunRoute . ">Listar Funcionários</a>";
        echo "<a href=" . $comentariosRoute . ">Comentarios dos Clientes</a>";
        echo "<a href=" . $cadVeiculoRoute . ">Cadastrar veículos</a>";
        echo "<a href=" . $listarVeiculoRoute . ">Listar veículos</a>";
      }


      if (isset($_SESSION['deleteFun'])) {
        unset($_SESSION['deleteFun']);
      }

      if (isset($_GET['pagina'])) {
        echo "<p id='pag' hidden>" . $_GET['pagina'] . "</p>";
      } else {
        echo "<p id='pag' hidden>1</p>";
      }
      ?>
      <button onclick="executeFunctions('logoff', '')">Sair</button>


    </nav>

  </header>


  <h1 class="titulo-agendamento">Lista de veículos</h1>

  <div class="container box-total">

    <div class="box-opcoes">
      <select name="situacoes" id="situacoes" onchange="paginacao('gerarTabelaVeiculos')" required>
        <option value="" disabled selected hidden>Selecione a situação</option>
        <option value="ativo">Ativo</option>
        <option value="inativo">Inativo</option>
      </select>
    </div>

    <table id="tabela"></table>
    <div id="links"></div>
  </div>
  <script src="../script.js"></script>
  <script src="../../backend/funcoes/toast.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>