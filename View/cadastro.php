<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Agenda PHP</title>
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap-multiselect.css">
</head>
<body>
    <?php
    //include_once "include/menu.php";
    ?>
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand">
                <span>CutClub</span>
            </a>
        </div> <!-- fecha /container -->
    </nav> <!-- fecha /barra de navegação -->

    <div class="container" style="padding:2.5rem 2.5rem 2.5rem 2.5rem; border:10px double white;">
        <div class="row">
            <div class="col"></div>

            <div class="col-lg-4">
                <h3>Cadastro de Usuário</h3>

                <form>
                    <div class="form-group mb-3">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" placeholder="Informe seu Nome">
                    </div>

                    <div class="form-group mb-3">
                        <label for="sobrenome">Sobrenome</label>
                        <input type="text" class="form-control" id="sobrenome" placeholder="Informe seu Sobrenome">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" placeholder="Informe seu E-mail">
                    </div>

                    <div class="form-group mb-3">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" id="telefone" placeholder="(00)00000-0000" oninput="formatarTelefone(this)">
                    </div>

                    <div class="form-group mb-3">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control" id="senha" placeholder="Informe uma Senha">
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" id="cadastrar" onclick="cadastrarContato()">Cadastrar</button>
                        <button type="button" class="btn btn-primary" id="voltar" onclick="voltarLogin()">Voltar</button>
                    </div>
                </form>

                <div id="status"></div>
            </div>

            <div class="col"></div>
        </div>
    </div> <!-- fecha /container -->

    <!-- jQuery (online) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>
    <script>
        function formatarTelefone(input) {
            // Remove todos os caracteres não numéricos do valor do campo
            let cleaned = input.value.replace(/\D/g, '');

            // Adiciona parênteses nos dois primeiros caracteres
            if (cleaned.length >= 2) {
                cleaned = "(" + cleaned.substring(0, 2) + ")" + cleaned.substring(2);
            }

            // Insere hífen após o nono caractere, se houver mais de nove caracteres
            if (cleaned.length > 9) {
                cleaned = cleaned.substring(0, 9) + "-" + cleaned.substring(9);
            }

            // Limita o comprimento máximo do valor formatado
            input.value = cleaned.substring(0, 14);
        }
    </script>
</body>
</html>