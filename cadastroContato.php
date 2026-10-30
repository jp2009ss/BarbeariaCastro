<?php
// Só aceita envio do formulário (POST); acesso direto volta ao site.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// Recebe os dados enviados pelo formulário
$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$assunto = trim($_POST["assunto"] ?? "");
$mensagem = trim($_POST["mensagem"] ?? "");

// Validação básica
if ($nome === "" || $mensagem === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
        alert('Preencha nome, e-mail válido e mensagem.');
        window.history.back();
    </script>";
    exit;
}

// Importa a conexão com o banco
require("conexao.php");

// Prepara o comando SQL
$sql = "INSERT INTO contato (nome, email, telefone, assunto, mensagem, status) VALUES (?,?,?,?,?,0)";
$stmt = mysqli_prepare($conexao, $sql);

// Vincula os valores aos ?
mysqli_stmt_bind_param($stmt, "sssss", $nome, $email, $telefone, $assunto, $mensagem);

// Executa
if (mysqli_stmt_execute($stmt)) {
    echo "<script>
        alert('Mensagem enviada com sucesso!');
        window.location.href = 'index.php';
    </script>";
} else {
    error_log('Erro ao salvar contato: ' . mysqli_error($conexao));
    echo "<script>
        alert('Erro ao enviar mensagem. Tente novamente.');
        window.history.back();
    </script>";
}

mysqli_stmt_close($stmt);
mysqli_close($conexao);