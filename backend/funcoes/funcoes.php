<?php
function loged() {
    // Verifica se o usuário está logado
    if (isset($_SESSION['tipo'])) {
        return isset($_SESSION['loggedinFun']) && $_SESSION['loggedinFun'];
    } else {
        return isset($_SESSION['loggedinCli']) && $_SESSION['loggedinCli'];
    }
}

function logoff() {
    // Desloga o usuário
    if (isset($_SESSION['tipo'])) {
        unset($_SESSION['loggedinFun']);
        unset($_SESSION['idFun']);
        unset($_SESSION['nome']);
        unset($_SESSION['tipo']);


    } else {
        unset($_SESSION['loggedinCli']);
        unset($_SESSION['idCli']);
        unset($_SESSION['nomeCliente']);
    }
}

function gerarTabelaAni() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $header = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/Pet-Shop/';

    // Receber o número da página
    $pagina_atual = filter_input(INPUT_GET, 'pag', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    // Setar a quantidade de items por pagina
    $qnt_result_pg = 5;

    // Calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    // String de preparação
    $stmt = $conn->prepare("SELECT nome, data_nascimento, raca, peso, pk_Animal
        FROM Animais WHERE fk_Cliente = ? AND ativo = 'ativo' ORDER BY nome LIMIT ?, ?");
    // Substituição da string preparada pelos valores corretos
    $stmt->bind_param("sss", $_SESSION['idCli'], $inicio, $qnt_result_pg);
    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    // String que será retornada na tabela
    $tabela = "<thead><tr>
            <th>Nome</th>
            <th>Data de Nascimento</th>
            <th>Raça</th>
            <th>Peso</th>
            <th>Alterar</th>
            <th>Excluir</th>
        </tr></thead>";

    if (mysqli_num_rows($resultado) == 0) {
        $tabela = $tabela . "
            <tr>
                <td colspan=6>Não há animais cadastrados</td>
            </tr>
            ";
    } else {
        // Pega cada linha da query e monta as linhas da tabela
        foreach ($resultado->fetch_all() as $row) {
            // Formata a data
            $data = date('d/m/Y', strtotime($row[1]));
            $tabela = $tabela .
                "<tr>
                    <td>$row[0]</td>
                    <td>$data</td>
                    <td>$row[2]</td>
                    <td>$row[3] Kg</td>
                    <td><a href='http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Pet-Shop/pages/cliente/altAnimal.php?id="
                . $row[4] . "'><i class='bi bi-pencil-square'></i></a></td>
                    <td><a href='http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Pet-Shop/backend/processos/proc_excAnimal.php?id="
                . $row[4] . "'><i class='bi bi-trash'></i></a></td>
                </tr>";
        }
    }

    // Paginação - Somar a quantidade de usuários

    $stmtCount = $conn->prepare("SELECT COUNT(PK_Animal) AS num_result FROM Animais WHERE fk_Cliente = ? AND ativo = 'ativo'");
    $stmtCount->bind_param("s", $_SESSION['idCli']);
    // Executa o sql
    $stmtCount->execute();
    // Pega o resultado do banco
    $resultado = $stmtCount->get_result();
    $row_pg = $resultado->fetch_all();


    // Quantidade de pagina
    $quantidade_pg = ceil($row_pg[0][0] / $qnt_result_pg);

    // Limitar os link antes depois
    $max_links = 2;
    $linkPaginas = "<a href='$header?pagina=1'><</a> ";

    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
        if ($pag_ant >= 1) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_ant'>$pag_ant</a> ";
        }
    }

    $linkPaginas =  $linkPaginas . "<p class='selecionado'>" . $pagina . "</p>";

    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
        if ($pag_dep <= $quantidade_pg) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_dep'>$pag_dep</a> ";
        }
    }

    $linkPaginas = $linkPaginas . "<a href='$header?pagina=$quantidade_pg'>></a>";

    $retornar = array('animais', $tabela, 'links', $linkPaginas);
    return json_encode($retornar);
}

function altAnimal() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    // String de preparação
    $stmt = $conn->prepare("SELECT nome, data_nascimento, sexo, especie, raca, peso, cor 
    FROM Animais WHERE pk_Animal = ?");
    // Substituição da string preparada pelos valores corretos
    $stmt->bind_param("s", $_GET['idAni']);
    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();
    $data = $resultado->fetch_all()[0];

    header('Content-Type: application/json');
    return json_encode($data);
}

