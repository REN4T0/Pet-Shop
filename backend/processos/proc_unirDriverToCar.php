<?php
include_once("../../rotas.php");
include_once($connRoute);



// echo "<select>";
// // Selecionando o nome dos motoristas no banco
// $selectDriver = "SELECT pk_Funcionario, nome FROM funcionarios WHERE profissao = 'Motorista'";
// $executeSelect = mysqli_query($conn, $selectDriver);
// $driver = mysqli_fetch_assoc($executeSelect);
// print_r($driver);
// $resposta = '';

// // Exibindo todos eles
// while($driver = mysqli_fetch_assoc($executeSelect)){
//     $resposta .= "<option value='" . $driver['nome'] . "'>" . $driver['nome'] . "</option> ";
// }

// echo $resposta;
// echo "</select>";
// Verificando a quantidade de motoristas no banco
// $countDriver = "SELECT COUNT(*) AS qtd_registro  FROM funcionarios WHERE profissao = 'Motorista'";
// $executeCount = mysqli_query($conn, $countDriver);
// $qntd = mysqli_fetch_assoc($executeCount);
// print_r($qntd);
// echo $qntd['qtd_registro'];
// echo "$qntd[]";

// for($contar = 1; $contar <= $driver['qtd_registro']; $contar++){
//     print_r($driver);
// }

?>
