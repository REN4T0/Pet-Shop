<?php
include_once("../../rotas.php");
include_once($connRoute);
require_once $funcoesRoute;


if ($_SESSION['tipo'] != "admin") {
    // $_SESSION['msgRotaProibida'] = "Você Não possui permissão para entrar nessa página";
    header("Location: " . $agendamentoFunRoute);
}

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
    <title>Cadastrar Funcionário</title>

    <link rel="stylesheet" href="../css-dinamico/meu-perfil.css">
    <link rel="stylesheet" href="../css-dinamico/header-corporativo.css">
    <link rel="stylesheet" href="../css-dinamico/cadastrar-funcionario.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css-estatico/header.css">
    <link rel="icon" href="../img-dinamico/dog-icon.png">
    <link rel="stylesheet" href="../css-estatico/olhoSenha.css">

</head>

<body onload="activateToast(<?php echo verificarSession(['msgCadFun']); ?>)">

    <?php
    if (isset($_SESSION['msgCadFun'])) {
        unset($_SESSION['msgCadFun']);
    }
    ?>
    <div>

        <div class="informacoes-superior">
            <img src="../img-dinamico/icone-cadastro-funcionario.svg" alt="ícone do meu perfil">
            <h1>CADASTRE UM VEÍCULO NA PLATAFORMA!</h1>
        </div>

        <form action="<?php echo $procCadFunRoute; ?>" method="post">

            <div class="box-maior-input">


                <div class="box-input">
                    <label for="nome">PLACA: </label>
                    <input type="text" name="placa" id="placa" required autofocus placeholder="Digite o a placa do veículo" onkeyup="validarPlaca()">
                </div>

            </div>


            <div class="box-inferior-botoes">

                <p id="placaNaoIgual"></p>

                <input type="submit" id="cadastrar" value="Cadastrar" disabled></input>

                <a href="<?php echo $agendamentoFunRoute; ?>">VOLTAR</a>

            </div>

        </form>

    </div>
    <script src="<?php echo $validPlacaRoute; ?>"></script>
    <script src="../../backend/funcoes/toast.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../script.js"></script>

</body>

</html>