function gerarTabelaAgenCli() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $header = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/Pet-Shop/pages/cliente/agendamentosCli.php';

    // Receber o número da página
    $pagina_atual = filter_input(INPUT_GET, 'pag', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    // Setar a quantidade de items por pagina
    $qnt_result_pg = 5;
    // Calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    // String de preparação
    $stmt = $conn->prepare("SELECT Funcionarios.nome, data_agendamento, horario_agendamento, Animais.nome, Agendamentos.tipo, `status`, pk_Agendamento, Agendamentos.transporte FROM Agendamentos
            INNER JOIN Animais
            ON Agendamentos.fk_Animal = Animais.pk_Animal
            INNER JOIN Clientes
            ON Animais.fk_Cliente = Clientes.pk_Cliente
            INNER JOIN Funcionarios
            ON Agendamentos.fk_Funcionario = Funcionarios.pk_Funcionario
            WHERE pk_Cliente = ?
            ORDER BY data_agendamento, horario_agendamento
            LIMIT ?, ?");
    // Substituição da string preparada pelos valores corretos
    $stmt->bind_param("sss", $_SESSION['idCli'], $inicio, $qnt_result_pg);
    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    // String que será retornada na tabela
    $tabela = "<thead><tr>
            <th>Profissional</th>
            <th>Data Agendamento</th>
            <th>Horário do agendamento</th>
            <th>Nome do animal</th>
            <th>Tipo</th>
            <th>Detalhes</th>
            <th>Status</th>
            <th>Transporte</th>
        </tr></thead>";

    if (mysqli_num_rows($resultado) == 0) {
        $tabela = $tabela . "
            <tr>
                <td colspan=8>Não há agendamentos cadastrados</td>
            </tr>
            ";
    } else {
        // Pega cada linha da query e monta as linhas da tabela
        foreach ($resultado->fetch_all() as $row) {
            // Formata a data
            $data = date('d/m/Y', strtotime($row[1]));
            $botao = "";

            if ($row[5] == "Marcado") {
                $botao = "<button class='cancelar' onclick='activeModal($row[6]," . '"Cancelar"' . ")'>Cancelar</button>";
            } elseif ($row[5] == "Concluido") {
                $botao = "<button class='finalizar cancelar' onclick='activeModal($row[6]," . '"Detalhes"' . ")'>Detalhes</button>";
            }

            $tabela = $tabela .
                "<tr>
                    <td>$row[0]</td>
                    <td>$data</td>
                    <td>$row[2]</td>
                    <td>$row[3]</td>
                    <td>$row[4]</td>
                    <td>$botao</td>
                    <td>$row[5]</td>";
            // Se o cliente ainda não tiver escolhido ou não escolheu transporte, e o staus do angendamento não for 'Marcado', a célula não vai aparecer como botão na tabela do funcionário
            if(($row[7] === 'Buscar' || $row[7] === 'Trazer' || $row[7] === 'Buscar/Trazer') && ($row[5] === 'Marcado' || $row[5] === 'Concluido')){
                $tabela .= "<td><button class='cancelar finalizar' style='width: 135px; heigth: 30px;' onclick='activeModalTransportFun($row[6]," . '"' . 'Cliente' . '"' . ")'>$row[7]</button></td>";
            }else{
                $tabela .= "<td>$row[7]</td>";
            }
            $tabela .= "</tr>";
        }
    }

    // Paginação - Somar a quantidade de usuários
    $stmtCount = $conn->prepare("SELECT COUNT(PK_Agendamento) AS num_result FROM Agendamentos
            INNER JOIN Animais
            ON Agendamentos.fk_Animal = Animais.pk_Animal
            INNER JOIN Clientes
            ON Animais.fk_Cliente = Clientes.pk_Cliente
            INNER JOIN Funcionarios
            ON Agendamentos.fk_Funcionario = Funcionarios.pk_Funcionario
            WHERE pk_Cliente = ?");
    $stmtCount->bind_param("s", $_SESSION['idCli']);
    // Executa o sql
    $stmtCount->execute();
    // Pega o resultado do banco
    $resultado = $stmtCount->get_result();
    $row_pg = mysqli_fetch_assoc($resultado);

    // Quantidade de pagina
    $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

    // Limitar os link antes depois
    $max_links = 2;
    $linkPaginas = "<a href='$header?pagina=1'><</a> ";

    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
        if ($pag_ant >= 1) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_ant'>$pag_ant</a> ";
        }
    }

    $linkPaginas =  $linkPaginas . "<p class='selecionado'>" . $pagina . "</p>";

    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
        if ($pag_dep <= $quantidade_pg) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_dep'>$pag_dep</a> ";
        }
    }

    $linkPaginas = $linkPaginas . "<a href='$header?pagina=$quantidade_pg'>></a>";

    $retornar = array("agendamentos", $tabela, 'links', $linkPaginas);
    return json_encode($retornar);
}

function checkAnimais() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $stmt = $conn->prepare("SELECT pk_Animal, nome FROM Animais WHERE fk_Cliente = ? AND ativo = 'ativo' ORDER BY nome");
    $stmt->bind_param("s", $_SESSION['idCli']);
    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    $opcoes = "<option value='' disabled selected hidden>Selecione um animal</option>";

    if (mysqli_num_rows($resultado) == 0) {
        $opcoes = $opcoes . "
            <option selected disabled>Cadastre um Animal</option>
            ";
    } else {
        foreach ($resultado->fetch_all() as $row) {
            $opcoes = $opcoes . "<option value='$row[0]'>$row[1]</option>";
        }
    }

    $retornar = array("animais", $opcoes);
    return json_encode($retornar);
}

function gerarTabelaFazAgenCli() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $header = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/Pet-Shop/pages/funcionario/agendamentosFun.php';

    // Receber o número da página
    $pagina_atual = filter_input(INPUT_GET, 'pag', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    // Setar a quantidade de items por pagina
    $qnt_result_pg = 5;

    // Calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    // String de preparação
    $stmt = $conn->prepare("SELECT Funcionarios.nome, data_agendamento,
        horario_agendamento, pk_Agendamento FROM Agendamentos
            INNER JOIN Funcionarios
            ON Agendamentos.fk_Funcionario = Funcionarios.pk_Funcionario
            WHERE `status` = 'Disponivel' AND data_agendamento LIKE ? AND tipo = ?
            ORDER BY data_agendamento, horario_agendamento
            LIMIT ?, ?");

    // Substituição da string preparada pelos valores corretos
    $data_atual = date("Y-m-d");
    $hora_atual = date("H:i");

    if ($_GET['dataAgen'] == ''){
        $dataAgen = date("Y-m-d");
    } else {
        $dataAgen = $_GET['dataAgen'];
    }

    $data = "%" . $_GET['dataAgen'] . "%";
    $stmt->bind_param("ssss", $data, $_GET['tipoAgen'], $inicio, $qnt_result_pg);
    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    // String que será retornada na tabela
    $tabela = "<thead><tr>
            <th>Profissional</th>
            <th>Data Agendamento</th>
            <th>Horário do agendamento</th>
            <th>Agendar</th>
        </tr></thead>";

    if (mysqli_num_rows($resultado) == 0) {
        $tabela = $tabela . "
            <tr>
                <td colspan=7>Não há agendamentos cadastrados</td>
            </tr>
            ";
    } else {
        // Pega cada linha da query e monta as linhas da tabela
        foreach ($resultado->fetch_all() as $row) {

            if (strtotime($data_atual) > strtotime($row[1]) || (strtotime($data_atual) == strtotime($row[1]) && strtotime($hora_atual) > strtotime($row[2]))){
                $stmt2 = $conn -> prepare("UPDATE Agendamentos set `status` = 'Cancelado'
                where pk_Agendamento = ?");

                $stmt2 -> bind_param("s", $row[3]);

                $stmt2-> execute();
            } else {
                // Formata a data
                $data = date('d/m/Y', strtotime($row[1]));
                $tabela = $tabela .
                    "<tr>
                        <td>$row[0]</td>
                        <td>$data</td>
                        <td>$row[2]</td>
                        <td><button class='cancelar agendar' type='button' onclick='executeFunctions(" . '"fazAgendamentoCli",' . $row[3] . ")'>Agendar</button></td>
                    </tr>";
            }
        }
    }

    $stmtPg = $conn->prepare("SELECT COUNT(PK_Agendamento) AS num_result FROM Agendamentos
        INNER JOIN Funcionarios
        ON Agendamentos.fk_Funcionario = Funcionarios.pk_Funcionario
        WHERE `status` = 'Disponivel' AND data_agendamento LIKE ? AND tipo = ?
        ORDER BY data_agendamento, horario_agendamento");

    $stmtPg->bind_param("ss", $data, $_GET['tipoAgen']);

    // Paginação - Somar a quantidade de usuários 
    $stmtPg->execute();
    $resultado = $stmtPg->get_result();
    $row_pg = $resultado->fetch_all();

    // Quantidade de pagina
    $quantidade_pg = ceil($row_pg[0][0] / $qnt_result_pg);

    // Limitar os link antes depois
    $max_links = 2;
    $linkPaginas = "<a href='$header?pagina=1".
    "&animais=". $_GET['animais'] ."&tipoAgen=".$_GET['tipoAgen']."&dataAgen=$dataAgen'><</a>";

    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
        if ($pag_ant >= 1) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_ant".
            "&animais=". $_GET['animais'] ."&tipoAgen=".$_GET['tipoAgen']."&dataAgen=$dataAgen'>$pag_ant</a> ";
        }
    }

    $linkPaginas =  $linkPaginas . "<p class='selecionado'>" . $pagina . "</p>";

    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
        if ($pag_dep <= $quantidade_pg) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_dep".
            "&animais=". $_GET['animais'] ."&tipoAgen=".$_GET['tipoAgen']."&dataAgen=$dataAgen'>$pag_dep</a> ";
        }
    }

    $linkPaginas = $linkPaginas .
    "<a href='$header?pagina=$quantidade_pg".
    "&animais=". $_GET['animais'] ."&tipoAgen=".$_GET['tipoAgen']."&dataAgen=$dataAgen'>></a>";

    $retornar = array('fazAgend', $tabela, 'links', $linkPaginas);
    return json_encode($retornar);
}

