<?php
    include_once("../../rotas.php");
    include_once($connRoute);

    $placa_veiculo = htmlspecialchars($_POST['placa']);
    $marca_veiculo = htmlspecialchars($_POST['marca']);
    $modelo_veiculo = htmlspecialchars($_POST['modelo']);


    // Padrão da placa
    $padrao = "/^[A-Za-z]{3}[0-9]{1}[a-zA-Z0-9]{1}[0-9]{2}/";

    // Verificação da placa
    if(preg_match($padrao, $placa_veiculo)){

        // Verificar se a placa já existe no banco
        $sql = "SELECT * FROM veiculos WHERE placa='$placa_veiculo'";
        $query = mysqli_query($conn, $sql);
        if (mysqli_num_rows($query) > 0){

            $_SESSION['msgCadCar'] = "Essa Placa já existe no banco de dados";
            header("Location: $cadVeiculoRoute");

        }else{ 

            // Inserindo a placa, modelo e marca
            $insertCar = "INSERT INTO veiculos (placa, marca, modelo) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertCar);
            mysqli_stmt_bind_param($stmt, "sss", $placa_veiculo, $marca_veiculo, $modelo_veiculo);

            // $insertCar = "INSERT INTO veiculos (placa) VALUE ('$placa_veiculo')";
            // $execute = mysqli_query($conn, $insertCar);
            
            if(mysqli_stmt_execute($stmt)){
                // echo 'foi';
                $_SESSION['msgCadCar'] = "Veículo cadastrado com sucesso";
                header("Location: $listarVeiculoRoute");
            }else{
                // echo 'não foi';
                $_SESSION['msgCadCar'] = "O veículo não foi cadastrado";
                header("Location: $cadVeiculoRoute");
            }
        }

    }else{
        $_SESSION['msgCadCar'] = "A placa não corresponde ao padrão correto";
        header("Location: $cadVeiculoRoute");
    }


?>