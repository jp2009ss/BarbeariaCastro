<?php
include 'conexao.php';

// Pega o ID passado pela URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: painel.php");
    exit;
}

// Busca os dados do contato específico para exibir na tela
$id = mysqli_real_escape_string($conexao, $id);
$sql = "SELECT * FROM contato WHERE idContato = '$id'";
$resultado = mysqli_query($conexao, $sql);
$contato = mysqli_fetch_assoc($resultado);

if (!$contato) {
    echo "Contato não encontrado.";
    exit;
}

// Lógica para quando o formulário de resposta for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resposta = $_POST['mensagem_resposta'];

    // Atualiza o status para 1 (ou 'Respondido') indicando que já foi tratado
    $sqlUp = "UPDATE contato SET status = 1 WHERE idContato = '$id'";
    
    if (mysqli_query($conexao, $sqlUp)) {
        echo "<script>
                alert('Resposta salva com sucesso!');
                window.location.href = 'painel.php';
              </script>";
        exit;
    } else {
        $erro = "Erro ao atualizar: " . mysqli_error($conexao);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Responder Contato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Responder para: <?= htmlspecialchars($contato['nome']) ?></h2>
    <p><strong>E-mail:</strong> <?= htmlspecialchars($contato['email']) ?></p>
    <p><strong>Mensagem original:</strong><br><?= nl2br(htmlspecialchars($contato['mensagem'])) ?></p>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="mensagem_resposta" class="form-label">Sua Resposta:</label>
            <textarea class="form-class form-control" id="mensagem_resposta" name="mensagem_resposta" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Resposta</button>
        <a href="painel.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>
</html>