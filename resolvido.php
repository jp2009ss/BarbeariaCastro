<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexao, $_GET['id']);

    // Atualiza o status para 1 (resolvido)
    $sql = "UPDATE contato SET status = 1 WHERE idContato = '$id'";

    if (mysqli_query($conexao, $sql)) {
        header("Location: painel.php?msg=resolvido");
        exit;
    } else {
        echo "Erro ao atualizar o registo: " . mysqli_error($conexao);
    }
} else {
    header("Location: painel.php");
    exit;
}
?>