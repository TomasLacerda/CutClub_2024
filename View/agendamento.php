<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento</title>
    <!-- Adicione os estilos e scripts necessários para o jQuery UI Datepicker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> <!-- Adicionando Moment.js -->

    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilos para o formulário */
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Estilos para a seção de resumo */
        #resumo {
            display: none;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Estilos para dispositivos móveis */
        @media (max-width: 768px) {
            .container {
                width: 100%;
                margin: 10px auto;
                padding: 10px;
            }
        }

        .highlight-unavailable {
            background-color: #FFA07A !important; /* Cor de fundo para datas indisponíveis */
            color: #000 !important; /* Cor do texto para datas indisponíveis */
        }
    </style>
</head>
<body>
    <?php
        include_once "../Model/ContatoDAO.php";        
        include_once "../Model/SemanaDAO.php";        

        $ContatoDAO = new ContatoDAO();
        $stFiltro = " WHERE barbeiro = 1";
        $rsBarbeiros = $ContatoDAO->recuperaTodos($stFiltro);

        $SemanaDAO = new SemanaDAO();
        $stFiltro = " WHERE id not in (SELECT id_semana FROM dt_indisponivel)";
        $rsSemana = $SemanaDAO->recuperaTodos($stFiltro);

        // Busca as datas indisponíveis do banco de dados
        $datasDesabilitadas = array('segunda', 'domingo', '23/03/2024');
        echo "<script>var datasDesabilitadas = " . json_encode($datasDesabilitadas) . ";</script>";
        
        ?>

<div class="container">
    <h2>Agendar Horário</h2>
    <form id="formulario" action="processar_agendamento.php" method="POST">
        <div class="form-group">
            <label for="barbeiro">Selecione o barbeiro*</label>
            <select id="barbeiro" name="barbeiro">
                <option selected disabled>Selecione</option>
                <?php
                // Verificar se existem resultados da consulta
                if ($rsBarbeiros->num_rows > 0) {
                    // Loop através dos resultados e exibir as opções do select
                    while ($coluna = $rsBarbeiros->fetch_assoc()) {
                        echo "<option value='" . $coluna['id'] . "'>" . $coluna['nome'] . ' ' . $coluna['sobrenome'] ."</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum barbeiro encontrado</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group" id="opcoesDia" style="display: none;">
            <label for="dia">Selecione o dia:</label>
            <input type="text" id="datepicker" name="dia" disabled>
        </div>
        <div class="form-group" id="opcoesHorario" style="display: none;">
            <label for="horario">Selecione o horário:</label>
            <select id="horario" name="horario" disabled>
                <option value="10h">10h</option>
                <option value="11h">11h</option>
                <option value="12h">12h</option>
                <!-- Adicione mais opções de horário conforme necessário -->
            </select>
        </div>
        <div id="resumo" style="display: none;">
            <h3>Resumo do Agendamento:</h3>
            <p id="resumoTexto"></p>
        </div>
        <button type="submit" style="display: none;">Agendar</button>
    </form>
</div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    var barbeiroSelect = document.getElementById('barbeiro');
    var opcoesDia = document.getElementById('opcoesDia');
    var opcoesHorario = document.getElementById('opcoesHorario');
    var resumo = document.getElementById('resumo');
    var resumoTexto = document.getElementById('resumoTexto');
    var formulario = document.getElementById('formulario');
    var submitButton = document.querySelector('button[type="submit"]');
    var datepickerInput = document.getElementById('datepicker'); // Defina a variável datepickerInput aqui
    var selectHorario = document.getElementById('horario');

    // Quando o usuário selecionar um barbeiro
    barbeiroSelect.addEventListener('change', function () {
        opcoesDia.style.display = 'block';
        datepickerInput.removeAttribute('disabled'); // Habilita o Datepicker
        submitButton.style.display = 'none';
    });

    // Inicialize o Datepicker aqui
    $(datepickerInput).datepicker({
        dateFormat: 'dd/mm/yy',
        onSelect: function(dateText) {
            // Quando uma data é selecionada, exibe as opções de horário
            document.getElementById('opcoesHorario').style.display = 'block';
            selectHorario.removeAttribute('disabled');
        },
        beforeShowDay: function(date) {
            var dayOfWeek = date.getDay(); // Dia da semana (0 = Domingo, 1 = Segunda, ..., 6 = Sábado)
            var formattedDate = $.datepicker.formatDate('dd/mm/yy', date); // Formata a data para corresponder ao formato do array

            // Verifica se a data está em datasDesabilitadas
            if (datasDesabilitadas.includes('domingo') && dayOfWeek === 0) {
                return [false, '']; // Desabilita Domingos
            } else if (datasDesabilitadas.includes('segunda') && dayOfWeek === 1) {
                return [false, '']; // Desabilita Segundas
            } else if (datasDesabilitadas.includes(formattedDate)) {
                return [false, '']; // Desabilita datas específicas
            } else {
                return [true, '']; // Permite outras datas
            }
        }
    });

    // Quando o usuário selecionar um horário
    document.getElementById('horario').addEventListener('change', function () {
        mostrarResumo();
    });
});


        function mostrarResumo() {
            var barbeiroSelecionado = document.getElementById('barbeiro').value;
            var diaSelecionado = document.getElementById('datepicker').value;
            var horarioSelecionado = document.getElementById('horario').value;

            // Atualizar o texto do resumo com as seleções feitas pelo usuário
            var resumoTexto = document.getElementById('resumoTexto');
            resumoTexto.innerText = `Barbeiro: ${barbeiroSelecionado}\nDia: ${diaSelecionado}\nHorário: ${horarioSelecionado}`;

            // Exibir o resumo e o botão de submit
            var resumo = document.getElementById('resumo');
            var submitButton = document.querySelector('button[type="submit"]');
            resumo.style.display = 'block';
            submitButton.style.display = 'block';
        }
    </script>
</body>
</html>