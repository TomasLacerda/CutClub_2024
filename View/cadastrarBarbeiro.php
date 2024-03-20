<!DOCTYPE html>
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
        .table-bg{
            background-color:rgba(192,192,192,0.3);
            border-radius: 15px 15px 0 0;
        }

            /* Estilos para o checkbox */
            .custom-checkbox {
                transform: scale(1.5); /* Ajusta o tamanho do checkbox */
                margin-top: 10px; /* Ajusta o espaçamento superior */
            }
    </style>
</head>

<body>
    <?php
    include_once "include/menu.php";
    include_once "../Model/ContatoDAO.php";        
    
    $ContatoDAO = new ContatoDAO();
    $stFiltro = " WHERE barbeiro = 0";
    $resultado = $ContatoDAO->recuperaTodos($stFiltro);
    ?>

    <div class="container" style="padding: 2.5rem; border:6px double white;">
        <div class="row">
            <div class="col"></div>
            <div class="col-lg-8">
                <h3>Barbeiros</h3>
                <p>Para incluir um novo funcionário, selecione o cadastro dele na tabela abaixo e salve. Se o funcionário não estiver cadastrado, <a href="cadastro.php">clique aqui</a> para criar um novo cadastro.</p>
                <form>
                <div class="m-5"></div>
                    <table class="table text-white table-bg">
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Sobrenome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Telefone</th>
                                <th scope="col">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($dados = mysqli_fetch_assoc($resultado))
                                {
                                    echo "<tr>";
                                    echo "<td>".$dados['id']."</td>";
                                    echo "<td>".$dados['nome']."</td>";
                                    echo "<td>".$dados['sobrenome']."</td>";
                                    echo "<td>".$dados['email']."</td>";
                                    echo "<td>".$dados['telefone']."</td>";
                                    echo "<td><input class='custom-checkbox' type='checkbox' name='boBarbeiro[]' id='boBarbeiro_".$dados['id']."' value='".$dados['id']."'></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col">
                        <button id="cadastrar" onclick="SalvarEditarContato()" type="button" class="btn btn-primary">Incluir Barbeiro</Button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary" onclick="history.go(-1); return false;">Voltar</button>
                        </div>
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
</body>
</html>
