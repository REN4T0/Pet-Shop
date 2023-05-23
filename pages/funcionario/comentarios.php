<?php
include_once("../../rotas.php");
include_once($connRoute);
require_once $funcoesRoute;

if (!isset($_SESSION['tipo'])) {
    $_SESSION['msgRotaProibidaCli'] = "Você Não possui permissão para entrar nessa página";
    header("Location: " . $homeRoute);
}
?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Comentários</title>
    <script src="<?php echo $functionsRoute; ?>"></script>
    
    <link rel="stylesheet" href="../css-dinamico/paginacao.css">
    <link rel="stylesheet" href="../css-estatico/header.css">
    <link rel="stylesheet" href="../css-dinamico/pagina-inicial-corporativo.css">
    <link rel="stylesheet" href="../css-dinamico/header-corporativo.css">
    <link rel="stylesheet" href="../css-dinamico/comentarios.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="../img-dinamico/dog-icon.png">



</head>


<body onload="filtros('comentarios'), paginacao('tabelaComentarios')">



    <?php
    if (isset($_GET['pagina'])) {
        echo "<p id='pag' hidden>" . $_GET['pagina'] . "</p>";
    } else {
        echo "<p id='pag' hidden>1</p>";
    }
    ?>

    <header class="header-corporativo">
        <div class="box-logo-barra-de-pesquisa-perfil">

            <a href="<?php echo $homeRoute; ?>"><img src="../img-dinamico/logo-corporativo.svg" alt="logo hamtaro petshop corporativo"></a>

            <div class="box-pesquisar">
                <input type="text" placeholder="Pesquise por palavras chaves nas mensagens" id="pesq">
                <button onclick="paginacao('tabelaComentarios')"><i class="bi bi-search"></i></button>

            </div>


            <div class='perfil-corpotativo' onmousedown='menuPerfil()'>
                <img src='../img-estatico/account_circle.svg'>
                <p>></p>
            </div>

        </div>

        <nav class="responsive menu-perfil" style="opacity: 0; z-index: -1;">

            <?php
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
            }


            if (isset($_SESSION['msgCadData'])) {
                unset($_SESSION['msgCadData']);
            }

            if (isset($_SESSION['msgCadFun'])) {
                echo $_SESSION['msgCadFun'];
                unset($_SESSION['msgCadFun']);
            }

            if (isset($_SESSION['msgRotaProibida'])) {
                echo $_SESSION['msgRotaProibida'];
                unset($_SESSION['msgRotaProibida']);
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


            

    <div class="box-input">
        <input type="date" id="data">
        <button onclick="paginacao('tabelaComentarios')"><i class="bi bi-search"></i></button>
    </div>
  


    <div id="tabela"></div>
    <div id="links"></div>
            

    


    <script src="../script.js"></script>
    <script src="../../backend/funcoes/toast.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>