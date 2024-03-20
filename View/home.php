<!DOCTYPE html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Agenda PHP</title>
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
        }

        .box {
            background: linear-gradient(to top right, #191970, #00008B);
            margin: 20px;
            border: 2px solid #FFFFF0;
            border-radius: 20px;
            padding: 60px;
        }

        .legend-style {
            border-radius: 10px; /* Define o raio de borda desejado */
            margin-top: -1px; /* Sobe a legenda para que se sobreponha à borda superior do .box */
            color: #000000; /* Cor do texto */
            font-size: 1.4em; /* Tamanho da fonte */
            font-weight: bold; /* Deixa o texto em negrito */
            font-family: Arial, sans-serif; /* Especifica a fonte */
            padding: 5px 10px; /* Adiciona um preenchimento interno à legenda */
            background-color: #FFFFF0; /* Cor de fundo da legenda */
        }

        .quadro {
            display: block;
            text-decoration: none;
            border: 1px solid #ccc;
            border-radius: 40px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 1);
        }

        /* CSS para ajustar o posicionamento do pop-up */
        .custom-swal-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100vw; /* Define a largura do container como 100% da largura da viewport */
            height: 100vh; /* Define a altura do container como 100% da altura da viewport */
        }
    </style>
</head>

<body>
    <?php
    include_once "include/menu.php";
    //include_once "../Model/DtIndisponivelDAO.php";        
    //
    //$DtIndisponivelDAO = new DtIndisponivelDAO();
    //$stFiltro = "";
    //$resultado = $DtIndisponivelDAO->recuperaRelacionamento($stFiltro);
    ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div id="status">
                <fieldset class="box">
                <legend class="legend-style">Seja bem-vindo</legend>
                    <a href="#" onclick="openPopup('agendamento.php')" class="quadro bg-light rounded p-4 mb-4 text-dark">
                        <i class="fas fa-calendar-alt mr-2"></i> Agendamento de Horários:
                        <span class="text-secondary"> agende seu horário por aqui.</span>
                    </a>
                    <a href="link_para_fidelidade" class="quadro bg-light rounded p-4 mb-4 text-dark">
                        <i class="fas fa-star mr-2"></i> Plano de Fidelidade
                        <span class="text-secondary">: consulte seu plano de fidelidade aqui.</span>
                    </a>
                </fieldset>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery (online) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>
    <script>
        function openPopup(url) {
            Swal.fire({
                html: '<iframe src="' + url + '" width="100%" height="100%" frameborder="0"></iframe>',
                width: '80%', // Defina a largura do pop-up como 80% da largura da tela
                height: '80%', // Defina a altura do pop-up como 80% da altura da tela
                backdrop: true,
                allowOutsideClick: false,
                customClass: {
                    container: 'custom-swal-container',
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title'
                },
                showCloseButton: true, // Mostra o botão de fechar
                showConfirmButton: false // Esconde o botão padrão de confirmação
            });
            
            // Ajustar a largura e a altura do pop-up com base no tamanho da tela
            var width = window.innerWidth * 0.8; // 80% da largura da tela
            var height = window.innerHeight * 0.8; // 80% da altura da tela
            
            // Definir a largura e a altura do pop-up
            Swal.getPopup().style.width = width + 'px';
            Swal.getPopup().style.height = height + 'px';
        }
</script>
</body>
</html>
