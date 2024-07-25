<?php
class AgendaDAO
{
    public function recuperaTodos($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT * FROM contato
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

    public function recuperaRelacionamento($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT DATE_FORMAT(dthora_execucao, '%H:%i') AS hora_minuto, duracao
                FROM agenda
                JOIN servico
                    ON servico.id = agenda.id_servico
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

    public function recuperaHistorico($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT DATE_FORMAT(dthora_execucao, '%d/%m/%Y') AS data, agenda.id, servico.imagem, DATE_FORMAT(dthora_execucao, '%H:%i') AS hora_minuto, CONCAT(contato.nome, ' ', contato.sobrenome) AS barbeiro, servico.nome as servico, valor, dthora_consumo,confirmado, CONCAT(cliente.nome, ' ', cliente.sobrenome) AS cliente,
                        CASE WHEN dthora_consumo IS NULL THEN 'FALSE'
                        ELSE 'TRUE' END AS comparaceu 
                FROM agenda
                JOIN servico
                    ON servico.id = agenda.id_servico
                JOIN contato
                    ON contato.id = agenda.id_barbeiro
                JOIN contato as cliente
                    ON cliente.id = agenda.id_cliente
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

    public function agendarDAO($agendar) 
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();

        $conexao = $db->abrirConexaoDB();

        // monta o query cadastro

        $sql = "INSERT INTO agenda (id_servico, dthora_agendamento, dthora_execucao, dthora_consumo, descricao, preco_atendimento, id_barbeiro, id_cliente)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("isssssii", $id_servico, $dtagendamento, $dtexecucao, $dtconsumo, $descricao, $preco, $idBarbeiro, $idCliente);

        // Recebe os valores guardados no objeto
        $id_servico = $agendar->id_servico;
        $dtagendamento = $agendar->dthora_agendamento;
        $dtexecucao = $agendar->dthora_execucao;
        $dtconsumo = NULL;
        $descricao = '';
        $preco = 35.00;
        $idBarbeiro = $agendar->id_barbeiro;
        $idCliente = $agendar->id_cliente;

        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;

    }

    public function buscarContatoDAO($contato)
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();

        $conexao = $db->abrirConexaoDB();

        // Monta query Busca
        $sql = " SELECT * FROM contato WHERE email = ?";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("s", $email);

        $email = $contato->email;

        // Executa o Statement
        $stmt->execute();

        // Guarda o resultado encontrado
        $resultado = $stmt->get_result()->fetch_assoc();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $resultado;
    }

    public function editarContatoDAO($contato) 
    {
        include_once "ContatoDAO.php";

        $dao = new ContatoDAO();

        $infoContato = $dao->buscarContatoDAO($contato);

        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "UPDATE contato set nome = ?, sobrenome = ?, email = ?, senha = ?, telefone = ?
                WHERE contato.id in (".$infoContato['id'].")";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("sssss", $nome, $sobrenome, $email, $senha, $telefone);

        // Recebe os valores guardados no objeto
        $nome = $contato->nome;
        $email = $contato->email;
        $senha = $contato->senha == "" ? $infoContato['senha'] : $contato->senha;
        $sobrenome = $contato->sobrenome;

        $removeCaracteres = array("(", ")", "-", " ");
        $telefone = str_replace($removeCaracteres, "", $contato->telefone);

        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;

    }

    public function salvarFuncionarioDAO($contato) 
    {
        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "UPDATE contato set barbeiro = ?
                WHERE contato.id in (?)";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("ss", $barbeiro, $id);

        // Recebe os valores guardados no objeto
        $id = $contato->id;
        $barbeiro = $contato->barbeiro;
        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;
    }

    public function excluirAgendamentoDAO($agenda)
    {
        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "DELETE FROM agenda WHERE agenda.id in (?)";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("s", $id);

        // Recebe os valores guardados no objeto
        $id = $agenda->id;

        // Executa o Statement
        $deletou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $deletou;
    }

    public function confirmarCorteDAO($agenda)
    {
        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "UPDATE agenda SET confirmado = ? WHERE id in (".$agenda->id.")";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("s", $confirmado);

        // Recebe os valores guardados no objeto
        $confirmado = 1;

        // Executa o Statement
        $deletou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $deletou;
    }
}