function fazAgendamentoCli() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    if ($_GET['idAni'] != 0) {
        try {
            // $transporte = htmlspecialchars($_GET['transporte']);
            $stmt = $conn->prepare("UPDATE Agendamentos SET fk_Animal = ?, `transporte` = ?, `status` = 'Marcado' WHERE pk_Agendamento = ?");
            $stmt->bind_param("sss", $_GET['idAni'], $_GET['transporte'], $_GET['idAgen']);
            // Executa o sql
            $stmt->execute();

            $_SESSION['msgAgendamentoCli'] = "Agendamento Realizado";
            return "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Pet-Shop/pages/cliente/agendamentosCli.php";
        } catch (Exception $e) {
            $_SESSION['msgFazAgendamento'] = "Error";
            return "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Pet-Shop/pages/cliente/agendamentosCli.php";
        }
    } else {
        $_SESSION['msgFazAgendamento'] = "Selecione um animal por favor";
        return "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Pet-Shop/pages/cliente/agendamentosCli.php";    }
}

function gerarTabelaAgenFun() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $header = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/Pet-Shop/pages/funcionario/agendamentosFun.php';

    // Receber o número da página
    $pagina_atual = filter_input(INPUT_GET, 'pag', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    // Setar a quantidade de items por pagina
    $qnt_result_pg = 5;

    // Calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    $status = $_GET['status'];

    if ($status == '') {
        $status = 'Disponivel';
    }

    if ($_SESSION['tipo'] == 'Veterinario' || $_SESSION['tipo'] == 'Esteticista') {

        // String de preparação
        $stmt = $conn->prepare("SELECT Funcionarios.nome, data_agendamento, horario_agendamento, Animais.nome, Clientes.nome, `status`, pk_Agendamento, transporte FROM Agendamentos
            LEFT JOIN Animais
            ON Agendamentos.fk_Animal = Animais.pk_Animal
            LEFT JOIN Clientes
            ON Animais.fk_Cliente = Clientes.pk_Cliente
            INNER JOIN Funcionarios
            ON Agendamentos.fk_Funcionario = Funcionarios.pk_Funcionario
            WHERE fk_Funcionario = ?
            AND Funcionarios.nome LIKE ?
            AND `status` = ?
            ORDER BY data_agendamento, horario_agendamento
            LIMIT ?, ?");

        $pesquisar = "%" . $_GET['pesq'] . "%";
        // Substituição da string preparada pelos valores corretos
        $stmt->bind_param("sssss", $_SESSION['idFun'], $pesquisar, $status, $inicio, $qnt_result_pg);

        $stmtPg = $conn->prepare("SELECT COUNT(PK_Agendamento) AS num_result FROM Agendamentos
        INNER JOIN Funcionarios ON Agendamentos.fk_Funcionario = Funcionarios.pk_Funcionario
        WHERE fk_Funcionario = ? AND Funcionarios.nome LIKE ? AND `status` = ?");
        $stmtPg->bind_param("sss", $_SESSION['idFun'], $pesquisar, $status);

    } else {
        $stmt = $conn->prepare("SELECT Funcionarios.nome, data_agendamento, horario_agendamento, Animais.nome, Clientes.nome, `status`, pk_Agendamento, transporte from Agendamentos            LEFT JOIN Animais
            ON Agendamentos.fk_Animal = Animais.pk_Animal
            LEFT JOIN Clientes
            ON Animais.fk_Cliente = Clientes.pk_Cliente
            INNER JOIN Funcionarios
            ON Agendamentos.fk_Funcionario = Funcionarios.pk_Funcionario
            WHERE Funcionarios.nome LIKE ?
            AND `status` = ?
            ORDER BY data_agendamento, horario_agendamento
            LIMIT ?, ?");

        $pesquisar = "%" . $_GET['pesq'] . "%";
        $stmt->bind_param("ssss", $pesquisar, $status, $inicio, $qnt_result_pg);

        $stmtPg = $conn->prepare("SELECT COUNT(PK_Agendamento) AS num_result FROM Agendamentos
        INNER JOIN Funcionarios ON Agendamentos.fk_Funcionario = Funcionarios.pk_Funcionario
        WHERE Funcionarios.nome LIKE ? AND `status` = ?");
        $stmtPg->bind_param("ss", $pesquisar, $status);
    }

    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    // String que será retornada na tabela
    $tabela =
        "<thead><tr>
            <th>Profissional</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Animal</th>
            <th>Dono</th>
            <th>Detalhes</th>
            <th>Status</th>
            <th>Transporte</th>
        </tr></thead>";

    if (mysqli_num_rows($resultado) == 0){
        $tabela = $tabela . "
            <tr>
                <td colspan=8>Não há agendamentos cadastrados</td>
            </tr>
            ";
    } else {
        foreach ($resultado->fetch_all() as $row) {
            // Formata a data
            $det = "<button class='cancelar finalizar' onclick='executeFunctions(" . '"update"' . ", $row[6])'>Finalizar</button>";
    
            if ($row[3] == '') {
                $det = '';
    
            } elseif ($row[5] == "Concluido" && $row[3] != '') {
    
                $det = "<button class='finalizar cancelar' onclick='activeModalDetalhesFun($row[6]," . '"' . $_SESSION['tipo'] . '"' . ")'>Detalhes</button>";
            }
    
            $data = date('d/m/Y', strtotime($row[1]));
            $tabela .=
                "<tr>
                    <td>$row[0]</td>
                    <td>$data</td>
                    <td>$row[2]</td>
                    <td>$row[3]</td>
                    <td>$row[4]</td>
                    <td>$det</td>
                    <td>$row[5]</td>";
            
            // Se o cliente ainda não tiver escolhido ou não escolheu transporte, e o staus do angendamento não for 'Marcado', a célula não vai aparecer como botão na tabela do funcionário
            if(($row[7] === 'Buscar' || $row[7] === 'Trazer' || $row[7] === 'Buscar/Trazer') && ($row[5] === 'Marcado' || $row[5] === 'Concluido')){
                $tabela .= "<td><button class='cancelar finalizar' style='width: 135px; heigth: 30px;' onclick='activeModalTransportFun($row[6]," . '"' . $_SESSION['tipo'] . '"' . ")'>$row[7]</button></td>";
            }else{
                $tabela .= "<td>$row[7]</td>";
            }
            $tabela .= "</tr>";
        }
    }

    // Pega cada linha da query e monta as linhas da tabela

    // Paginação - Somar a quantidade de usuários
    
    $stmtPg->execute();
    $resultado = $stmtPg->get_result();
    $row_pg = $resultado->fetch_all();

    // Quantidade de pagina
    $quantidade_pg = ceil($row_pg[0][0] / $qnt_result_pg);

    // Limitar os link antes depois
    $max_links = 2;
    $linkPaginas = "<a href='$header?pagina=1&status=$status&pesq=".$_GET['pesq']."'><</a>";

    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
        if ($pag_ant >= 1) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_ant
            &status=$status&pesq=".$_GET['pesq']."'>$pag_ant</a> ";
        }
    }

    $linkPaginas =  $linkPaginas . "<p class='selecionado'>" . $pagina . "</p>";

    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
        if ($pag_dep <= $quantidade_pg) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_dep
            &status=$status&pesq=".$_GET['pesq']."'>$pag_dep</a> ";
        }
    }

    $linkPaginas = $linkPaginas .
    "<a href='$header?pagina=$quantidade_pg&status=$status&pesq=".$_GET['pesq']."'>></a>";

    $retornar = array('tabela', $tabela, 'links', $linkPaginas);
    return json_encode($retornar);
}

