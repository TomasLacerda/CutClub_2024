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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <style>
        .box {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .textarea {
            width: 100%; /* Largura total */
            padding: 10px; /* EspaÃ§amento interno */
            margin-bottom: 20px; /* Margem inferior */
            border: 1px solid #ced4da; /* Borda com cor */
            border-radius: 10px; /* Bordas arredondadas */
            box-sizing: border-box; /* Caixa de modelagem inclui borda e preenchimento */
            resize: none; /* Desabilitar redimensionamento automÃ¡tico */
            min-height: 100px; /* Altura mÃ­nima */
            max-height: 200px; /* Altura mÃ¡xima */
            overflow-y: auto; /* Adicionar barras de rolagem vertical, se necessÃ¡rio */
        }

        .help-text {
            font-size: 0.8rem; /* Tamanho da fonte do texto de ajuda */
            color: black; /* Cor do texto de ajuda */
            margin-top: 5px; /* Espaçamento superior */
        }

        label {
            font-weight: bold; /* Negrito para os rótulos */
            width: 100%; /* Largura total para os campos de seleção e texto */
        }
        select, input[type="text"], input[type="number"] {
            width: 50%; /* Largura total para os campos de seleção e texto */
            padding: 10px; /* Espaçamento interno */
            margin-bottom: 20px; /* Margem inferior */
            border: 1px solid #ced4da; /* Borda com cor */
            border-radius: 5px; /* Bordas arredondadas */
            box-sizing: border-box; /* Caixa de modelagem inclui borda e preenchimento */
        }

        input[type="date"] {
            width: 50%; /* Largura total para os campos de seleção e texto */
            padding: 10px; /* Espaçamento interno */
            margin-bottom: 20px; /* Margem inferior */
            border: 1px solid #ced4da; /* Borda com cor */
            border-radius: 5px; /* Bordas arredondadas */
            box-sizing: border-box; /* Caixa de modelagem inclui borda e preenchimento */
        }

        .custom-ul {
            padding-left: 0; /* Remove qualquer padding padrão à esquerda */
            list-style-type: none; /* Remove marcadores de lista */
        }

        .custom-ul li {
            text-align: left; /* Alinha o texto à esquerda */
        }

        .points {
            color: #F8DE7E;
            font-size: 1.6rem;
            font-family: 'Playfair Display', serif;
        }

        .decimal {
            color: #F8DE7E;
            font-size: 1.3rem;
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body>
    <?php
        include_once "include/menu.php";
        include_once "../Model/AgendaDAO.php";
        include_once "../Model/ContatoDAO.php";
        include_once "../Model/ServicoDAO.php";

        $id = $_SESSION['id'];

        $ContatoDAO = new ContatoDAO();
        $stFiltro = " WHERE admin = 1 AND id =".$id;
        $rsAdmin = $ContatoDAO->recuperaTodos($stFiltro);

        $AgendaDAO = new AgendaDAO();
        $stFiltro = " WHERE confirmado = 1 AND cliente.id = ".$id;
        $cortesRealizados = $AgendaDAO->recuperaHistorico($stFiltro);

        $ServicoDAO = new ServicoDAO();
        $stFiltro = "";
        $rsServicos = $ServicoDAO->recuperaTodos($stFiltro);

        $somaTotal = 0;
        $pontos = '0,00';

        if ($cortesRealizados->num_rows > 0) {
            while ($linha = $cortesRealizados->fetch_assoc()) {
                $valorStr = $linha['valor']; // Valor no formato "xx,xx"
                $valorStr = str_replace(",", ".", $valorStr); // Substitui vírgula por ponto
                $valorFloat = (float)$valorStr; // Converte a string para float
        
                $somaTotal += $valorFloat; // Soma ao total
            }

            $pontos = number_format($somaTotal, 2, ',', '.');
        }
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="box-wrapper">
                <div id="center">
                    <fieldset class="box">
                        <legend class="legend-style">Plano de Fidelidade</legend>
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <h4 class="bonus-title">Regras do Plano:</h4>
                                <ul class="custom-ul" style="list-style: none; padding-left: 0; font-size: 16px; font-family: 'Arial', sans-serif;">
                                    <li><i class="fas fa-angle-right" style="color: #F8DE7E;"></i> A cada <span class="decimal">1 real</span> gasto, você recebe <span class="decimal">1 ponto</span>.</li>
                                    <li><i class="fas fa-angle-right" style="color: #F8DE7E;"></i> <span class="decimal">Acumule</span> pontos e <span class="decimal">troque</span> por bônus.</li>
                                </ul>
                            </div>
                        </div>
                        <?php if ($rsAdmin->num_rows > 0) { ?>
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <button id="cadastrar" name="cadastrarRegra" type="button" class="btn btn-primary">Cadastrar Regras</button>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($rsAdmin->num_rows <= 0) { ?>
                            <div class="row">
                                <div class="col">
                                    <hr>
                                    <h4>Seus Pontos:</h4>
                                    <p>Atualmente você possui <strong><?php echo $pontos; ?> pontos</strong>.</p>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <hr>
                                <h4 class="bonus-title">Bônus Disponíveis:</h4>
                                <ul class="custom-ul">
                                    <li><u><i class="fas fa-angle-right" style="color: #F8DE7E;"></i> Barba:<span class="points"> 140</span><span class="decimal">,00</span> pontos</u></li>
                                    <li><u><i class="fas fa-angle-right" style="color: #F8DE7E;"></i> Corte Degradê:<span class="points"> 175</span><span class="decimal">,00</span> pontos</u></li>
                                    <li><u><i class="fas fa-angle-right" style="color: #F8DE7E;"></i> Corte Degradê e Barba:<span class="points"> 280</span><span class="decimal">,00</span> pontos</u></li>
                                </ul>
                            </div>
                        </div>
                        <?php if ($rsAdmin->num_rows > 0) { ?>
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <button id="cadastrar" type="button" name="cadastrarBonus" class="btn btn-primary" style="text-align: center;">Cadastrar Bônus</button>
                                </div>
                            </div>
                        <?php } ?>
                        <p></p>
                        <div class="row justify-content-center">
                            <div class="col-auto">                            
                                <button type="button-voltar" class="btn btn-secondary" onclick="history.go(-1); return false;">Voltar</button>
                            </div>
                        </div>
                    </fieldset>
                </div>
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
            document.getElementsByName('cadastrarRegra').forEach(function(element) {
                element.addEventListener("click", function() {
                    Swal.fire({
                        title: 'Cadastrar Regras',
                        html: `
                        <?php if ($rsAdmin->num_rows > 0) { ?>
                                        <textarea class="textarea" id="descricao" data-toggle="tooltip" maxlength="255" placeholder="Digite as regras do plano aqui. Use o dígito separador para novos itens."></textarea>
                                        <label for="separador">Dígito Separador:*</label>
                                        <input type="text" id="separador" maxlength="1" placeholder="Informe o dígito separador (ex: | ou ;)">
                                        <p class="help-text">Exemplo: Digite 'Regra1| Regra2| Regra3' e pressione OK. Cada termo separado pelo dígito será uma nova linha.</p>
                                <?php } ?>
                        `,
                        showCloseButton: true,
                        showCancelButton: false,
                        allowOutsideClick: true,
                        preConfirm: () => {
                            Swal.showLoading()
                            return new Promise((resolve) => {
                                setTimeout(() => {
                                    resolve(true)
                                }, 3000)
                            })
                        },
                    })
                })
            })

            document.getElementsByName('cadastrarBonus').forEach(function(element) {
                element.addEventListener("click", function() {
                    Swal.fire({
                        title: 'Cadastrar Bônus',
                        html: `
                            <div class="row">
                                <div class="col">
                                    <label for="barbeiro">Selecione o servico*:</label>
                                    <select id="barbeiro" name="barbeiro">
                                        <option selected disabled>Selecione</option>
                                        <?php
                                        if ($rsServicos->num_rows > 0) {
                                            while ($coluna = $rsServicos->fetch_assoc()) {
                                                echo "<option value='" . $coluna['id'] . "'>" . $coluna['nome'] ."</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Nenhum servico encontrado</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="separador">Meta:*</label>
                                    <input type="number" id="meta" maxlength="5" step="0.010" placeholder="Informe a meta para o resgate do bônus.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="dt_inicio"><span data-toggle="tooltip" data-placement="top" title="Selecione a data inicial da validade do bônus">Data inicial*</span></label>
                                    <input id="dt_inicio" type="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" required/>
                                    <label for="dt_inicio"><span data-toggle="tooltip" data-placement="top" title="Selecione a data final da validade do bônus">Data final*</span></label>
                                    <input id="dt_inicio" type="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" required/>
                                </div>
                            </div>
                        `,
                        showCloseButton: true,
                        showCancelButton: false,
                        allowOutsideClick: true,
                        didOpen: () => {
                            document.getElementById('meta').addEventListener("change", function(){
                                this.value = parseFloat(this.value).toFixed(2);
                            });
                        },
                        preConfirm: () => {
                            Swal.showLoading()
                            return new Promise((resolve) => {
                                setTimeout(() => {
                                    resolve(true)
                                }, 3000)
                            })
                        },
                    })
                })
            })
        });
    </script>
</body>
</html>