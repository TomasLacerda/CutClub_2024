<?php
session_start();
?>

<nav class="navbar fixed-top navbar-expand-lg navbar-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a href="home.php" class="navbar-brand">
            <span>CutClub</span>
        </a>

        <div class="collapse navbar-collapse" id="menu">
            <div class="navbar-header">
                <ul class="navbar-nav">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="barbeiro.php" id="Barbeiro">Barbeiro</a></li>
                    <li><a href="horario.php" id="Barbeiro">Horários</a></li>

                    <li>
                        <?php
                        if (isset($_SESSION['id'])) {
                            ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?= $_SESSION['nome']; ?> <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#" id="logout" onclick="efetuarLogout()">Logout</a>
                                    </li>
                                    <li>
                                        <a href='editarContato.php?id=<?= $_SESSION['id']; ?>&location=menu' id="editar">Editar</a>
                                    </li>
                                    <li>
                                        <a href="#" id="excluir" onclick="confirmarExclusao('<?= $_SESSION['id']; ?>')">Excluir</a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        } else {
                            ?>
                            <a href="login.php">Login</a>
                            <?php
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- modal -->
<div id="modalLoading" class="modal-loading animated bounceIn"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

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
                window.location.href = 'exclusaoContato.php?id=' + id + '&location=menu';

                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 3000);
            }
        });
    }
</script>