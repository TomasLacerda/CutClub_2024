<?php
// Inicia sessão
session_start();

// Cadastro
if (isset($_POST['cadastrar'])) {
    cadastrarDatas();
// Buscar
} else {
    header("Location: ../View/home.php");
}

// Função cadastrarDatas()
function cadastrarDatas()
{
    // Incluir arquivos
    include_once "../Model/DtIndisponivel.php";
    include_once "../Model/DtIndisponivelService.php";

    //if (!$_POST['diaSemana'] || !$_POST['dtInicio']) {
    //    // Mostra mensagem de erro
    //    print json_encode(array(
    //        'mensagem' => 'Essa data de recesso já está sendo utilizada.',
    //        'campo' => 'campo',
    //        'codigo' => 0
    //    ));
    //    exit();
    //}

    // Retorno Json - validar
    header('Content-Type: application/json');

    $hrFim = $_POST['hrFinal'];
    $dtInicio = $_POST['dtInicio'];
    //$descricao = $_POST['descricao'];
    $idBarbeiro = isset($_POST['idBarbeiro']) ? $_POST['idBarbeiro'] : '';
    $dtFimRegra = $_POST['dtFinal'];
    $hrInicio = $_POST['hrInicio'];
    $diaSemana = $_POST['semana'];
    // $hrFimInt = $_POST['hora_fim_intervalo'];
    // $hrInicioInt = $_POST['hora_inicio_intervalo'];

    // Cria os objetos
    $serviceIndisponivel = new DtIndisponivelService();
    $dtInisponivel = new DtIndisponivel();

    // Preenche os objetos
    $dtInisponivel->hora_fim = $hrFim;
    //$dtInisponivel->descricao = $descricao;
    $dtInisponivel->id_semana = $diaSemana;
    $dtInisponivel->hora_inicio = $hrInicio;
    $dtInisponivel->data_inicio = $dtInicio;
    $dtInisponivel->id_barbeiro = $idBarbeiro;
    $dtInisponivel->data_fim_regra = $dtFimRegra;

    // Envia os objetos
    $response = $serviceIndisponivel->cadastrarDatasService($dtInisponivel);

    // Verifica o tipo de retorno
    if ($response['sucesso']) {
        // Mostra mensagem de sucesso
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'codigo' => 1
        ));
        exit();
    } else {
        // Mostra mensagem de erro
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'campo' => $response['campo'],
            'codigo' => 0
        ));
        exit();
    }
}