<?php
class DtIndisponivelService
{
    // Atributos da classe
    public function cadastrarDatasService($dataIndisponivel)
    {
        // Verificar se os campos foram preenchidos
        //$campo = $this->verificarCampo($dataIndisponivel->dtRecesso, "Data que a loja não abrirá");
        //if (!$campo['sucesso']) return $campo;

        //$resultado = $this->buscarIndisponivelService($dataIndisponivel);

        // Caso retorne algo do banco
        //if ($resultado != null) {
        //    return array (
        //        "mensagem" => "Esta data de recesso já está sendo utilizada.",
        //        "campo" => "#dt_recesso",
        //        "sucesso" => false
        //    );
        //}

        include_once "DtIndisponivelDAO.php";
        $dao = new DtIndisponivelDAO();
        $cadastrou = $dao->CadastrarDtIndisponivel($dataIndisponivel);

        if ($cadastrou) {
            return array (
                'mensagem' => "Cadastro efetuado com sucesso!",
                'sucesso' => true
            );
        } else {
            return array (
                'mensagem' => "Erro ao efetuar o cadastro.",
                'campo' => "",
                'sucesso' => false
            );
        }
    }

    private function buscarIndisponivelService($dataIndisponivel)
    {
        // incluir o arquivo contatoDAO
        include_once "DtIndisponivelDAO.php";

        $dao = new DtIndisponivelDAO();

        return $dao->buscarIndisponivelDAO($dataIndisponivel);
    }

    private function verificarCampo($valorCampo, $nomeCampo)
    {
        // Verifica se o campo foi preenchido
        if (strcmp($valorCampo, "") == 0) {
            return array (
                'mensagem' => "Preencha a $nomeCampo",
                'campo' => "#$nomeCampo",
                'sucesso' => false
            );
        }
        return array (
            'sucesso' => true
        );
    }
}