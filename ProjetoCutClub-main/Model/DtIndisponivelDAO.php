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

        // Fecha Statement e conexÃ£o
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
                dt_indisponivel.id,
                DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio,
                    CASE
                        WHEN data_fim_regra = '0000-00-00' THEN 'Indefinido'
                        ELSE DATE_FORMAT(data_fim_regra, '%d/%m/%Y')
                    END AS data_fim_regra,
                    TIME_FORMAT(hora_inicio, '%H:%i') AS hora_inicio,
                    TIME_FORMAT(hora_fim, '%H:%i') AS hora_fim,
                    descricao,
                    semana.dia_semana,
                    CASE 
                        WHEN contato.nome IS NULL THEN 'Todos' 
                        ELSE CONCAT(contato.nome, ' ', contato.sobrenome) 
                    END AS barbeiro  
                FROM 
                    dt_indisponivel 
                LEFT JOIN 
                    contato ON contato.id = dt_indisponivel.id
                LEFT JOIN
                    semana ON dt_indisponivel.id_semana = semana.id
                
                ".$stFiltro;
    
        // cria o prepared statement
        $stmt = $conexao->prepare($sql);
    
        // Executa o Statement
        $stmt->execute();
    
        // Guarda todos os resultados encontrados em um array
        $resultado = $stmt->get_result();
    
        // Fecha Statement e conexÃ£o
        $stmt->close();
        $db->fecharConexaoDB($conexao);
    
        return $resultado;
    }

    public function CadastrarDtIndisponivel($dataIndisponivel) 
    {
        require_once "ConexaoDB.php";

        // Suponha que $dataIndisponivel->dia_semana e $dataIndisponivel->id_barbeiro sejam arrays
        $diasSemana = $dataIndisponivel->id_semana;
        $idBarbeiros = $dataIndisponivel->id_barbeiro != NULL ? $dataIndisponivel->id_barbeiro : '';
        
        // Abre uma conexão com o banco de dados fora do loop
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();
        
        // Monta o SQL de inserção
        $sql = "INSERT INTO dt_indisponivel (id_semana, hora_inicio, hora_fim, data_inicio, data_fim_regra, id_barbeiro, descricao)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // Cria o prepared statement fora do loop
        $stmt = $conexao->prepare($sql);
        
        // Vincula os parâmetros do statement
        $stmt->bind_param("issssis", $diasSemana, $horaInicio, $horaFim, $dtInicio, $dtFimRegra, $idBarbeiro, $descricao);
        
        // Itera sobre diasSemana e idBarbeiros
        foreach ($diasSemana as $diaSemana) {
            if ($idBarbeiros != '') {
                foreach ($idBarbeiros as $idBarbeiro) {
                    $diasSemana = $diaSemana;
                    $idBarbeiro = $idBarbeiro;
                    
                    // Recebe os valores para cada iteração
                    $horaFim = $dataIndisponivel->hora_fim != NULL ? $dataIndisponivel->hora_fim : '';
                    $dtInicio = $dataIndisponivel->data_inicio != NULL ? $dataIndisponivel->data_inicio : '';
                    $horaInicio = $dataIndisponivel->hora_inicio != NULL ? $dataIndisponivel->hora_inicio : '';
                    $dtFimRegra = $dataIndisponivel->data_fim_regra != NULL ? $dataIndisponivel->data_fim_regra : '';
                    $descricao = ""; // Defina a descrição conforme necessário
            
                    // Executa o statement
                    $cadastrou = $stmt->execute();
                }
            } else {
                $diasSemana = $diaSemana;
                $idBarbeiro = $idBarbeiro;
                
                // Recebe os valores para cada iteração
                $horaFim = $dataIndisponivel->hora_fim != NULL ? $dataIndisponivel->hora_fim : '';
                $dtInicio = $dataIndisponivel->data_inicio != NULL ? $dataIndisponivel->data_inicio : '';
                $horaInicio = $dataIndisponivel->hora_inicio != NULL ? $dataIndisponivel->hora_inicio : '';
                $dtFimRegra = $dataIndisponivel->data_fim_regra != NULL ? $dataIndisponivel->data_fim_regra : '';
                $descricao = ""; // Defina a descrição conforme necessário
        
                // Executa o statement
                $cadastrou = $stmt->execute();
            }

        }
        
        // Fecha o statement e a conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);
        
        return $cadastrou;
    }

    public function buscarIndisponivelDAO($dataIndisponivel)
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();

        $conexao = $db->abrirConexaoDB();

        // Monta query Busca
        $sql = " SELECT * FROM dt_indisponivel where dt_indisponivel.descricao is not null";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        // $stmt->bind_param("s", $dtIndisponivel);

        // Recebe os valores guardados no objeto
        // $dtIndisponivel = $dataIndisponivel->id;

        // Executa o Statement
        $stmt->execute();

        // Guarda o resultado encontrado
        $resultado = $stmt->get_result()->fetch_assoc();

        // Fecha Statement e conexï¿½o
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $resultado;
    }
}