function profissionais() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $prof = $_GET['servico'];

    if ($prof == 'Banho' || $prof == 'Tosa' || $prof == "Banho e Tosa"){
        $prof = "Esteticista";
    }

    $stmt = $conn->prepare("SELECT nome, pk_Funcionario FROM Funcionarios WHERE profissao = ? and ativo = 'ativo' ORDER BY nome");

    $stmt->bind_param("s", $prof);

    // Executa o sql
    $stmt->execute();

    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    if (mysqli_num_rows($resultado) == 0){
        $tabela = "<option value='' disabled selected hidden>Nenhum funcionário encontrado</option>";
    } else {
        $tabela = "<option value='' disabled selected hidden>Selecione um funcionário</option>";
    
        foreach ($resultado->fetch_all() as $row) {
            $tabela = $tabela . "<option value='$row[0]'>$row[0]</option>";
        }

    }


    $retornar = array('profissionais', $tabela);
    return json_encode($retornar);
}

function getDesc() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT descricao FROM Agendamentos WHERE pk_Agendamento = ?");
    $stmt->bind_param("s", $id);
    // Executa o sql
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_all()[0][0];

    if ($row == "") {
        $retornar = "Os detalhes não foram adicionados ainda";
    } else {
        $retornar = $row;
    }

    return $retornar;
}

// Função que vai retornar a lista de motoristas e veículos do banco
function getDrivers() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $id = $_GET['id'];
    
    // Verificando se o agendamento já tem um motorista
    $verifyExistDriver = "SELECT fk_motorista FROM Agendamentos WHERE pk_Agendamento = $id";
    $executeVerify = mysqli_query($conn, $verifyExistDriver);
    $arrayDriver = mysqli_fetch_assoc($executeVerify);
    $pkDriver = $arrayDriver['fk_motorista'];

    // Se o cliente já tiver um motorista, ele estará selecionando no select
    if($pkDriver > 0){
        // Selecionando o nome do motorista
        $chosenDriver = "SELECT nome FROM funcionarios WHERE pk_Funcionario = $pkDriver";
        $executeSelect = mysqli_query($conn, $chosenDriver);
        $driverDetails = mysqli_fetch_assoc($executeSelect);
        $driverName = $driverDetails['nome'];
    }else{
        $pkDriver = '';
        $driverName = 'Selecione';
    }

    // Verificando se o agendamento tem um veículo
    $verifyExistCar = "SELECT fk_veiculo FROM Agendamentos WHERE pk_Agendamento = $id";
    $executeVerify = mysqli_query($conn, $verifyExistCar);
    $arrayCar = mysqli_fetch_assoc($executeVerify);
    $pkCar = $arrayCar['fk_veiculo'];

    // Se o cliente já estiver com o veículo deefinido para o transporte, ele estará selecionando no select
    if($pkCar > 0){
        // Selecionando a placa do carro
        $chosenCar = "SELECT placa FROM veiculos WHERE pk_veiculo = $pkCar";
        $executeSelect = mysqli_query($conn, $chosenCar);
        $carDetails = mysqli_fetch_assoc($executeSelect);
        $licensePlate = $carDetails['placa'];
    }else{
        $pkCar = '';
        $licensePlate = 'Selecione';
    }


    // Selecionando motoristas
    $selectDriver = "SELECT pk_Funcionario, nome FROM funcionarios WHERE profissao = 'Motorista' AND ativo = 'ativo'";
    $executeSelect = mysqli_query($conn, $selectDriver);

    // Criando o elemento HTML para selecionar motorista
    $resposta = "<label for='nomeMotorista'>Motorista</label> <select name='motorista' required> <option value='$pkDriver' selected hidden>$driverName</option> ";

    // Armazenando options com os motoristas na variável resposta
    while($driver = mysqli_fetch_assoc($executeSelect)){
        $resposta .= "<option value='" . $driver['pk_Funcionario'] . "'>" . $driver['nome'] . "</option> ";
    }

    // Concluindo o select do motorista e criando o elemento HTML para selecionar o veículo
    $resposta .= "</select> <label for='veiculo'>Placa do veículo</label> <select name='veiculo' required> <option value='$pkCar' selected hidden>$licensePlate</option> ";

    // Selecionando veículos
    $selectCar = "SELECT pk_veiculo, placa FROM veiculos WHERE ativo = 'ativo'";
    $executeSelect = mysqli_query($conn, $selectCar);

    while($car = mysqli_fetch_assoc($executeSelect)){
        $resposta .= "<option value='" . $car['pk_veiculo'] . "'>" . $car['placa'] . "</option> ";
    }

    // Fechando select do veículo
    $resposta .= "</select>";

    // Retornando os options com os motoristas
    return $resposta;
}

