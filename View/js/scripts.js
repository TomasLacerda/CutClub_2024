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

function SalvarEditarContato() {
    // Obtém os valores dos checkboxes selecionados
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

    // Parâmetros Ajax
    parametros = {
        'urlBackEnd': '../Controller/contatoController.php',
        'location': 'home.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function cadastrarDatas() {
    dados = {
        'dtFinal': $('#dt_final').val(),
        'hrFinal': $('#hora_fim').val(),
        'semana': $('#dia_semana').val(),
        'dtInicio': $('#dt_inicio').val(),
        'idBarbeiro': $('#barbeiro').val(),
        'hrInicio': $('#hora_inicio').val(),
        'hrFinalInt': $('#hora_fim_intervalo').val(),
        'hrInicioInt': $('#hora_inicio_intervalo').val(),
        'cadastrar': $('#cadastrar').val()
    }

    // Parametros Ajax
    parametros = {
        'urlBackEnd': '../Controller/atendimentoController.php',
        'location': 'cadastroHorario.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
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
            setTimeout(() => {
                esconderModalLoading();

                $('#status').text(data.mensagem);

                //Verifica retorno
                if (data.codigo == 1) {
                    $('#status').css({
                        "color": parametros['corSucesso']
                    });

                    // Redirecionamento
                    setTimeout(() => {
                        window.location.href = parametros['location']
                    }, 2000);
                } else {
                    $(data.campo).focus();
                    $('#status').css({
                        "color": parametros['corErro']
                    });
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