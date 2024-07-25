<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f8f9fa; /* Cor de fundo */
        }
        label {
            font-weight: bold; /* Negrito para os rótulos */
        }
        select, input[type="text"] {
            width: 100%; /* Largura total para os campos de seleção e texto */
            padding: 10px; /* Espaçamento interno */
            margin-bottom: 20px; /* Margem inferior */
            border: 1px solid #ced4da; /* Borda com cor */
            border-radius: 5px; /* Bordas arredondadas */
            box-sizing: border-box; /* Caixa de modelagem inclui borda e preenchimento */
        }
        #agendar {
            width: 100%; /* Largura total para o botão */
            padding: 15px; /* Espaçamento interno */
            font-size: 18px; /* Tamanho da fonte */
            border-radius: 5px; /* Bordas arredondadas */
            color: white;
        }
    </style>
<body>
    <?php
        include_once "include/menu.php";
        include_once "../Model/ContatoDAO.php";
        include_once "../Model/dtIndisponivelDAO.php";

        $ContatoDAO = new ContatoDAO();
        $stFiltro = " WHERE barbeiro = 1";
        $rsBarbeiros = $ContatoDAO->recuperaTodos($stFiltro);


    ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
                <fieldset class="box">
                    <h1 id="subtitle">Agendar Horário</h1>
                    <div class="m-5"></div>
                    <label for="barbeiro">Selecione o barbeiro*:</label>
                    <select id="barbeiro" name="barbeiro">
                        <option selected disabled>Selecione</option>
                            <?php
                            if ($rsBarbeiros->num_rows > 0) {
                                while ($coluna = $rsBarbeiros->fetch_assoc()) {
                                    echo "<option value='" . $coluna['id'] . "'>" . $coluna['nome'] . ' ' . $coluna['sobrenome'] ."</option>";
                                }
                            } else {
                                echo "<option value=''>Nenhum barbeiro encontrado</option>";
                            }
                            ?>
                    </select>
                    <div class="form-group" id="opcoesServico" style="display: none;">
                        <label for="servico">Selecione o serviço*:</label>
                    </div>
                    <div class="form-group" id="datepicker" style="display: none;">
                        <label for="dia">Selecione o dia*:</label>
                        <input type="text" id="datepicker" name="dia" disabled>
                    </div>
                    <div class="form-group" id="opcoesHorario" style="display: none;">
                        <label for="horario">Selecione o horário*:</label>
                        <select id="horario" name="horario" disabled>
                            <option selected disabled>Selecione</option>
                            <?php
                            ?>
                        </select>
                    </div>
                    <fieldset class="desfocado" id="resumoFieldset" style="display: none;">
                        <div id="resumo" style="display: none;">
                            <h3>Resumo:</h3>
                            <p id="resumoTexto"></p>
                        </div>
                    </fieldset>
                </fieldset>
            </div>
        </div>
    </div>

    <!-- JavaScript customizado -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    var barbeiroSelect = document.getElementById('barbeiro');
    var selectServico = document.getElementById('servico');
    var selectHorario = document.getElementById('horario');
    var datepickerInput = document.getElementById('datepicker');
    var datasDesabilitadas = []; // Inicialize como um array vazio
    var diasSemanaDesabilitados = []; // Inicialize como um array vazio

    function desabilitarDatas(date) {
        var formattedDate = $.datepicker.formatDate('dd/mm/yy', date);
        // Verifica se a data formatada está presente no array de datas desabilitadas
        for (var i = 0; i < datasDesabilitadas.length; i++) {
            var disabledDate = datasDesabilitadas[i];
            var parts = disabledDate.split('/');
            var disabledFormattedDate = parts[0] + '/' + parts[1] + '/' + (parts[2].length === 2 ? '20' + parts[2] : parts[2]); // Ajusta o formato da data
            if (formattedDate === disabledFormattedDate) {
                return [false, '']; // Desabilita a data
            }
        }

        var dayOfWeek = date.getDay();
        // Converte o objeto em um array de dias da semana
        var diasDesabilitados = Object.keys(diasSemanaDesabilitados).map(Number);
        // Verifica se o dia da semana está presente no array de dias da semana desabilitados
        if (diasDesabilitados.includes(dayOfWeek)) {
            return [false]; // Desabilita o dia da semana
        }

        return [true, '']; // Permite outras datas
    }

    barbeiroSelect.addEventListener('change', function () {
        var idBarbeiro = barbeiroSelect.value;
        $.ajax({
            url: '../Controller/atendimentoController.php',
            type: 'GET',
            data: { id_barbeiro: idBarbeiro },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    datasDesabilitadas = data.datas_desabilitadas;
                    diasSemanaDesabilitados = data.semana_desabilitadas;

                    // Obtém a data atual
                    var dataAtual = new Date();

                    // Define a data mínima como a data atual
                    var dataMinima = new Date(dataAtual);

                    // Define a data máxima como a data atual acrescida de 3 meses
                    var dataMaxima = new Date(dataAtual);
                    dataMaxima.setMonth(dataMaxima.getMonth() + 3);

                    Swal.fire({
                        title: 'Selecione o Serviço',
                        html: `
                            <div id="servico" style="display: flex; align-items: center; justify-content: space-between;">
                                <div>
                                    <button id="prevService" class="btn btn-link" style="visibility: hidden;"><i class="fas fa-chevron-left"></i></button>
                                </div>
                                <div id="serviceDetails">
                                    <img height="50" src="${data.opcoes_servico_imagem[0]}" style="width: 200px; height: 200px; border: 2px solid black; border-radius: 5px;">
                                    <h5> ${data.opcoes_servico_nome[0]}</h5>
                                    <p><div style="margin-top: 10px;">
                                        <label for="descricao">Descrição:</label>
                                        <textarea id="descricao" rows="4" style="width: 100%;" readonly>${data.opcoes_servico_descricao[0]}</textarea>
                                    </p></div>
                                    <p><b>Valor:</b> R$ ${data.opcoes_servico_valor[0]}</p>
                                    <p><b>Duração:</b> ${data.opcoes_servico_duracao[0].replace(':', 'h')}</p>
                                </div>
                                <div>
                                    <button id="nextService" class="btn btn-link"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                        `,
                        showCloseButton: true,
                        showCancelButton: false,
                        allowOutsideClick: true,
                        didOpen: () => {
                            const serviceSelector = document.getElementById('servico');
                            const serviceDetails = document.getElementById('serviceDetails');
                            const prevServiceButton = document.getElementById('prevService');
                            const nextServiceButton = document.getElementById('nextService');

                            let currentIndex = 0;

                            // Atualiza a visibilidade dos botões de navegação
                            function updateNavigationButtons() {
                                prevServiceButton.style.visibility = currentIndex === 0 ? 'hidden' : 'visible';
                                nextServiceButton.style.visibility = currentIndex === data.opcoes_servico_nome.length - 1 ? 'hidden' : 'visible';
                            }

                            // Mostra o serviço anterior
                            prevServiceButton.addEventListener('click', () => {
                                currentIndex--;
                                updateNavigationButtons();
                                serviceDetails.innerHTML = `
                                    <img height="50" src="${data.opcoes_servico_imagem[currentIndex]}" style="width: 200px; height: 200px; border: 2px solid black; border-radius: 5px;">
                                    <h5>${data.opcoes_servico_nome[currentIndex]}</h5>
                                    <p><div style="margin-top: 10px;">
                                        <label for="descricao">Descrição:</label>
                                        <textarea id="descricao" rows="4" style="width: 100%;" readonly>${data.opcoes_servico_descricao[currentIndex]}</textarea>
                                    </p></div>
                                    <p><b>Valor:</b> R$ ${data.opcoes_servico_valor[currentIndex]}</p>
                                    <p><b>Duração:</b> ${data.opcoes_servico_duracao[currentIndex].replace(':', 'h')}</p>
                                `;
                            });

                            // Mostra o próximo serviço
                            nextServiceButton.addEventListener('click', () => {
                                currentIndex++;
                                updateNavigationButtons();
                                serviceDetails.innerHTML = `
                                    <img height="50" src="${data.opcoes_servico_imagem[currentIndex]}" style="width: 200px; height: 200px; border: 2px solid black; border-radius: 5px;">
                                    <h5>${data.opcoes_servico_nome[currentIndex]}</h5>
                                    <p><div style="margin-top: 10px;">
                                        <label for="descricao">Descrição:</label>
                                        <textarea id="descricao" rows="4" style="width: 100%;" readonly>${data.opcoes_servico_descricao[currentIndex]}</textarea>
                                    </p></div>
                                    <p><b>Valor:</b> R$ ${data.opcoes_servico_valor[currentIndex]}</p>
                                    <p><b>Duração:</b> ${data.opcoes_servico_duracao[currentIndex].replace(':', 'h')}</p>
                                `;
                            });

                            // Atualiza a visibilidade dos botões de navegação quando o modal é aberto
                            updateNavigationButtons();
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var selectServiceDetails = document.getElementById('serviceDetails');
                            var serviceName = selectServiceDetails.querySelector('h5').textContent.trim();


                            Swal.fire({
                                title: 'Quase lá...',
                                html: `
                                <h5>Data:</h5>
                                    <input type="text" id="swal-datepicker" style="margin-bottom: 10px;width: 75%;">
                                    <p></p>
                                    <h5>Hora:</h5>
                                    <select id="swal-select-horario" style="margin-bottom: 10px;width: 75%;" ></select>
                                `,
                                showCloseButton: true,
                                showCancelButton: false,
                                allowOutsideClick: true,
                                didOpen: () => {
                                    $("#swal-datepicker").datepicker({
                                        dateFormat: 'dd/mm/yy',
                                        minDate: dataMinima,
                                        maxDate: dataMaxima,
                                        beforeShowDay: function (date) {
                                            return desabilitarDatas(date);
                                        },
                                    });

                                    $('#swal-select-horario').select2({
                                        dropdownParent: $('.swal2-modal') // Importante: Monta o dropdown dentro do modal do SweetAlert
                                    });

                                    // Atualizar os horários disponíveis quando a data for selecionada
                                    $("#swal-datepicker").on('change', function() {
                                        var selectedDate = $(this).val();
                                        // Enviar a data selecionada para o controlador PHP
                                        $.ajax({
                                            url: '../Controller/atendimentoController.php',
                                            type: 'GET',
                                            data: { id_barbeiro: barbeiroSelect.value, selected_date: selectedDate, idServico: serviceName },
                                            success: function(response) {
                                                // Tratar a resposta do controlador PHP...
                                                var data = JSON.parse(response);
                                                var horariosDisponiveis = data.horas_do_dia;

                                                // Atualizar as opções do select de horários
                                                var selectHorario = document.getElementById('swal-select-horario');
                                                selectHorario.innerHTML = ''; // Limpar as opções existentes

                                                // Iterar sobre as chaves do objeto horariosDisponiveis
                                                for (var key in horariosDisponiveis) {
                                                    if (horariosDisponiveis.hasOwnProperty(key)) {
                                                        var option = document.createElement('option');
                                                        option.value = key; // Defina o valor do horário como a chave (ID do horário)
                                                        option.text = horariosDisponiveis[key]; // Defina o texto do horário como o valor correspondente
                                                        selectHorario.appendChild(option); // Adicione a opção ao select
                                                    }
                                                }
                                            },
                                            error: function(xhr, status, error) {
                                                // Tratamento de erro...
                                            }
                                        });
                                    });
                                },
                                willClose: () => {
                                    // document.getElementById('barbeiro').selectedIndex = 0;
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    var selectHorario = document.getElementById('swal-select-horario');
                                    var horarioSelecionadoId = selectHorario.value; // ID do horário selecionado
                                    var horarioSelecionadoText = selectHorario.options[selectHorario.selectedIndex].text; // Texto do horário selecionado
                                    var dataSelecionada = document.getElementById('swal-datepicker').value; // Obter a data selecionada
                                                                
                                    // Passar a data, o horário e o serviço selecionados para a função mostrarResumo
                                    mostrarResumo(dataSelecionada, horarioSelecionadoText, serviceName);
                                }
                            });
                        }
                    });
                } catch (error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Ocorreu um erro ao processar os dados!',
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Ocorreu um erro ao carregar os dados!',
                });
            }
        });
    });
});

