<?php

// $host = 'localhost';
// $user = 'root';
// $password = '';
// $database = 'barbeariacastro';
// $port = '3307';

// $pdo = new PDO(dsn: "mysql:host=$host;port=$port;dbname=$database", username: $user, password: $password);

// var_dump($pdo);


// Conecta ao banco de dados
$conexao = mysqli_connect(
    "localhost",
    "root",
    "",
    "barbeariacastro",
    3307
);

if (!$conexao) {
    die("Erro ao conectar ao banco de dados.");
}

//Configura caracteres especiais
mysqli_set_charset($conexao, "utf8mb4");

?>
