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
$sql = "SELECT * FROM contatos WHERE idContato = '$id'";
$resultado = mysqli_query($conexao, $sql);
$contato = mysqli_fetch_assoc($resultado);

if (!$contato) {
    echo "Contato não encontrado.";
    exit;
}

// Lógica para quando o formulário de resposta for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resposta = $_POST['mensagem_resposta'];
    $emailDestino = $contato['email'];
    $assunto = "Re: " . $contato['assunto'];

    // Exemplo usando a função nativa mail() do PHP (requer servidor de e-mail configurado)
    // Ou você pode salvar a resposta em uma tabela de respostas no banco de dados.
    $enviado = mail($emailDestino, $assunto, $resposta, "From: seu-email@dominio.com");

    if ($enviado) {
        // Opcional: Atualizar o status para 'Respondido'
        mysqli_query($conexao, "UPDATE contatos SET status = 'Respondido' WHERE idContato = '$id'");
        
        header("Location: painel.php?msg=respondido");
        exit;
    } else {
        $erro = "Falha ao enviar o e-mail.";
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