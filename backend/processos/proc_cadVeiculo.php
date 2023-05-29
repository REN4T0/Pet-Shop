<?php
    include_once("../../rotas.php");
    include_once($connRoute);

    $placa_veiculo = htmlspecialchars($_POST['placa']);
    echo $placa_veiculo;

    $insertCar = "INSERT INTO veiculos (placa) VALUE ('$placa_veiculo')";
    $execute = mysqli_query($conn, $insertCar);

    if(mysqli_insert_id($conn)){
        echo 'foi';
    }else{
        echo 'não foi';
    }

?>