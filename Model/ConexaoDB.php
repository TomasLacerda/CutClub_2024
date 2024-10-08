<?php
class ConexaoDB
{
    public function abrirConexaoDB()
    {
        // Dados para a conexao (localhost)
        $banco = "agenda_php";
        $servidor = "localhost";
        $usuario = "root";
        $senha = "";

        // Conexao
        $conexao = mysqli_connect($servidor, $usuario, $senha);

        // Seleciona o DB
        mysqli_select_db($conexao, $banco);

        // Força os dados a serem gravados no DB com o encoding correto
        mysqli_query($conexao, "SET NAMES 'utf8'");
        mysqli_query($conexao, "SET character_set_connection=utf8");
        mysqli_query($conexao, "SET character_set_client=utf8");
        mysqli_query($conexao, "SET character_set_results=utf8");

        // Encerra a tentativa se der erro
        if (mysqli_connect_errno()) {
            die(mysqli_connect_error());
        }

        return $conexao;
    }
    
    public function fecharConexaoDB($conexao)
    {
        mysqli_close($conexao);
    }

}