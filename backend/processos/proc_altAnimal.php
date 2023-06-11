<?php
include_once("../../rotas.php"); // Inclui o arquivo de rotas
include_once($connRoute); // Inclui o arquivo de conexão

try {
    $idAni = $_POST['idAnimal'];
    // Altera os dados do animal de acordo com o que o usuário alterar.
    $stmtAni = $conn->prepare("UPDATE Animais SET nome = ?, data_nascimento = ?, sexo = ?,
    especie = ?, raca = ?, peso = ?, cor = ? WHERE pk_Animal = ?");
    // Substituição da string preparada pelos valores corretos
    $stmtAni->bind_param("ssssssss", $_POST['nome'], $_POST['dataNasc'], $_POST['sexo'],
    $_POST['espec'], $_POST['raca'], $_POST['peso'], $_POST['cor'], $idAni);
    // Executa o sql
    $stmtAni->execute();
    $_SESSION['msgAltAnimaisCli'] = "Animal alterado com successo";
    header("Location: " . $animaisCliRoute);
} catch (Exception $e) {
    echo $e->getMessage();
}
