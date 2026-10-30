<?php
session_start();

// Só quem está logado pode cadastrar ou editar itens.
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Só aceita envio de formulário (POST).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: painel.php');
    exit;
}

require 'conexao.php';

// Mostra um aviso e volta para o formulário.
function voltar($mensagem)
{
    echo "<script>alert(" . json_encode($mensagem) . "); window.history.back();</script>";
    exit;
}

// Recebe os dados do formulário.
$idItem = (int) ($_POST['idItem'] ?? 0);          // 0 = novo item
$nomeItem = trim($_POST['nomeItem'] ?? '');
$descricaoItem = trim($_POST['descricaoItem'] ?? '');
$valor = (float) ($_POST['valor'] ?? 0);

if ($nomeItem === '') {
    voltar('Informe o nome do item.');
}

// Na edição, parte da imagem que já está salva no banco.
$nomeImagem = '';
if ($idItem > 0) {
    $stmt = mysqli_prepare($conexao, "SELECT imagemProduto FROM itens WHERE idItem = ?");
    mysqli_stmt_bind_param($stmt, "i", $idItem);
    mysqli_stmt_execute($stmt);
    $atual = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if (!$atual) {
        voltar('Item não encontrado.');
    }
    $nomeImagem = (string) $atual['imagemProduto'];
}

// Upload da imagem (opcional).
if (isset($_FILES['imagemProduto']) && $_FILES['imagemProduto']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['imagemProduto']['tmp_name'];
    $extensao = strtolower(pathinfo($_FILES['imagemProduto']['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    // Só imagens de verdade (evita enviar um .php disfarçado).
    if (!in_array($extensao, $permitidas, true) || getimagesize($tmp) === false) {
        voltar('Envie uma imagem JPG, PNG, GIF ou WEBP.');
    }

    $pastaDestino = 'img/';
    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0755, true);
    }

    $novoNome = uniqid() . '.' . $extensao;
    if (!move_uploaded_file($tmp, $pastaDestino . $novoNome)) {
        voltar('Não foi possível salvar a imagem.');
    }

    // Remove a imagem antiga ao trocar.
    if ($nomeImagem !== '' && is_file($pastaDestino . basename($nomeImagem))) {
        unlink($pastaDestino . basename($nomeImagem));
    }
    $nomeImagem = $novoNome;
}

// Insere ou atualiza (colunas da tabela 'itens').
if ($idItem > 0) {
    $stmt = mysqli_prepare(
        $conexao,
        "UPDATE itens SET nomeItem = ?, descricaoItem = ?, valor = ?, imagemProduto = ? WHERE idItem = ?"
    );
    mysqli_stmt_bind_param($stmt, "ssdsi", $nomeItem, $descricaoItem, $valor, $nomeImagem, $idItem);
    $mensagem = 'Item atualizado com sucesso!';
} else {
    $stmt = mysqli_prepare(
        $conexao,
        "INSERT INTO itens (nomeItem, descricaoItem, valor, imagemProduto) VALUES (?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "ssds", $nomeItem, $descricaoItem, $valor, $nomeImagem);
    $mensagem = 'Item cadastrado com sucesso!';
}

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert(" . json_encode($mensagem) . "); window.location.href = 'painel.php';</script>";
} else {
    error_log('Erro ao salvar item: ' . mysqli_error($conexao));
    voltar('Erro ao salvar o item.');
}