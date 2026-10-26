<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $item =  $_POST['item'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    

    require("conexao.php");

    $inserir = "INSERT INTO matricula (item,descricao,valor) VALUES ('$item','$descricao','$valor')";

    if (mysqli_query($conexao, $inserir)) {

        echo "
        <script>

        alert('Item Cadastrado com sucesso!');
        window.location.href = 'cadastrarItem.php';
        
        </script>";
        exit();
    } else {
        echo "
        <script>

        alert('Erro ao cadastrar:" . mysqli_error($conexao) . "';
        window.history.back();
        
        </script>";
        exit();
    }
}
