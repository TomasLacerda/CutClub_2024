function cadastrarContato() {
    dados = {
        'nome': $('#nome').val(),
        'email': $('#email').val(),
        'senha': $('#senha').val(),
        'telefone': $('#telefone').val(),
        'sobrenome': $('#sobrenome').val(),
        'cadastrar': $('#cadastrar').val()
    }

    // Parametros Ajax
    parametros = {
        'urlBackEnd': '../Controller/contatoController.php',
        'location': 'login.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function cadastrarBarbeiro() {
    dados = {
        'nome': $('#nome').val(),
        'email': $('#email').val(),
        'senha': $('#senha').val(),
        'telefone': $('#telefone').val(),
        'sobrenome': $('#sobrenome').val(),
        'cadastrarBarbeiro': $('#cadastrar').val()
    }

    // Parametros Ajax
    parametros = {
        'urlBackEnd': '../Controller/contatoController.php',
        'location': 'barbeiro.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function SalvarEditarContato() {
    // ObtÃÂ©m os valores dos checkboxes selecionados
    var boBarbeiro = [];
    $('input[name="boBarbeiro[]"]:checked').each(function() {
        boBarbeiro.push($(this).val());
    });

    // Define os dados a serem enviados
    dados = {
        'nome': $('#nome').val(),
        'email': $('#email').val(),
        'senha': $('#senha').val(),
        'telefone': $('#telefone').val(),
        'sobrenome': $('#sobrenome').val(),
        'boBarbeiro': boBarbeiro, // Usa o array com os valores dos checkboxes selecionados
        'editar': $('#editar').val()
    }

    // ParÃÂ¢metros Ajax
    parametros = {
        'urlBackEnd': '../Controller/contatoController.php',
        'location': 'home.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function cadastrarDatas() {
    // Obtenha o valor do campo de rádio 'dia_trabalho'
    var diaDeTrabalho = $('input[name="dia_trabalho"]:checked').val();

    // Crie o objeto 'dados' e inclua todas as outras propriedades
    var dados = {
        'dtFinal': $('#dt_final').val(),
        'hrFinal': $('#hora_fim').val(),
        'semana': $('#dia_semana').val(),
        'dtInicio': $('#dt_inicio').val(),
        'idBarbeiro': $('#barbeiro').val(),
        'descricao': $('#descricao').val(),
        'hrInicio': $('#hora_inicio').val(),
        'hrFinalInt': $('#hora_fim_intervalo').val(),
        'hrInicioInt': $('#hora_inicio_intervalo').val(),
        'diaDeTrabalho': diaDeTrabalho, // Adicione aqui o valor obtido do campo de rádio
        'cadastrar': $('#cadastrar').val()
    };

    // Parâmetros Ajax
    var parametros = {
        'urlBackEnd': '../Controller/atendimentoController.php',
        'location': 'horario.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    };

    // Chame a função para carregar o Ajax com os dados e parâmetros especificados
    carregarAjax(dados, parametros);
}

function agendarServico(idBarbeiroSelecionado, dataSelecionada, horarioSelecionado, idServicoSelecionado) {
    dados = {
        'idBarbeiro': idBarbeiroSelecionado,
        'dtServico': dataSelecionada,
        'horario': horarioSelecionado,
        'idServico': idServicoSelecionado,
        'agendar': $('#agendar').val()
    }

    // Parametros Ajax
    parametros = {
        'urlBackEnd': '../Controller/atendimentoController.php',
        'location': 'home.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function cadastrarServico() {
    // Remova o 'R$ ' do valor antes de enviar
    var valor = $('#valor').val().replace('R$ ', '');

    // Construa um objeto FormData
    var formData = new FormData();
    formData.append('nome', $('#nome').val());
    formData.append('duracao', $('#duracao').val());
    formData.append('valor', valor);
    formData.append('imagem', $('#imagem')[0].files[0]);
    formData.append('descricao', $('#descricao').val());
    formData.append('cadastrarServico', $('#cadastrar').val());

    var parametros = {
        'urlBackEnd': '../Controller/atendimentoController.php',
        'location': 'servico.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    };

    // Realize a solicitação AJAX
    $.ajax({
        url: parametros.urlBackEnd,
        type: 'POST',
        data: formData,
        contentType: false, // Necessário para enviar arquivos
        processData: false, // Necessário para enviar arquivos
        success: function(response) {
            // Verifica se o retorno indica sucesso
            if (response.codigo === 1) {
                // Exibe mensagem de sucesso
                $('#status').text(response.mensagem).css('color', parametros.corSucesso);

                // Redireciona para a página de serviços após um breve intervalo
                setTimeout(function() {
                    window.location.href = parametros.location;
                }, 2000);
            } else {
                // Caso contrário, exibe mensagem de erro
                $('#status').text(response.mensagem).css('color', parametros.corErro);
            }
        },
        error: function(xhr, status, error) {
            // Manipule o erro aqui
            console.error(xhr.responseText);
        }
    });
}

function direcionaCadastrar() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "cadastro.php"
    }, 2000);
}

function voltarLogin() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "login.php"
    }, 2000);
}

function voltarBarbeiro() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "cadastrarBarbeiro.php"
    }, 2000);
}

function voltar() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "../"
    }, 2000);
}

function efetuarLogin() {
    dados = {
        'email': $('#email').val(),
        'senha': $('#senha').val(),
        'login': $('#login').val()
    }

    // Parametros Ajax
    parametros = {
        'urlBackEnd': '../Controller/contatoController.php',
        'location': 'home.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function efetuarLogout() {
    dados = {
        'logout': $('#logout').val()
    }

    carregarModalLoading();
    
    $.ajax({
        url: "../Controller/contatoController.php",
        type: 'POST',
        data: dados,
        success: function() {
            // Redirecionamento
            setTimeout(() => {
                window.location.href = "login.php"
            }, 2000);
        }
    });
}

function carregarAjax(dados, parametros) {
    carregarModalLoading();

    $.ajax({
        url: parametros['urlBackEnd'],
        type: 'POST',
        data: dados,
        success: function(data) {
            console.log(typeof data); // Verifica o tipo de dados
            console.log(data);
            setTimeout(() => {
                esconderModalLoading();

                $('#status').text(data.mensagem);

                //Verifica retorno
                if (data.codigo == 1) {
                    $('#status').css({
                        "color": parametros['corSucesso']
                    });
                
                    // Redirecionamento
                    console.log("Redirecionando para:", parametros['location']); // Adicione este console.log
                    setTimeout(() => {
                        window.location.href = parametros['location'];
                    }, 2000);
                }
            }, 1000);
        }
    });
}

function carregarModalLoading() {
    $('#modalLoading').css({
        "display": "block"
    });
}

function esconderModalLoading() {
    $('#modalLoading').css({
        "display": "none"
    });
}