// Função que vai mostrar quem irá transportar o pet
function viewDrivers() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $id = $_GET['id'];

    // Selecionando as pks do motorista e do veículo
    $getingDriverCar = "SELECT fk_motorista, fk_veiculo FROM agendamentos WHERE pk_Agendamento = $id";
    $executeSelect = mysqli_query($conn, $getingDriverCar);
    $driverCarArray = mysqli_fetch_assoc($executeSelect);
    $driverFk = $driverCarArray['fk_motorista'];
    $carFk = $driverCarArray['fk_veiculo'];

    // Iniciando mensagem
    $resposta = '<h5><strong>NOME DO MOTORISTA</strong></h5>';

    // condição que vai verificar se o agendamento tem o motorista definido
    if($driverFk > 0){
        $discoverDriver = "SELECT nome FROM funcionarios WHERE pk_funcionario = $driverFk";
        $executeDiscover = mysqli_query($conn, $discoverDriver);
        $driverNameArray = mysqli_fetch_assoc($executeDiscover);
        $driverName = $driverNameArray['nome'];

        $resposta .= "<p>$driverName</p>";
    }else{
        $resposta .= '<p>Não determinado por enquanto.<br>Mas não se preocupe! Logo, logo, você saberá quem vai transportar seu pet...</p>';
    }

    $resposta .= '<h5><strong>PLACA DO VEÍCULO</strong></h5>';

    // Condição que vai verificar se existe um veículo definido para transportr o pet
    if($carFk > 0){
        $discoverCar = "SELECT placa, marca, modelo FROM veiculos WHERE pk_veiculo = $carFk";
        $executeDiscover = mysqli_query($conn, $discoverCar);
        $carDetails = mysqli_fetch_assoc($executeDiscover);
        $licensePlate = $carDetails['placa'];
        $brand = $carDetails['marca'];
        $model = $carDetails['modelo'];

        $resposta .= "<p>Placa - $licensePlate<br>Marca - $brand<br>Modelo - $model</p>";
    }else{
        $resposta .= 'Não determinado por enquanto.<br>Mas não se preocupe! Logo, logo, você saberá qual veículo vai transportar seu pet...';
    }

    return $resposta;
}


function gerarTabelaDeleteFun() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $header = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/Pet-Shop/pages/funcionario/listarFuncionario.php';

    // Receber o número da página
    $pagina_atual = filter_input(INPUT_GET, 'pag', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    // Setar a quantidade de items por pagina
    $qnt_result_pg = 5;

    // Calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    // String de preparação
    $stmt = $conn->prepare("SELECT nome, cpf, profissao, pk_Funcionario, ativo FROM Funcionarios
        WHERE nome LIKE ?
        AND profissao != 'admin'
        AND ativo = ?
        ORDER BY nome ASC
        LIMIT ?, ?");

    $pesquisar = "%" . $_GET['pesq'] . "%";

    if ($_GET['situacoes'] == '') {
        $situacao = 'ativo';
    } else {
        $situacao = $_GET['situacoes'];
    }


    // Substituição da string preparada pelos valores corretos
    $stmt->bind_param("ssss", $pesquisar, $situacao, $inicio, $qnt_result_pg);

    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    // String que será retornada na tabela
    $tabela =
        "<thead><tr>
            <th>Nome</th>
            <th>Cpf</th>
            <th>Profissão</th>
            <th>Demitir</th>
        </tr></thead>";

    $cont = 1;
    if (mysqli_num_rows($resultado) == 0){
        $tabela = $tabela . "
            <tr>
                <td colspan=7>Não há funcionários cadastrados</td>
            </tr>
            ";
    } else {
        // Pega cada linha da query e monta as linhas da tabela
        foreach ($resultado->fetch_all() as $row) {
            $button = "<button class='cancelar' onclick='executeFunctions(`apagarFun`," . $row[3] . ")'>Demitir";

            if ($row[4] == 'demitido') {
                $button = '<p>demitido</p>';
            }
            $tabela = $tabela .
                "<tr>
                    <td id='nome$cont'>$row[0]</td>
                    <td>$row[1]</td>
                    <td>$row[2]</td>
                    <td>$button</td>
                </tr>";
            $cont += 1;
        }
    }
    
    // Paginação - Somar a quantidade de usuários
    $stmtCount = $conn->prepare("SELECT COUNT(PK_Funcionario) AS num_result FROM Funcionarios
        WHERE nome LIKE ?
        AND profissao != 'admin'
        AND ativo = ?");
    $stmtCount->bind_param("ss", $pesquisar, $situacao);
    // Executa o sql
    $stmtCount->execute();
    // Pega o resultado do banco
    $resultado = $stmtCount->get_result();
    $row_pg = mysqli_fetch_assoc($resultado);

    // Quantidade de pagina
    $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

    // Limitar os link antes depois
    $max_links = 2;
    $linkPaginas = "<a href='$header?pagina=1&situacoes=$situacao&pesq=".$_GET['pesq']."'><</a> ";

    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
        if ($pag_ant >= 1) {
            $linkPaginas =  
            $linkPaginas . "<a href='$header?pagina=$pag_ant&situacoes=$situacao&pesq=".$_GET['pesq']."'>$pag_ant</a> ";
        }
    }

    $linkPaginas =  $linkPaginas . "<p class='selecionado'>" . $pagina . "</p>";

    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
        if ($pag_dep <= $quantidade_pg) {
            $linkPaginas =  
            $linkPaginas . "<a href='$header?pagina=$pag_dep&situacoes=$situacao&pesq=".$_GET['pesq']."'>$pag_dep</a> ";
        }
    }

    $linkPaginas = 
    $linkPaginas . " <a href='$header?pagina=$quantidade_pg&situacoes=$situacao&pesq=".$_GET['pesq']."'>></a>";

    $retornar = array('tabela', $tabela, 'links', $linkPaginas);
    return json_encode($retornar);
}