function mostrarResumo(dataSelecionada, horarioSelecionado, serviceName) {
    var selectBarbeiro = document.getElementById('barbeiro');
    var barbeiroSelecionado = selectBarbeiro.options[selectBarbeiro.selectedIndex].text;

    var resumoTexto = document.getElementById('resumoTexto');

    resumoTexto.innerText = `Barbeiro: Exemplo Barbeiro\nServiço: ${serviceName}\nDia: ${dataSelecionada}\nHorário: ${horarioSelecionado}\nValor: R$ 35,00`;

    var resumo = document.getElementById('resumo');
    var resumoFieldset = document.getElementById('resumoFieldset');
    
    // Remova o botão "Agendar" se já existir
    var existingAgendarButton = document.getElementById('agendar');
    if (existingAgendarButton) {
        existingAgendarButton.remove();
    }

    // Criar um novo botão "Agendar"
    var agendarButton = document.createElement('button');
    agendarButton.setAttribute('id', 'agendar');
    agendarButton.setAttribute('class', 'btn btn-primary');
    agendarButton.setAttribute('style', 'width: 100%');
    agendarButton.innerText = 'Agendar';

    // Adicionar evento de clique ao botão "Agendar"
    agendarButton.addEventListener('click', function() {
        var barbeiroSelect = document.getElementById('barbeiro');
        var idBarbeiro = barbeiroSelect.value;

        // Chamar a função agendarServico com os parâmetros apropriados
        agendarServico(idBarbeiro, dataSelecionada, horarioSelecionado, serviceName);
    });

    // Adicionar o botão "Agendar" ao elemento com id "resumo"
    resumo.appendChild(agendarButton);

    // Mostrar o fieldset de resumo
    resumo.style.display = 'block';
    resumoFieldset.style.display = 'block';
}




    </script>
</body>
</html>