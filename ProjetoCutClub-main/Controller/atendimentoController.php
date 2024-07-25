<?php
// Inicia sessão
session_start();

// Cadastro
if (isset($_POST['cadastrar'])) {
    cadastrarDatas();
// Servico
} if (isset($_POST['cadastrarServico'])) {
    cadastrarServico();
// Agendar
} if (isset($_POST['agendar'])) {
    agendarHorario();
// Tela Incio
} if(isset($_GET['id_barbeiro'])) {
    recuperaAgenda();
} else {
    header("Location: ../View/home.php");
}

function recuperaAgenda()
{
    include_once "../Model/SemanaDAO.php";
    include_once "../Model/dtIndisponivelDAO.php";
    include_once "../Model/AgendaDAO.php";
    include_once "../Model/ServicoDAO.php";

    $servicoDAO = new ServicoDAO();
    $stFiltro = "";

    if (isset($_REQUEST['idServico'])) {
        $stFiltro = " WHERE nome = '".$_REQUEST['idServico']."'";
    }
    $rsServicos = $servicoDAO->recuperaTodos($stFiltro);

    if ($rsServicos) {
        // Loop através dos resultados e adicionar cada dia da semana ao array $datasDesabilitadas
        while ($row = $rsServicos->fetch_assoc()) {
            $opcoesServicoDescricao[] = $row['descricao'];
            $opcoesServicoImagem[] = $row['imagem'];
            $opcoesServicoNome[] = $row['nome'];
            $opcoesServicoValor[] = $row['valor'];
            $opcoesServicoDuracao[] = $row['duracao'];
        }
    }
    
    $dtIndisponivelDAO = new dtIndisponivelDAO();
    $stFiltro = " WHERE id_barbeiro is NULL or id_barbeiro = " . $_REQUEST['id_barbeiro'];
    $rsSemana = $dtIndisponivelDAO->recuperaRelacionamento($stFiltro);

    // Verifica se há resultados da consulta
    if ($rsSemana) {
        // Loop através dos resultados e adicionar cada dia da semana ao array $datasDesabilitadas
        while ($row = $rsSemana->fetch_assoc()) {
            $opcoesdiaSemana[] = $row['dias_semana'];
        }
    }

    $stringDias = $opcoesdiaSemana[0];

    // Separe os dias da semana usando explode
    $diasSeparados = explode(', ', $stringDias);

    // Remova espaços em branco em excesso de cada dia da semana
    $diasLimpos = array_map('trim', $diasSeparados);

    // Crie um novo array associativo
    $opcoesdiaSemana = array();
    foreach ($diasLimpos as $indice => $dia) {
        $opcoesdiaSemana[$indice] = $dia;
    }

    $opcoesdata = array(
        '10/04/2024',
        '11/04/2024'
    );

    $horasDisp = array();

    ## HORAS AGENDADAS ##
    $agendaDAO = new AgendaDAO();
    $stFiltro = " WHERE id_barbeiro = ".$_REQUEST['id_barbeiro'];
    if (isset($_REQUEST['selected_date'])) {
        $stFiltro .= " AND DATE_FORMAT(dthora_execucao, '%d/%m/%Y') ='".$_REQUEST['selected_date']."'";
    }

    $rsHorasAgendadas = $agendaDAO->recuperaRelacionamento($stFiltro);

    // Verifique se a consulta retornou resultados
    if ($rsHorasAgendadas) {
        // Loop através dos resultados e adicionar cada hora agendada ao array $horasAgendadas
        while ($row = $rsHorasAgendadas->fetch_assoc()) {
            $horasAgendadas[] = $row['hora_minuto'];
        }
    }

    ## HORAS DISPONIVEIS ##
    if (isset($_REQUEST['selected_date'])) {
        $stFiltro = " WHERE id_barbeiro is NULL or id_barbeiro = " . $_REQUEST['id_barbeiro'];
        $stFiltro .= " AND data_inicio < '".$_REQUEST['selected_date']."' AND data_fim_regra >= '".$_REQUEST['selected_date']."'";

        $rsHorarios = $dtIndisponivelDAO->recuperaRelacionamento($stFiltro);

        function timeToMinutes($time) {
            list($hours, $minutes) = explode(':', $time);
            return $hours * 60 + $minutes;
        }

        function minutesToTime($minutes) {
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
            return sprintf('%02d:%02d', $hours, $minutes);
        }

        $rsRecordSet = $rsHorarios->fetch_assoc();
        $hora_inicio = $rsRecordSet['hora_inicio'];
        $hora_fim = $rsRecordSet['hora_fim'];
        if ($opcoesServicoDuracao) {
            $duracao = explode(':', $opcoesServicoDuracao[0]);
            $totalMinutos = $duracao[0] * 60 + $duracao[1];  // Converts hours into minutes and adds minutes
            if ($duracao[0] <> '00') {
                $totalMinutos = ($duracao[0] * 60) + $duracao[1]; // This ensures that only the minutes are added to the total minutes if hour is not '00'
            } else {
                $totalMinutos = $duracao[1];  // If there is no hour, just take the minutes part
            }
        }
    
        $intervalo_minutos = $totalMinutos; // Duration of each slot

        $horasDisp = [];

        // INTERVALOS (APENAS SE EXISTIR)
        if ($rsRecordSet['hora_inicio_intervalo']) {
            $hora_inicio_intervalo = $rsRecordSet['hora_inicio_intervalo'];
            $hora_fim_intervalo = $rsRecordSet['hora_fim_intervalo'];

            // First interval
            $start = timeToMinutes($hora_inicio);
            $end = timeToMinutes($hora_inicio_intervalo);
            for ($i = $start; $i < $end; $i += $intervalo_minutos) {
                $horasDisp[] = minutesToTime($i);
            }

            // Second interval
            $start = timeToMinutes($hora_fim_intervalo);
            $end = timeToMinutes($hora_fim);
            for ($i = $start; $i < $end; $i += $intervalo_minutos) {
                $horasDisp[] = minutesToTime($i);
            }
        } else {
            // First interval
            $start = timeToMinutes($hora_inicio);
            $end = timeToMinutes($hora_fim);
            for ($i = $start; $i < $end; $i += $intervalo_minutos) {
                $horasDisp[] = minutesToTime($i);
            }
        }

        // Optionally filter out booked times if $horasAgendadas is defined
        if (!empty($horasAgendadas)) {
            $horasDisp = array_diff($horasDisp, $horasAgendadas);
        }
    }

    echo json_encode(array(
        'opcoes_servico_descricao' => $opcoesServicoDescricao,
        'opcoes_servico_duracao' => $opcoesServicoDuracao,
        'opcoes_servico_imagem' => $opcoesServicoImagem,
        'opcoes_servico_valor' => $opcoesServicoValor,
        'opcoes_servico_nome' => $opcoesServicoNome,
        'semana_desabilitadas' => $opcoesdiaSemana,
        'datas_desabilitadas' => $opcoesdata,
        'horas_do_dia' => $horasDisp
    ));

    exit();
}

