<?php
include_once("../../rotas.php");
include_once($connRoute);

$pkUser = htmlspecialchars($_POST['ide']);
$pkDriver= htmlspecialchars($_POST['motorista']);
$pkCar = htmlspecialchars($_POST['veiculo']);

echo "pkUser = $pkUser<br>pkDriver = $pkDriver<br>pkCar = $pkCar";

// Associando motorista ao veículo
$linkDriverToCar = "UPDATE veiculos SET fk_motorista = $pkDriver WHERE pk_veiculo = $pkCar";
$executeLink = mysqli_query($conn, $linkDriverToCar);

if(mysqli_affected_rows($conn)){
    // Associando motorista de busca ao agendamento
    $linkDriverToSched = "UPDATE agendamentos SET fk_motorista = $pkDriver, fk_veiculo = $pkCar WHERE pk_Agendamento = $pkUser";
    $executeLink = mysqli_query($conn, $linkDriverToSched);

    if(mysqli_affected_rows($conn)){
        $_SESSION['msgLinkDriverCar'] = 'Operação realizada com sucesso';
        header("location: $agendamentoFunRoute");
    }else{
        $_SESSION['msgLinkDriverCar'] = 'Operação falhou';
        header("location: $agendamentoFunRoute");
    }
}else{
    $_SESSION['msgLinkDriverCar'] = 'Operação falhou';
    header("location: $agendamentoFunRoute");
}


?>