function gerarTabelaVeiculos() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $header = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/Pet-Shop/pages/funcionario/listarVeiculos.php';

    // Receber o número da página
    $pagina_atual = filter_input(INPUT_GET, 'pag', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    // Setar a quantidade de items por pagina
    $qnt_result_pg = 5;

    // Calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    // String de preparação
    $stmt = $conn->prepare("SELECT pk_veiculo, placa, marca, modelo, ativo FROM veiculos
        WHERE placa LIKE ?
        AND ativo = ?
        ORDER BY pk_veiculo ASC
        LIMIT ?, ?");

    $pesquisar = "%" . $_GET['pesq'] . "%";

    if ($_GET['situacoes'] == '') {
        $situacao = 'ativo';
    } else {
        $situacao = $_GET['situacoes'];
    }


    // Substituição da string preparada pelos valores corretos
    $stmt->bind_param("ssss", $pesquisar, $situacao, $inicio, $qnt_result_pg);

    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    // String que será retornada na tabela
    $tabela =
        "<thead><tr>
            <th>Placa</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Desativar</th>
        </tr></thead>";

    $cont = 1;
    if (mysqli_num_rows($resultado) == 0){
        $tabela = $tabela . "
            <tr>
                <td colspan=7>Não há carros cadastrados</td>
            </tr>
            ";
    } else {
        // Pega cada linha da query e monta as linhas da tabela
        foreach ($resultado->fetch_all() as $row) {
            $button = "<button class='cancelar' onclick='executeFunctions(`desativarCarro`," . $row[0] . ")'>Desativar";

            if ($row[4] == 'inativo') {
                $button = "<button class='cancelar finalizar' onclick='executeFunctions(`reativarCarro`," . $row[0] . ")'>Reativar";
            }
            $tabela = $tabela .
                "<tr>
                    <td id='nome$cont'>$row[1]</td>
                    <td>$row[2]</td>
                    <td>$row[3]</td>
                    <td>$button</td>
                </tr>";
            $cont += 1;
        }
    }
    
    // Paginação - Somar a quantidade de usuários
    $stmtCount = $conn->prepare("SELECT COUNT(pk_veiculo) AS num_result FROM veiculos
        WHERE placa LIKE ?
        AND ativo = ?");
    $stmtCount->bind_param("ss", $pesquisar, $situacao);
    // Executa o sql
    $stmtCount->execute();
    // Pega o resultado do banco
    $resultado = $stmtCount->get_result();
    $row_pg = mysqli_fetch_assoc($resultado);

    // Quantidade de pagina
    $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

    // Limitar os link antes depois
    $max_links = 2;
    $linkPaginas = "<a href='$header?pagina=1&situacoes=$situacao&pesq=".$_GET['pesq']."'><</a> ";

    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
        if ($pag_ant >= 1) {
            $linkPaginas =  
            $linkPaginas . "<a href='$header?pagina=$pag_ant&situacoes=$situacao&pesq=".$_GET['pesq']."'>$pag_ant</a> ";
        }
    }

    $linkPaginas =  $linkPaginas . "<p class='selecionado'>" . $pagina . "</p>";

    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
        if ($pag_dep <= $quantidade_pg) {
            $linkPaginas =  
            $linkPaginas . "<a href='$header?pagina=$pag_dep&situacoes=$situacao&pesq=".$_GET['pesq']."'>$pag_dep</a> ";
        }
    }

    $linkPaginas = 
    $linkPaginas . " <a href='$header?pagina=$quantidade_pg&situacoes=$situacao&pesq=".$_GET['pesq']."'>></a>";

    $retornar = array('tabela', $tabela, 'links', $linkPaginas);
    return json_encode($retornar);
}

function desativarCarro(){
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE veiculos
        SET ativo = 'inativo'
        WHERE pk_veiculo = ?");
    $stmt->bind_param("s", $id);

    $stmt->execute();

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['msgCadCar'] = "Carro desativado com sucesso";
    } else {
        $_SESSION['msgCadCar'] = "Erro ao desativar carro";
    }

}

function reativarCarro(){
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE veiculos
        SET ativo = 'ativo'
        WHERE pk_veiculo = ?");
    $stmt->bind_param("s", $id);

    $stmt->execute();

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['msgCadCar'] = "Carro reativado com sucesso";
    } else {
        $_SESSION['msgCadCar'] = "Erro ao reativar carro";
    }
}

function update($table, $set, $where, $param) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $stmt = $conn->prepare("UPDATE $table
        SET $set
        WHERE $where");
    $stmt->bind_param($param[0], $param[1]);

    $stmt->execute();
}

function apagarFuncionario() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE Funcionarios
        SET ativo = 'demitido'
        WHERE pk_Funcionario = ?");
    $stmt->bind_param("s", $id);

    $stmt->execute();

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['deleteFun'] = "Funcionário demitido com sucesso";
    } else {
        $_SESSION['deleteFun'] = "Erro ao demitir funcionário";
    }


}

function finalizarConsul() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $id = $_GET['id'];
    $a = "Concluido";

    $stmt = $conn->prepare("UPDATE Agendamentos
        SET `status` = ?
        WHERE pk_Agendamento = ?");

    $stmt->bind_param("ss", $a, $id);

    $stmt->execute();

}

function altMeuPerfilCli() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    // String de preparação
    $stmt = $conn->prepare("SELECT cpf, nome, sobrenome, celular, cep, logradouro,
        numero, complemento, bairro, municipio, uf, email FROM Clientes WHERE pk_Cliente = ?");
    // Substituição da string preparada pelos valores corretos
    $stmt->bind_param("s", $_GET['idCli']);
    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();
    $data = $resultado->fetch_all()[0];

    header('Content-Type: application/json');
    return json_encode($data);
}


