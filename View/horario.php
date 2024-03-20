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
    </style>
</head>

<body>
    <?php
    include_once "include/menu.php";
    include_once "../Model/DtIndisponivelDAO.php";        
    
    $DtIndisponivelDAO = new DtIndisponivelDAO();
    $stFiltro = "";
    $resultado = $DtIndisponivelDAO->recuperaRelacionamento($stFiltro);
    ?>

    <div class="container" style="padding:2.5rem 2.5rem 2.5rem 2.5rem; border:10px double white;">
        <div class="row">
            <div class="col"></div>
            <div class="col-lg-8">
                <h3>Horários</h3>
                <small class="form-text text-muted">Edição de horário não é permitida, cadastre um novo caso queira alguma alteração.</small>
                <form>
                <div class="m-5"></div>
                    <table class="table text-white table-bg">
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">dia semana fechado</th>
                                <th scope="col">hora inicial</th>
                                <th scope="col">hora final</th>
                                <th scope="col">data inicial</th>
                                <th scope="col">data final</th>
                                <th scope="col">Barbeiro</th>
                                <th scope="col">descrição</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($dados = mysqli_fetch_assoc($resultado))
                                {
                                    echo "<tr>";
                                    echo "<td>".$dados['id']."</td>";
                                    echo "<td>".$dados['dia_semana']."</td>";
                                    echo "<td>".$dados['hora_inicio']."</td>";
                                    echo "<td>".$dados['hora_fim']."</td>";
                                    echo "<td>".$dados['data_inicio']."</td>";
                                    echo "<td>".$dados['data_fim_regra']."</td>";
                                    echo "<td>".$dados['barbeiro']."</td>";
                                    echo "<td>".$dados['descricao']."</td>";
                                    echo "<td>
                                        <a class='btn btn-sm btn-danger' data-tooltip='Excluir' onclick='confirmarExclusao(".$dados['id'].")'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                                <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                                            </svg>
                                        </a>
                                    </td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col">
                            <button id="cadastrar" onclick="window.location.href='cadastroHorario.php'" type="button" class="btn btn-primary">Cadastrar Horário</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>
    <script>
        function confirmarExclusao(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja excluir este cadastro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuário confirmar, redirecione para a página de exclusão
                    window.location.href = 'exclusaoHorario.php?id=' + id;
                }
            });
        }
    </script>
</body>
</html>
