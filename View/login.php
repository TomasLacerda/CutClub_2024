<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agenda PHP</title>
    <!-- CSS do Bootstrap e CSS customizado -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>


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

<body>
    <?php
    //include_once "include/menu.php";
    ?>

    <div class="container" style="padding:2.5rem 2.5rem 2.5rem 2.5rem; border:10px double white;">
        <div class="row">
            <div class="col"></div>
            <div class="col-lg-4">
            <h3>Barbearia 313</h3>
                <h2>Efetuar Login</h2>
                <form>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" placeholder="Informe seu e-mail" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control" id="senha" placeholder="Informe sua senha" required>
                    </div>
                    <button id="login" onclick="efetuarLogin()" type="button" class="btn btn-primary">Entrar</Button>
                </form>
                <p>
                    <div><label for="senha">Ou cadastre-se</label></div>
                    <div><button id="login" onclick="direcionaCadastrar()" type="button" class="btn btn-primary">Cadastrar</Button></div>
                </p>


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
</body>
</html>