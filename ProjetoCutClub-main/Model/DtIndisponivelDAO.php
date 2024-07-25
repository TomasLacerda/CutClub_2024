<?php
class DtIndisponivelDAO
{
    public function excluirHorarioDAO($horario)
    {
        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "DELETE FROM dt_indisponivel WHERE id in (?)";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("s", $id);

        // Recebe os valores guardados no objeto
        $id = $horario->id;

        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;
    }

    public function recuperaRelacionamento($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT 
                    id,
                    id_barbeiro,
                    data_inicio,
                    data_fim_regra,
                    hora_inicio,
                    hora_fim,
                    descricao,
                    dias_semana,
                    barbeiro,
                    bo_trabalho,
                    IFNULL(hora_inicio_intervalo, 'N/A') AS hora_inicio_intervalo,
                    IFNULL(hora_fim_intervalo, 'N/A') AS hora_fim_intervalo
                FROM (
                    SELECT 
                        dt_indisponivel.id,
                        contato.id as id_barbeiro,
                        DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio,
                        CASE
                            WHEN data_fim_regra = '0000-00-00' THEN 'Indefinido'
                            ELSE DATE_FORMAT(data_fim_regra, '%d/%m/%Y')
                        END AS data_fim_regra,
                        TIME_FORMAT(hora_inicio, '%H:%i') AS hora_inicio,
                        TIME_FORMAT(hora_fim, '%H:%i') AS hora_fim,
                        descricao,
                        GROUP_CONCAT(DISTINCT semana.dia_semana ORDER BY semana.dia_semana SEPARATOR ', ') AS dias_semana,
                        CASE 
                            WHEN contato.nome IS NULL THEN 'Todos' 
                            ELSE CONCAT(contato.nome, ' ', contato.sobrenome) 
                        END AS barbeiro,
                        bo_trabalho,
                        TIME_FORMAT(MAX(CASE WHEN bo_trabalho = 0 THEN hora_inicio END), '%H:%i') AS hora_inicio_intervalo,
                        TIME_FORMAT(MAX(CASE WHEN bo_trabalho = 0 THEN hora_fim END), '%H:%i') AS hora_fim_intervalo
                    FROM 
                        dt_indisponivel 
                    LEFT JOIN 
                        contato ON contato.id = dt_indisponivel.id_barbeiro
                    LEFT JOIN
                        semana ON dt_indisponivel.id_semana = semana.id
                    GROUP BY 
                        data_inicio, data_fim_regra, id_barbeiro
                ) AS subconsulta
    
                
                ".$stFiltro;
    
        // cria o prepared statement
        $stmt = $conexao->prepare($sql);
    
        // Executa o Statement
        $stmt->execute();
    
        // Guarda todos os resultados encontrados em um array
        $resultado = $stmt->get_result();
    
        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);
    
