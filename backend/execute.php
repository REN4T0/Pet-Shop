<?php
include_once("../rotas.php");
require_once($funcoesRoute);

// Verifica qual função deve ser executada
switch ($_GET['function']) {
    case 'logoff':
        logoff();
        echo $loginCliRoute;
        break;
    case 'gerarTabelaAni':
        echo gerarTabelaAni();
        break;
    case 'altAnimal':
        echo altAnimal();
        break;
    case 'gerarTabelaAgenCli':
        echo gerarTabelaAgenCli();
        break;
    case 'checkAnimais':
        echo checkAnimais();
        break;
    case 'gerarTabelaFazAgenCli':
        echo gerarTabelaFazAgenCli();
        break;
    case 'fazAgendamentoCli':
        echo fazAgendamentoCli();
        break;
    case 'gerarTabelaAgenFun':
        echo gerarTabelaAgenFun();
        break;
    case 'gerarTabelaDeleteFun':
        echo gerarTabelaDeleteFun();
        break;
    case 'tabelaFunAgenCli':
        echo tabelaFunAgenCli();
        break;
    case 'apagarFun':
        echo apagarFuncionario();
        break;
    case 'update':
        echo update("Agendamentos", "`status` = 'Concluido'", "pk_Agendamento = ?", ["s", $_GET['id']]);
        break;
    case 'profissionais':
        echo profissionais();
        break;
    case 'getDesc':
        echo getDesc();
        break;
    case 'altMeuPerfilCli':
        echo altMeuPerfilCli();
        break;
    case 'animais':
        echo animais();
        break;
    case 'verificar':
        echo verificar();
        break;
    case 'fazerAgenParaCli':
        echo fazerAgenParaCli();
        break;
    case 'tabelaComentarios':
        echo tabelaComentarios();
        break;
    case 'gerarTabelaVeiculos':
        echo gerarTabelaVeiculos();
        break;
    case 'desativarCarro':
        echo desativarCarro();
        break;
    case 'reativarCarro':
        echo reativarCarro();
        break;
    case 'getDrivers':
        echo getDrivers();
        break;
    case 'viewDrivers':
        echo viewDrivers();
        break;
}
