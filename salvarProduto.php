<?php
require("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idItem = $_POST['idItem'];
    $nomeItem = $_POST['nomeItem'];
    $descricaoItem = $_POST['descricaoItem'];
    $valor = $_POST['valor'];
    
    // Tratamento de imagem (se o usuário enviou uma nova imagem)
    $nomeImagem = $_POST['imagemAntiga']; // Mantém a antiga por padrão
    
    if (isset($_FILES['imagemProduto']) && $_FILES['imagemProduto']['error'] == 0) {
        $extensao = pathinfo($_FILES['imagemProduto']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid() . "." . $extensao;
        $pastaDestino = "img/";
        
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }
        move_uploaded_file($_FILES['imagemProduto']['tmp_name'], $pastaDestino . $nomeImagem);
    }

    // Se tem ID, é Edição (UPDATE)
    if (!empty($idItem)) {
        $sql = "UPDATE itens SET nomeItem = '$nomeItem', descricaoItem = '$descricaoItem', valor = '$valor', imagemProduto = '$nomeImagem' WHERE idItem = $idItem";
        $msg = "Produto atualizado com sucesso!";
    } 
    // Se não tem ID, é Cadastro Novo (INSERT)
    else {
        $sql = "INSERT INTO itens (nomeItem, descricaoItem, valor, imagemProduto) VALUES ('$nomeItem', '$descricaoItem', '$valor', '$nomeImagem')";
        $msg = "Item cadastrado com sucesso!";
    }

    if (mysqli_query($conexao, $sql)) {
        echo "<script>alert('$msg'); window.location.href = 'painel.php';</script>";
    } else {
        echo "<script>alert('Erro: " . mysqli_error($conexao) . "'); window.history.back();</script>";
    }
}
?>