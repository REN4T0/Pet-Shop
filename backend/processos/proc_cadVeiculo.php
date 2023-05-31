<?php
    include_once("../../rotas.php");
    include_once($connRoute);

    $placa_veiculo = htmlspecialchars($_POST['placa']);
    echo $placa_veiculo;

    // if(mysqli_insert_id($conn)){
    //     echo 'foi';
    // }else{
    //     echo 'não foi';
    // }

    // Padrão da placa
    $padrao = "/^[A-Za-z]{3}[0-9]{1}[a-zA-Z0-9]{1}[0-9]{2}/";

    // Verificação da placa
    if(preg_match($padrao, $placa_veiculo)){

        // Verificar se a placa já existe no banco
        $sql = "SELECT * FROM veiculos WHERE placa='$placa_veiculo'";
        $query = mysqli_query($conn, $sql);
        if (mysqli_num_rows($query) > 0){

            $_SESSION['msgCadCar'] = "Placa existente";
            header("Location: $cadVeiculoRoute");

        }else{ 

            // Inserindo a placa
            $insertCar = "INSERT INTO veiculos (placa) VALUE (?)";
            $stmt = mysqli_prepare($conn, $insertCar);
            mysqli_stmt_bind_param($stmt, "s", $placa_veiculo);

            // $insertCar = "INSERT INTO veiculos (placa) VALUE ('$placa_veiculo')";
            // $execute = mysqli_query($conn, $insertCar);
            
            if(mysqli_stmt_execute($stmt)){
                // echo 'foi';
                $_SESSION['msgCadCar'] = "Veículo cadastrado com sucesso";
                header("Location: $cadVeiculoRoute");
            }else{
                // echo 'não foi';
                $_SESSION['msgCadCar'] = "O veículo não foi cadastrado";
                header("Location: $cadVeiculoRoute");
            }
        }

    }else{
        echo "Placa errada";
    }


?>