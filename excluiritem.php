<?php
session_start();

// Só quem está logado pode excluir.
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Exclusão só por POST (o botão Excluir do painel envia um formulário).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: painel.php');
    exit;
}

require 'conexao.php';

$id = (int) ($_POST['idItem'] ?? 0);

if ($id > 0) {
    // Descobre a imagem para apagar o arquivo junto.
    $stmt = mysqli_prepare($conexao, "SELECT imagemProduto FROM itens WHERE idItem = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($item) {
        $stmt = mysqli_prepare($conexao, "DELETE FROM itens WHERE idItem = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt) && !empty($item['imagemProduto'])) {
            $arquivo = 'img/' . basename($item['imagemProduto']);
            if (is_file($arquivo)) {
                unlink($arquivo);
            }
        }
    }
}

header('Location: painel.php');
exit;