        return $resultado;
    }

    public function CadastrarDtIndisponivel($dataIndisponivel) 
    {
        require_once "ConexaoDB.php";

        // Suponha que $dataIndisponivel->dia_semana e $dataIndisponivel->id_barbeiro sejam arrays
        $diasSemana = $dataIndisponivel->id_semana != NULL ? $dataIndisponivel->id_semana : array();
        $idBarbeiros = $dataIndisponivel->id_barbeiro != NULL && $dataIndisponivel->id_barbeiro[0] != "Todos" ? $dataIndisponivel->id_barbeiro : '';

        // Abre uma conex�o com o banco de dados fora do loop
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();
        
        // Monta o SQL de inser��o
        $sql = "INSERT INTO dt_indisponivel (id_semana, hora_inicio, hora_fim, data_inicio, data_fim_regra, id_barbeiro, bo_trabalho, descricao)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Cria o prepared statement fora do loop
        $stmt = $conexao->prepare($sql);
        
        // Vincula os par�metros do statement
        $stmt->bind_param("isssssss", $dia_Semana, $horaInicio, $horaFim, $dtInicio, $dtFimRegra, $idBarbeiro, $bo_trabalho, $descricao);
        
        // Itera sobre diasSemana e idBarbeiros
        if (count($diasSemana) > 0) {
            foreach ($diasSemana as $diaSemana) {

                $dtInicio =  $dataIndisponivel->data_inicio != NULL ? $dataIndisponivel->data_inicio : '';
                $descricao = $dataIndisponivel->descricao;
                $dia_Semana = $diaSemana;
                $idBarbeiro = $idBarbeiros;
                $dtFimRegra =  $dataIndisponivel->data_fim_regra  != NULL ? $dataIndisponivel->data_fim_regra  : '';
                $bo_trabalho = $dataIndisponivel->bo_trabalho;
    
                $hora_inicio1 = '';
                $hora_inicio2 = '';
                $hora_fim1 = '';
                $hora_fim2 = '';
    
                if ($idBarbeiro != '') {
                    foreach ($idBarbeiros as $idBarbeiro) {
                        $idBarbeiro = $idBarbeiro;
    
                        if ($dataIndisponivel->hora_inicio != NULL && strpos($dataIndisponivel->hora_inicio, '_') !== false) {
                            list($hora_inicio1, $hora_inicio2) = explode('_', $dataIndisponivel->hora_inicio);
                        }
                        if ($dataIndisponivel->hora_fim != NULL && strpos($dataIndisponivel->hora_fim, '_') !== false) {
                            list($hora_fim1, $hora_fim2) = explode('_', $dataIndisponivel->hora_fim);
                        }
    
                        if ($hora_inicio1 != '' && $hora_fim1) {
                            $horas = array();
                            $horas[] = $hora_inicio1;
                            $horas[] = $hora_fim1;
        
                            $horaFim = $horas[1] != '' ? $horas[1] : '';
                            $horaInicio = $horas[0] != '' ? $horas[0] : '';
                        
                            // Executa o statement
                            $cadastrou = $stmt->execute();
                        }
                        if ($hora_inicio2 != '' && $hora_fim2 != '') {
                            $bo_trabalho = '0';
                            $horasInt = array();
                            $horasInt[] = $hora_inicio2;
                            $horasInt[] = $hora_fim2;
        
                            $horaFim = $horasInt[1] != '' ? $horasInt[1] : '';
                            $horaInicio = $horasInt[0] != '' ? $horasInt[0] : '';
                        
                            // Executa o statement
                            $cadastrou = $stmt->execute();
                            $bo_trabalho = $dataIndisponivel->bo_trabalho;
                        }
                    }
                } else {
                    if ($dataIndisponivel->hora_inicio != NULL && strpos($dataIndisponivel->hora_inicio, '_') !== false) {
                        list($hora_inicio1, $hora_inicio2) = explode('_', $dataIndisponivel->hora_inicio);
                    }
                    if ($dataIndisponivel->hora_fim != NULL && strpos($dataIndisponivel->hora_fim, '_') !== false) {
                        list($hora_fim1, $hora_fim2) = explode('_', $dataIndisponivel->hora_fim);
                    }
    
                    if ($hora_inicio1 != '' && $hora_fim1) {
                        $horas = array();
                        $horas[] = $hora_inicio1;
                        $horas[] = $hora_fim1;
    
                        $horaFim = $horas[1] != '' ? $horas[1] : '';
                        $horaInicio = $horas[0] != '' ? $horas[0] : '';
                    
                        // Executa o statement
                        $cadastrou = $stmt->execute();
                    }
                    if ($hora_inicio2 == '' && $hora_fim2 == '') {
                        $horasInt = array();
                        $horasInt[] = $hora_inicio2;
                        $horasInt[] = $hora_fim2;
    
                        $horaFim = $horasInt[1] != '' ? $horasInt[1] : '';
                        $horaInicio = $horasInt[0] != '' ? $horasInt[0] : '';
                    
                        // Executa o statement
                        $cadastrou = $stmt->execute();
                    }
                }
            }
        } else {
            $dtInicio =  $dataIndisponivel->data_inicio != NULL ? $dataIndisponivel->data_inicio : '';
            $descricao = $dataIndisponivel->descricao;
            $dia_Semana = '';
            $idBarbeiro = $idBarbeiros;
            $dtFimRegra =  $dataIndisponivel->data_fim_regra  != NULL ? $dataIndisponivel->data_fim_regra  : '';
            $bo_trabalho = $dataIndisponivel->bo_trabalho;

            $hora_inicio1 = '';
            $hora_inicio2 = '';
            $hora_fim1 = '';
            $hora_fim2 = '';

            if ($idBarbeiro != '') {
                foreach ($idBarbeiros as $idBarbeiro) {
                    $idBarbeiro = $idBarbeiro;

                    if ($dataIndisponivel->hora_inicio != NULL && strpos($dataIndisponivel->hora_inicio, '_') !== false) {
                        list($hora_inicio1, $hora_inicio2) = explode('_', $dataIndisponivel->hora_inicio);
                    }
                    if ($dataIndisponivel->hora_fim != NULL && strpos($dataIndisponivel->hora_fim, '_') !== false) {
                        list($hora_fim1, $hora_fim2) = explode('_', $dataIndisponivel->hora_fim);
                    }

                    if ($hora_inicio1 != '' && $hora_fim1) {
                        $horas = array();
                        $horas[] = $hora_inicio1;
                        $horas[] = $hora_fim1;
    
                        $horaFim = $horas[1] != '' ? $horas[1] : '';
                        $horaInicio = $horas[0] != '' ? $horas[0] : '';
                    
                        // Executa o statement
                        $cadastrou = $stmt->execute();
                    }
                    if ($hora_inicio2 != '' && $hora_fim2 != '') {
                        $bo_trabalho = '0';
                        $horasInt = array();
                        $horasInt[] = $hora_inicio2;
                        $horasInt[] = $hora_fim2;
    
                        $horaFim = $horasInt[1] != '' ? $horasInt[1] : '';
                        $horaInicio = $horasInt[0] != '' ? $horasInt[0] : '';
                    
                        // Executa o statement
                        $cadastrou = $stmt->execute();
                        $bo_trabalho = $dataIndisponivel->bo_trabalho;
                    }
                }
            } else {
                if ($dataIndisponivel->hora_inicio != NULL && strpos($dataIndisponivel->hora_inicio, '_') !== false) {
                    list($hora_inicio1, $hora_inicio2) = explode('_', $dataIndisponivel->hora_inicio);
                }
                if ($dataIndisponivel->hora_fim != NULL && strpos($dataIndisponivel->hora_fim, '_') !== false) {
                    list($hora_fim1, $hora_fim2) = explode('_', $dataIndisponivel->hora_fim);
                }
                if ($hora_inicio1 != '' && $hora_fim1) {
                    $horas = array();
                    $horas[] = $hora_inicio1;
                    $horas[] = $hora_fim1;

                    $horaFim = $horas[1] != '' ? $horas[1] : '';
                    $horaInicio = $horas[0] != '' ? $horas[0] : '';
                
                    // Executa o statement
                    $cadastrou = $stmt->execute();
                }
                if ($hora_inicio2 != '' && $hora_fim2 != '') {
                    $bo_trabalho = '0';
                    $horasInt = array();
                    $horasInt[] = $hora_inicio2;
                    $horasInt[] = $hora_fim2;

                    $horaFim = $horasInt[1] != '' ? $horasInt[1] : '';
                    $horaInicio = $horasInt[0] != '' ? $horasInt[0] : '';
                
                    // Executa o statement
                    $cadastrou = $stmt->execute();
                    $bo_trabalho = $dataIndisponivel->bo_trabalho;
                }
            }
        }

        
        // Fecha o statement e a conexao
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;
    }

    public function buscarIndisponivelDAO($dtIndisponivel)
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta a query de busca
        $sql = "SELECT CONCAT(contato.nome, ' ', contato.sobrenome) AS nome_completo FROM dt_indisponivel
                    JOIN contato ON contato.id = dt_indisponivel.id_barbeiro
                    WHERE data_inicio = ? AND id_barbeiro = ?
                GROUP BY contato.nome, contato.sobrenome ";
    
        // Cria o prepared statement
        $stmt = $conexao->prepare($sql);
    
        // Vincula os parâmetros que serão inseridos na query
        $stmt->bind_param("si", $data_inicio, $id_barbeiro);
    
        // Recebe os valores guardados no objeto
        $data_inicio = $dtIndisponivel->data_inicio;
        $idBarbeiros = $dtIndisponivel->id_barbeiro;
    
        // Inicializa uma variável para armazenar o resultado da consulta
        $resultado = null;
    
        // Executa a consulta para cada ID de barbeiro fornecido
        foreach ($idBarbeiros as $idBarbeiro) {
            // Define o ID do barbeiro
            $id_barbeiro = $idBarbeiro;
    
            // Executa o statement
            $stmt->execute();
    
            // Guarda o resultado encontrado
            $resultado = $stmt->get_result()->fetch_assoc();
    
            // Se encontrou um resultado, interrompa o loop
            if ($resultado) {
                break;
            }
        }
    
        // Fecha o statement e a conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);
    
        return $resultado;
    }
    
}