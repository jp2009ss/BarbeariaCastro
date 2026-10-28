<?php
require("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeItem = $_POST['nomeItem'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    // Tratamento do Upload da Imagem
    $nomeImagem = "";
    if (isset($_FILES['imagemProduto']) && $_FILES['imagemProduto']['error'] == 0) {
        $extensao = pathinfo($_FILES['imagemProduto']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid() . "." . $extensao;
        $pastaDestino = "img/";
        
        // Cria a pasta img/ caso ela não exista
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }
        
        move_uploaded_file($_FILES['imagemItem']['tmp_name'], $pastaDestino . $nomeImagem);
    }

    // Comando SQL para inserir na tabela (certifique-se de que a tabela se chama 'produtos' ou ajuste para o seu caso)
    $sql = "INSERT INTO itens (nomeItem, descricao, valor, imagemProduto) VALUES ('$nomeItem', '$descricao', '$valor', '$nomeImagem')";

    if (mysqli_query($conexao, $sql)) {
        echo "
            <script>
                alert('Item cadastrado com sucesso!');
                window.location.href = 'painel.php';
            </script>
        ";
        exit;
    } else {
        echo "
            <script>
                alert('Erro ao cadastrar: " . mysqli_error($conexao) . "');
                window.history.back();
            </script>
        ";
        exit;
    }
}
?>