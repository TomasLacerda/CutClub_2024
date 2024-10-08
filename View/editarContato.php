<?php
    if (!empty($_GET['id'])) {
        include_once "include/menu.php";
        include_once "../Model/ContatoDAO.php";        
        
        $ContatoDAO = new ContatoDAO();
        $id = $_GET['id'];

        $stFiltro = " WHERE contato.id = ".$id;
        $resultado = $ContatoDAO->recuperaTodos($stFiltro);

        if ($resultado->num_rows > 0) {
            while($user_data = mysqli_fetch_assoc($resultado))
            {
                $nome = $user_data['nome'];
                $senha = $user_data['senha'];
                $email = $user_data['email'];
                $telefone = $user_data['telefone'];
                $sobrenome = $user_data['sobrenome'];
            }
        } else {
            header('Location: login.php');
        }
    }
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

    <style>
        /* Estilize o bot�o como desejar */
        #olho {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    include_once "include/menu.php";
    ?>

    <div class="container" style="padding: 2.5% 2.5% 2.5% 2.5%; border: 10px double white;">
        <div class="row justify-content-center align-items-center" >
            <div class="col-lg-4">
                <h3>Editar Cadastro - ID '<?php echo $id?>'</h3>
                <form>
                    <div class="form-group mb-3">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $nome?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="sobrenome">Sobrenome</label>
                        <input type="text" id="sobrenome" name="sobrenome" class="form-control" value="<?php echo $sobrenome?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo $email?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" class="form-control" value="<?php echo $telefone?>" oninput="formatarTelefone(this)">
                    </div>

                    <div class="form-group mb-3">
                        <label for="senha">Senha</label>
                        <div class="input-group">
                            <input type="password" id="senha" name="senha" class="form-control">
                            <span class="input-group-text">
                                <img id="olho" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABDUlEQVQ4jd2SvW3DMBBGbwQVKlyo4BGC4FKFS4+TATKCNxAggkeoSpHSRQbwAB7AA7hQoUKFLH6E2qQQHfgHdpo0yQHX8T3exyPR/ytlQ8kOhgV7FvSx9+xglA3lM3DBgh0LPn/onbJhcQ0bv2SHlgVgQa/suFHVkCg7bm5gzB2OyvjlDFdDcoa19etZMN8Qp7oUDPEM2KFV1ZAQO2zPMBERO7Ra4JQNpRa4K4FDS0R0IdneCbQLb4/zh/c7QdH4NL40tPXrovFpjHQr6PJ6yr5hQV80PiUiIm1OKxZ0LICS8TWvpyyOf2DBQQtcXk8Zi3+JcKfNafVsjZ0WfGgJlZZQxZjdwzX+ykf6u/UF0Fwo5Apfcq8AAAAASUVORK5CYII=" alt="Mostrar Senha">
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button id="editar" onclick="SalvarEditarContato()" type="button" class="btn btn-primary">Salvar</Button>
                        </div>
                        <div class="col">
                            <div class="d-flex justify-content-end align-items-end">
                                <button type="button" class="btn btn-primary" onclick="history.go(-1); return false;">Voltar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="status"></div>
            </div>
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
            // Remove todos os caracteres nao numericos do valor do campo
            let cleaned = input.value.replace(/\D/g, '');

            // Adiciona parenteses nos dois primeiros caracteres
            if (cleaned.length >= 2) {
                cleaned = "(" + cleaned.substring(0, 2) + ")" + cleaned.substring(2);
            }

            // Insere hifen apos o nono caractere, se houver mais de nove caracteres
            if (cleaned.length > 9) {
                cleaned = cleaned.substring(0, 9) + "-" + cleaned.substring(9);
            }

            // Limita o comprimento m�ximo do valor formatado
            input.value = cleaned.substring(0, 14);
        }
    </script>
    <script>
        const senha = document.getElementById('senha');
        const olho = document.getElementById('olho');

        olho.addEventListener('mousedown', () => {
            senha.type = 'text';
        });

        olho.addEventListener('mouseup', () => {
            senha.type = 'password';
        });

        // Para evitar o problema de arrastar a imagem e a senha continuar exposta
        olho.addEventListener('mouseout', () => {
            senha.type = 'password';
        });
    </script>
</body>
</html>