function cadastrarDatas()
{
    // Incluir arquivos
    include_once "../Model/DtIndisponivel.php";
    include_once "../Model/DtIndisponivelService.php";
    include_once "../Model/ContatoDAO.php";

    $barbeiroDAO = new ContatoDAO();
    $countBarbeiro = '';
    if (isset($_POST['idBarbeiro'])) {
        $stFiltro = " WHERE barbeiro = 1";
        $resultado = $barbeiroDAO->recuperaTodos($stFiltro);
        if ($resultado->num_rows > 1 && $resultado->num_rows == count($_REQUEST['idBarbeiro'])) {
            $countBarbeiro = 'Todos';
        }
    }

    // Retorno Json - validar
    header('Content-Type: application/json');

    $hrFim = $_POST['hrFinal'];
    $dtInicio = $_POST['dtInicio'];
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $idBarbeiro = isset($_POST['idBarbeiro']) && $countBarbeiro == '' ? $_POST['idBarbeiro'] : NULL;
    $dtFimRegra = $_POST['dtFinal'];
    $hrInicio = $_POST['hrInicio'];
    $diaSemana = isset($_POST['semana']) ? $_POST['semana'] : '';
    $hrInicioInt = isset($_POST['hrInicioInt']) ? $_POST['hrInicioInt'] : '';
    $hrFimInt = isset($_POST['hrFinalInt']) ? $_POST['hrFinalInt'] : '';
    $boTrabalho = $_POST['diaDeTrabalho'];

    if ($hrInicioInt != '' && $hrFimInt == '' || $hrInicioInt == '' && $hrFimInt != '') {
        // Mostra mensagem de erro
        print json_encode(array(
            'mensagem' => 'Deve existir horário de início e fim de intervalo.',
            'campo' => 'campo',
            'codigo' => 0
        ));
        exit();
    }

    // Cria os objetos
    $serviceIndisponivel = new DtIndisponivelService();
    $dtIndisponivel = new DtIndisponivel();

    // Preenche os objetos
    $dtIndisponivel->hora_fim = $hrFim.'_'.$hrFimInt;
    $dtIndisponivel->descricao = $descricao;
    $dtIndisponivel->id_semana = $diaSemana;
    $dtIndisponivel->hora_inicio = $hrInicio.'_'.$hrInicioInt;
    $dtIndisponivel->data_inicio = $dtInicio;
    $dtIndisponivel->id_barbeiro = $idBarbeiro;
    $dtIndisponivel->bo_trabalho = $boTrabalho;
    $dtIndisponivel->data_fim_regra = $dtFimRegra;

    // Envia os objetos
    $response = $serviceIndisponivel->cadastrarDatasService($dtIndisponivel);

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

# Cadastrar Servico
function cadastrarServico()
{
    // Incluir arquivos
    include_once "../Model/Servico.php";
    include_once "../Model/ServicoService.php";
    include_once "../Model/ServicoDAO.php";

    // $servicoDAO = new ServicoDAO();
    // if (isset($_POST['nome'])) {
    //     $stFiltro = " WHERE nome = '".$_POST['nome']."'";
    //     $resultado = $servicoDAO->recuperaTodos($stFiltro);
    // }

    // Retorno Json - validar
    header('Content-Type: application/json');

    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $duracao = $_POST['duracao'];
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';

    // Cria os objetos
    $servicoService = new ServicoService();
    $Servico = new Servico();

    // Preenche os objetos
    $Servico->nome = $nome;
    $Servico->valor = $valor;
    $Servico->duracao = $duracao;
    $Servico->descricao = $descricao;
    $Servico->imagem = '';

    if (isset($_FILES['imagem'])) {
        // Move o arquivo para o diretório desejado
        $arquivo = $_FILES['imagem'];

        $pasta = "../View/arquivos/";
        $nomeDoArquivo = $arquivo['name'];
        $novoNomeDoArquivo = uniqid();
        $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

        $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
        move_uploaded_file($arquivo['tmp_name'], $path);

        $Servico->imagem = $path;
        if ($extensao != "jpg" && $extensao != "png") {
            print json_encode(array(
                'mensagem' => 'Tipo de arquivo inválido.',
                'campo' => 'campo',
                'codigo' => 0
            ));
            exit();
        }

        // Agora você pode armazenar $caminhoCompleto no banco de dados
    }

    // Envia os objetos
    $response = $servicoService->cadastrarServicoService($Servico);

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

// Função cadastrarDatas()
function agendarHorario()
{
    // Incluir arquivos
    include_once "../Model/Agenda.php";
    include_once "../Model/AgendaService.php";
    include_once "../Model/ServicoDAO.php";
    include_once "../Model/ContatoDAO.php";

    $servicoDAO = new ServicoDAO();
    $stFiltro = " WHERE nome = '".$_REQUEST['idServico']."'";
    $servico = $servicoDAO->recuperaTodos($stFiltro);

    while ($coluna = $servico->fetch_assoc()) {
        $idServico[] = $coluna['id'];
    }

    $barbeiroDAO = new ContatoDAO();
    $stFiltro = " WHERE barbeiro = 1 AND id = ".$_POST["idBarbeiro"];
    $rsBarbeiro = $barbeiroDAO->recuperaTodos($stFiltro);
    while ($coluna = $rsBarbeiro->fetch_assoc()) {
        $idBarbeiro[] = $coluna['id'];
    }

    // Retorno Json - validar
    header('Content-Type: application/json');

    $barbeiro = $idBarbeiro[0];
    $servico = $idServico[0];
    $dtServico = $_POST["dtServico"];
    $dtServico = DateTime::createFromFormat('d/m/Y', $dtServico);
    $dtServico = $dtServico->format("Y-m-d");
    $horario = $_POST["horario"];

    $timezone = new DateTimeZone('America/Sao_Paulo');
    $dateTime = new DateTime('now', $timezone);
    $timestamp = $dateTime->format("Y-m-d h:i:s");

    // Cria os objetos
    $agendaService = new AgendaService();
    $agenda = new Agenda();

    // Preenche os objetos
    $agenda->id_servico = $servico;
    $agenda->dthora_agendamento = $timestamp;
    $agenda->dthora_execucao = $dtServico.' '.$horario.'.00';
    // $agenda->descricao = $idBarbeiro;
    // $agenda->preco_atendimento = $dtFimRegra;
    $agenda->id_barbeiro = $barbeiro;
    $agenda->id_cliente = $_SESSION["id"];

    // Envia os objetos
    $response = $agendaService->agendarService($agenda);

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