<?php
class DtIndisponivelService
{
    // Atributos da classe
    public function cadastrarDatasService($dataIndisponivel)
    {
        // Verificar se os campos foram preenchidos
        $campo = $this->verificarCampo($dataIndisponivel->data_inicio, "Preencha o campo 'Data inicial'!");
        if (!$campo['sucesso']) return $campo;
        $campo = $this->verificarCampo($dataIndisponivel->hora_inicio, "Preencha o campo 'Hora inicial'!");
        if (!$campo['sucesso']) return $campo;
        $campo = $this->verificarCampo($dataIndisponivel->hora_fim, "Preencha o campo 'Hora final'!");
        if (!$campo['sucesso']) return $campo;
        $campo = $this->verificarCampo($campo = $dataIndisponivel->id_barbeiro == NULL ? '' : 'Barbeiro', "Preencha o campo 'Selecione o Barbeiro'!");
        if (!$campo['sucesso']) return $campo;
        if ($dataIndisponivel->hora_inicio != NULL && strpos($dataIndisponivel->hora_inicio, '_') !== false) {
            list($hora_inicio1, $hora_inicio2) = explode('_', $dataIndisponivel->hora_inicio);
        }
        if ($dataIndisponivel->hora_fim != NULL && strpos($dataIndisponivel->hora_fim, '_') !== false) {
            list($hora_fim1, $hora_fim2) = explode('_', $dataIndisponivel->hora_fim);
        }

        $horaInicio = $hora_inicio1;
        $horaFim = $hora_fim1;
        
        // Verifica se ambos os campos de hora estão preenchidos
        if (!empty($horaInicio) && !empty($horaFim)) {
            // Separa as horas e minutos
            list($horaInicioHora, $horaInicioMinuto) = explode(':', $horaInicio);
            list($horaFimHora, $horaFimMinuto) = explode(':', $horaFim);
        
            // Converte as horas e minutos para minutos totais
            $totalMinutosInicio = intval($horaInicioHora) * 60 + intval($horaInicioMinuto);
            $totalMinutosFim = intval($horaFimHora) * 60 + intval($horaFimMinuto);

            if ($totalMinutosInicio > $totalMinutosFim) {
                return array (
                    "mensagem" => "A hora inicial deve ser menor que a hora final",
                    "campo" => "#hora_inicial",
                    "sucesso" => false
                );
            }
        }

        $horaFimInt = $hora_fim2;
        $horaInicioInt = $hora_inicio2;

        // Verifica se ambos os campos de hora estão preenchidos
        if (!empty($horaInicioInt) && !empty($horaFimInt)) {
            // Separa as horas e minutos
            list($horaInicioHora, $horaInicioMinuto) = explode(':', $horaInicioInt);
            list($horaFimHora, $horaFimMinuto) = explode(':', $horaFimInt);
        
            // Converte as horas e minutos para minutos totais
            $totalMinutosInicioInt = intval($horaInicioHora) * 60 + intval($horaInicioMinuto);
            $totalMinutosFimInt = intval($horaFimHora) * 60 + intval($horaFimMinuto);

            if ($totalMinutosInicio > $totalMinutosInicioInt) {
                return array (
                    "mensagem" => "A hora inicial deve ser menor que a hora inicial do intervalo",
                    "campo" => "#hora_inicial",
                    "sucesso" => false
                );
            }
    
            if ($totalMinutosInicioInt > $totalMinutosFimInt) {
                return array (
                    "mensagem" => "A hora inicial de intervalo deve ser menor que a hora final de intervalo",
                    "campo" => "#hora_inicial",
                    "sucesso" => false
                );
            }
    
            if ($totalMinutosFimInt > $totalMinutosFim) {
                return array (
                    "mensagem" => "A hora final de intervalo deve ser menor que a hora final",
                    "campo" => "#hora_final_intervalo",
                    "sucesso" => false
                );
            }
        }

        $resultado = $this->buscarIndisponivelService($dataIndisponivel);

        // Caso retorne algo do banco
        if ($resultado != null) {
            return array (
                "mensagem" => "Já existe essa data de início para o barbeiro: ".$resultado['nome_completo'],
                "campo" => "#dt_inicio",
                "sucesso" => false
            );
        }

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
        include_once "DtIndisponivelDAO.php";

        $dao = new DtIndisponivelDAO();

        return $dao->buscarIndisponivelDAO($dataIndisponivel);
    }

    private function recuperaDatas($stFiltro)
    {
        include_once "DtIndisponivelDAO.php";

        $dao = new DtIndisponivelDAO();

        return $dao->recuperaRelacionamento($stFiltro);
    }

    private function verificarCampo($valorCampo, $nomeCampo)
    {
        // Verifica se o campo foi preenchido
        if (strcmp($valorCampo, "") == 0 || strcmp($valorCampo, "_") == 0) {
            return array (
                'mensagem' => "$nomeCampo",
                'campo' => "#$nomeCampo",
                'sucesso' => false
            );
        }
        return array (
            'sucesso' => true
        );
    }
}