function animais() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $prof = $_GET['cpf'];

    $stmt = $conn->prepare("SELECT pk_Cliente from Clientes
        where cpf = ?");

    $stmt->bind_param("s", $prof);

    // Executa o sql
    $stmt->execute();

    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    if (mysqli_num_rows($resultado) == 0){
        $tabela = "CPF não está no sistema";
        $_SESSION['agenCliFun'] = "CPF não está no sistema";
    } else {

        $idCliente = $resultado -> fetch_all()[0][0];

        $stmt2 = $conn -> prepare("SELECT nome, pk_Animal from Animais where fk_Cliente = ?");

        $stmt2 -> bind_param("s", $idCliente);

        $stmt2 -> execute();

        $resultado2 = $stmt2->get_result();

        if (mysqli_num_rows($resultado2) == 0){
            $tabela = "Nenhum animal encontrado para esse CPF";
        } else {
            $tabela = "<option value='' disabled selected hidden>Selecione um animal</option>";

            foreach ($resultado2->fetch_all() as $row) {
                $tabela = $tabela . "<option value='$row[1]'>$row[0]</option>";
            }

        }


    }
    
    $retornar = array('animais', $tabela);
    return json_encode($retornar);
}


function tabelaFunAgenCli(){
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $header = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/Pet-Shop/pages/funcionario/agendarParaCliente.php';

    // Receber o número da página
    $pagina_atual = filter_input(INPUT_GET, 'pag', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    // Setar a quantidade de items por pagina
    $qnt_result_pg = 5;

    // Calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    $servico = $_GET['servico'];

    $stmt = $conn->prepare("SELECT Funcionarios.nome, data_agendamento, horario_agendamento, pk_Agendamento, COUNT(PK_Agendamento) AS num_result from Agendamentos
        LEFT JOIN Animais
        ON Agendamentos.fk_Animal = Animais.pk_Animal
        LEFT JOIN Clientes
        ON Animais.fk_Cliente = Clientes.pk_Cliente
        INNER JOIN Funcionarios
        ON Agendamentos.fk_Funcionario = Funcionarios.pk_Funcionario
        WHERE Funcionarios.nome LIKE ?
        AND `status` = 'Disponivel'
        AND tipo = ?
        GROUP BY Funcionarios.nome, data_agendamento, horario_agendamento, pk_Agendamento
        ORDER BY data_agendamento, horario_agendamento
        LIMIT ?, ?");

    $pesquisar = "%" . $_GET['pesq'] . "%";
    $stmt->bind_param("ssss", $pesquisar, $servico, $inicio, $qnt_result_pg);

    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    // String que será retornada na tabela
    $tabela =
        "<tr>
            <th>Profissional</th>
            <th>Data Agendamento</th>
            <th>Horário do agendamento</th>
            <th>Marcar</th>
        </tr>";

    $resultadoList = $resultado->fetch_all();
    
    if (!$resultadoList) {
        $row_pg = 0;
    }
    
    // Pega cada linha da query e monta as linhas da tabela
    foreach ($resultadoList as $row) {
        // Formata a data
        $det = "<button onclick='executeFunctions(" . '"fazerAgenParaCli"' . ", $row[3])' class = 'agendar cancelar'>Agendar</button>";

        $data = date('d/m/Y', strtotime($row[1]));
        $tabela = $tabela .
            "<tr>
                <td>$row[0]</td>
                <td>$data</td>
                <td>$row[2]</td>
                <td>$det</td>
            </tr>";
        $row_pg = $row[4];
    }

    if ($row_pg == 0){
        $tabela = "<tr>
            <th>Profissional</th>
            <th>Data Agendamento</th>
            <th>Horário do agendamento</th>
            <th>Marcar</th>
        </tr>" . "
            <tr>
                <td colspan=7>Não há agendamentos disponíveis</td>
            </tr>
            ";
    }
    

    // Quantidade de pagina
    $quantidade_pg = ceil($row_pg / $qnt_result_pg);

    // Limitar os link antes depois
    $max_links = 2;
    $linkPaginas = "<a href='$header?pagina=1&cpf=".
    $_GET['cpf'] ."&animais=". $_GET['animais'] ."&servico=". $servico ."&pesq=". $_GET['pesq']."'><</a> ";

    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
        if ($pag_ant >= 1) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_ant&cpf=".
            $_GET['cpf'] ."&animais=". $_GET['animais'] ."&servico=". $servico ."&pesq=". $_GET['pesq']."'>$pag_ant</a> ";
        }
    }

    $linkPaginas =  $linkPaginas . "<p class='selecionado'>" . $pagina . "</p>";

    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
        if ($pag_dep <= $quantidade_pg) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_dep&cpf=".
            $_GET['cpf'] ."&animais=". $_GET['animais'] ."&servico=". $servico ."&pesq=". $_GET['pesq']."'>$pag_dep</a> ";
        }
    }

    $linkPaginas = $linkPaginas . "<a href='$header?pagina=$quantidade_pg&cpf=".
    $_GET['cpf'] ."&animais=". $_GET['animais'] ."&servico=". $servico ."&pesq=". $_GET['pesq']."'>></a>";

    $retornar = array('tabela', $tabela, 'links', $linkPaginas);
    return json_encode($retornar);
}

function fazerAgenParaCli(){
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');

    $fkAnimal = $_GET['idAnimal'];
    $pkAgen = $_GET['idAgen'];
    $transporte = $_GET['transporte'];

    if($fkAnimal == ''){
        $_SESSION['agenCliFun'] = "Selecione um animal por favor";
    } else {
        $stmt = $conn->prepare("UPDATE Agendamentos set fk_Animal = ?, transporte = ?, status = 'Marcado' WHERE pk_Agendamento = ?");

        $stmt->bind_param("sss", $fkAnimal, $transporte, $pkAgen);

        $stmt->execute();

        if (mysqli_affected_rows($conn)){
            $_SESSION['agenCliFun'] = "Agendamento realizado com sucesso";
        }
    }
}

