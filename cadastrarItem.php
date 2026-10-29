<?php
require("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $nomeItem      = $_POST['nomeItem'];
    $descricaoItem = $_POST['descricaoItem'];
    $valor         = $_POST['valor'];

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
        
        move_uploaded_file($_FILES['imagemProduto']['tmp_name'], $pastaDestino . $nomeImagem);
    }

    // Comando SQL utilizando exatamente os nomes das colunas da tabela 'itens'
    $sql = "INSERT INTO itens (nomeItem, descricaoItem, valor, imagemProduto) 
            VALUES ('$nomeItem', '$descricaoItem', '$valor', '$nomeImagem')";

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