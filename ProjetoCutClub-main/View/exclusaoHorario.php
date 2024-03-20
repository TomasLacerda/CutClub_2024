<?php
// Verifica se o ID do barbeiro foi fornecido na URL
if(isset($_GET['id'])) {
    $id_barbeiro = $_GET['id'];

    include_once "include/menu.php";
    include_once "../Model/DtIndisponivel.php";
    include_once "../Model/DtIndisponivelDAO.php";
    
    $DtIndisponivel = new DtIndisponivel();
    $DtIndisponivelDAO = new DtIndisponivelDAO();

    $DtIndisponivel->id = $id_barbeiro;
    $DtIndisponivel->barbeiro = 0;

    $resultado = $DtIndisponivelDAO->excluirHorarioDAO($DtIndisponivel);

    // Redireciona de volta para a página anterior após a exclusão
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=realizada");
    exit;
} else {
    // Se nenhum ID foi fornecido, redireciona de volta à página anterior sem fazer nada
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=nao-realizada");
    exit;
}
?>