function verificar(){
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $header = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/Pet-Shop/';

    $cpf = $_GET['cpf'];

    $stmt = $conn->prepare("SELECT pk_Cliente from Clientes
    where cpf = ?");

    $stmt->bind_param("s", $cpf);

    // Executa o sql
    $stmt->execute();

    // Pega o resultado do banco
    $resultado = $stmt->get_result();


    if (mysqli_num_rows($resultado) > 0){
        $id = $resultado->fetch_all()[0][0];

        $_SESSION['idCliente'] = $id;

        $tabela = "
        <fieldset>
            <div>
                <label for='nome'>Nome</label><br>
                <input type='text' name='nome' id='nome' placeholder='Digite o nome'>
            </div>

            <div>
                <label for='dataNasc'>Data de Nascimento </label><br>
                <input type='date' name='dataNasc' id='data' placeholder='Digite a data de nascimento'>
            </div>
            
            <div>
                <label for='sexo'>Sexo</label><br>
                <select name='sexo' id='sexo'>
                    <option value='F'>Feminino</option>
                    <option value='M'>Masculino</option>
                    <option value='I'>Intersexo</option>
                </select>
            </div>

            <div>
                <label for='espec'>Espécie</label><br>
                <input type='text' id='espec' name='espec' list='especs' onchange='datalistRacas()' placeholder='Digite a espécie'>

                <datalist id='especs'>
                    <option value='Cachorro'>
                    <option value='Coelho'>
                    <option value='Gato'>
                    <option value='Hamster'>
                    <option value='Pássaro'>
                    <option value='Peixe'>
                    <option value='Tartaruga'>
                </datalist>
            </div>

            <div>
                <label for='raca'>Raça</label><br>
                <input type='text' name='raca' id='raca-input' list='racas' placeholder='Digite a raça'>

                <datalist id='racas'>
                </datalist>
            </div>

            <div>
                <label for='peso'>Peso (Kg)</label><br>
                <input type='number' name='peso' id='peso' step=0.01 pattern='[0-9]*' placeholder='Digite o peso'>
            </div>

            <div>
                <label for='cor'>Cor</label><br>
                <input type='text' name='cor' id='cor-input' placeholder='Digite a cor' list='cores-animais-list'>

                <datalist id='cores-animais-list'>
                    <option value='Amarelo'>
                    <option value='Azul'>
                    <option value='Branco'>
                    <option value='Bronze'>
                    <option value='Cinza'>
                    <option value='Dourado'>
                    <option value='Laranja'>
                    <option value='Marrom'>
                    <option value='Preto'>
                    <option value='Prateado'>
                    <option value='Rosa'>
                    <option value='Roxo'>
                    <option value='Verde'>
                    <option value='Vermelho'>
                </datalist>
            </div>
        </fieldset>

        <button type='button' value='Cadastrar' onclick='validarCampo()'>Cadastrar</button>

    </form>
        ";
        
    } else {

        $tabela = "Nenhum CPF encontrado";

    }

    $retornar = array('formAltAnimal', $tabela);
    return json_encode($retornar);
}

function tabelaComentarios() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Pet-Shop/backend/conexao.php');
    $header = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/Pet-Shop/pages/funcionario/comentarios.php';
    
    
    $filtroData = $_GET['data'];

    if ($filtroData == ''){ 
        $filtroData = date("Y-m-d");
    }

    $filtroMensagem = "%" . $_GET['pesq'] . "%";

    // Receber o número da página
    $pagina_atual = filter_input(INPUT_GET, 'pag', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    // Setar a quantidade de items por pagina
    $qnt_result_pg = 5;

    // Calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    // String de preparação
    $stmt = $conn->prepare("SELECT nome, telefone, email, mensagem, `data`,
    pk_Comentario FROM Comentarios
    WHERE `data` = ?
    AND mensagem LIKE ?
    ORDER BY nome
    LIMIT ?, ?");

    $stmt->bind_param("ssss", $filtroData, $filtroMensagem, $inicio, $qnt_result_pg);

    // Executa o sql
    $stmt->execute();
    // Pega o resultado do banco
    $resultado = $stmt->get_result();

    // String que será retornada na tabela
    $tabela = "";

    if (mysqli_num_rows($resultado) == 0) {
        $tabela = $tabela . "

            <div class='box-sem-cometario'>
                <label class='mensagem-sem-cometarios'>Não há mensagems no sistema</label>
            </div>
            
            ";
    } else {
        // Pega cada linha da query e monta as linhas da tabela
        foreach ($resultado->fetch_all() as $row) {
            // Formata a data
            $data = date('d/m/Y', strtotime($row[4]));
            $tabela = $tabela .

            
                "
                    <div class='box-mensagem'>
    
                        <div class='superior'>
                            <i class='bi bi-chat-square-quote'></i>
                            <label>Mensagem</label>
                        </div>

                        <div class='mensagem'>

                            <div class='box-info box-cliente'>
                                <i class='bi bi-person-circle'></i>
                                <label>Cliente:</label>
                                <p>$row[0]</p>
                            </div>

                            <div class='dado-triplo'>
                                <div class='box-info'>
                                    <i class='bi bi-calendar-check'></i>
                                    <label>Data:</label>
                                    <p>$data</p>
                                </div>

                                <div class='box-info'>
                                    <i class='bi bi-phone-vibrate'></i>
                                    <label>Telefone:</label>
                                    <p>$row[1]</p>
                                </div>

                                <div class='box-info'>
                                    <i class='bi bi-envelope'></i>
                                    <label>E-mail:</label>
                                    <p>$row[2]</p>
                                </div>

                            </div>
                            <p class='texto'>$row[3]</p>
                            <a class='email' href='mailto:" . $row[2] . "'>Enviar email</a>
                        </div>


                       
                    </div>
                ";
        }
    }

    // Paginação - Somar a quantidade de usuários
    $stmtCount = $conn->prepare("SELECT COUNT(PK_Comentario) AS num_result FROM Comentarios
        WHERE `data` = ?
        AND mensagem LIKE ?");
    $stmtCount->bind_param("ss", $filtroData, $filtroMensagem);
    // Executa o sql
    $stmtCount->execute();
    // Pega o resultado do banco
    $resultado = $stmtCount->get_result();
    $row_pg = mysqli_fetch_assoc($resultado);

    // Quantidade de pagina
    $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

    // Limitar os link antes depois
    $max_links = 2;
    $linkPaginas = "<a href='$header?pagina=1&pesq=". $_GET['pesq']. "&data=" . $_GET['data'] . "'><</a> ";

    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
        if ($pag_ant >= 1) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_ant&pesq=". $_GET['pesq']. "&data=" . $_GET['data'] . "'>$pag_ant</a> ";
        }
    }

    $linkPaginas =  $linkPaginas . "<p class='selecionado'>" . $pagina . "</p>";

    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
        if ($pag_dep <= $quantidade_pg) {
            $linkPaginas =  $linkPaginas . "<a href='$header?pagina=$pag_dep&pesq=". $_GET['pesq']. "&data=" . $_GET['data'] . "'>$pag_dep</a> ";
        }
    }

    $linkPaginas = $linkPaginas . "<a href='$header?pagina=$quantidade_pg&pesq=". $_GET['pesq']. "&data=" . $_GET['data'] . "'>></a>";

    $retornar = array('tabela', $tabela, 'links', $linkPaginas);
    return json_encode($retornar);
}


function verificarSession($lista){
    foreach ($lista as $item){
        if (!isset($_SESSION[$item])){
            $_SESSION[$item] = false;
        } else {
            $retorno = $_SESSION[$item];
            unset($_SESSION[$item]);
            return "'" . $retorno . "'";
        }
    }
}
