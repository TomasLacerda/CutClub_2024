<?php
// Verifica se o ID do barbeiro foi fornecido na URL
if(isset($_GET['id'])) {
    $id_barbeiro = $_GET['id'];

    include_once "include/menu.php";
    include_once "../Model/Contato.php";
    include_once "../Model/ContatoDAO.php";
    
    $contato = new Contato();
    $ContatoDAO = new ContatoDAO();

    $contato->id = $id_barbeiro;
    $contato->barbeiro = 0;

    if(isset($_GET['location'])) {
        $resultado = $ContatoDAO->excluirCadastroDAO($contato);
    } else {
        $resultado = $ContatoDAO->salvarFuncionarioDAO($contato);
    }
        

    // Redireciona de volta para a página anterior após a exclusão
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=realizada");
    exit;
} else {
    // Se nenhum ID foi fornecido, redireciona de volta à página anterior sem fazer nada
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=nao-realizada");
    exit;
}
?>