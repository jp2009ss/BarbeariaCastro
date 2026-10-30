<?php
session_start();

// Só quem está logado pode ver contatos e responder.
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require 'conexao.php';

// Pega o ID passado pela URL
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: painel.php");
    exit;
}

// Busca os dados do contato específico para exibir na tela
$stmt = mysqli_prepare($conexao, "SELECT * FROM contato WHERE idContato = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$contato = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$contato) {
    exit("Contato não encontrado.");
}

$erro = "";

// Quando o formulário de resposta for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // OBS: o texto da resposta ainda não é salvo nem enviado por e-mail.
    // Por enquanto, só marca o contato como respondido (status = 1).
    $stmt = mysqli_prepare($conexao, "UPDATE contato SET status = 1 WHERE idContato = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Contato marcado como respondido!');
            window.location.href = 'painel.php';
        </script>";
        exit;
    }

    error_log('Erro ao atualizar contato: ' . mysqli_error($conexao));
    $erro = "Erro ao atualizar o contato.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Contato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body class="container mt-5">

    <h2>Responder para: <?= htmlspecialchars($contato['nome']) ?></h2>
    <p><strong>E-mail:</strong> <?= htmlspecialchars($contato['email']) ?></p>
    <p><strong>Mensagem original:</strong><br><?= nl2br(htmlspecialchars($contato['mensagem'])) ?></p>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="mensagem_resposta" class="form-label">Sua Resposta:</label>
            <textarea class="form-control" id="mensagem_resposta" name="mensagem_resposta" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Resposta</button>
        <a href="painel.php" class="btn btn-secondary">Voltar</a>
    </form>

</body>

</html>