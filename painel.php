<?php
// Inicia ou recupera a sessão do usuário.
session_start();

// Impede cache da página (evita ver conteúdo antigo após o logout).
header('Cache-Control: no-store, no-cache, must-revalidate');

// Só entra quem estiver logado (autenticar.php grava 'usuario_id').
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require 'conexao.php';

// Escapa texto antes de mostrar no HTML (evita XSS).
function h($valor)
{
    return htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Mensagens de contato ainda não resolvidas.
$resultado = mysqli_query(
    $conexao,
    "SELECT idContato, nome, email, telefone, assunto, mensagem
     FROM contato
     WHERE status = 0
     ORDER BY idContato DESC"
);
if (!$resultado) {
    http_response_code(500);
    exit("Não foi possível carregar os contatos.");
}

// Variáveis do formulário (vazias = novo item).
$idItem = "";
$nomeItem = "";
$descricaoItem = "";
$valor = "";
$imagemProduto = "";

// Se clicou em Editar, busca o item (consulta preparada, id como número).
if (isset($_GET['editar'])) {
    $id = (int) $_GET['editar'];
    $stmt = mysqli_prepare($conexao, "SELECT * FROM itens WHERE idItem = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $produtoEdit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($produtoEdit) {
        $idItem = $produtoEdit['idItem'];
        $nomeItem = $produtoEdit['nomeItem'];
        $descricaoItem = $produtoEdit['descricaoItem'];
        $valor = $produtoEdit['valor'];
        $imagemProduto = $produtoEdit['imagemProduto'];
    }
}

// Lista de itens cadastrados.
$itens = mysqli_query($conexao, "SELECT * FROM itens ORDER BY idItem DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel | Barbearia Castro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">Barbearia Castro | Administração</span>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-outline-light">Ver site</a>
                <form action="logout.php" method="POST">
                    <button class="btn btn-outline-light">Sair</button>
                </form>
            </div>
        </div>
    </nav>

    <main>
        <section class="container">
            <h1>Mensagens recebidas</h1>
            <p>Olá, <?= h($_SESSION['usuario_nome'] ?? '') ?>.</p>
        </section>

        <!-- Mensagens de contato -->
        <section class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Telefone</th>
                            <th scope="col">Assunto</th>
                            <th scope="col">Mensagem</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($linha = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?= h($linha['idContato']) ?></td>
                                <td><?= h($linha['nome']) ?></td>
                                <td><?= h($linha['email']) ?></td>
                                <td><?= h($linha['telefone']) ?></td>
                                <td><?= h($linha['assunto']) ?></td>
                                <td style="min-width:220px;white-space:pre-wrap"><?= h($linha['mensagem']) ?></td>
                                <td class="text-nowrap">
                                    <a href="responder.php?id=<?= (int) $linha['idContato'] ?>"
                                        class="btn btn-primary btn-sm">Responder</a>
                                    <a href="resolvido.php?id=<?= (int) $linha['idContato'] ?>"
                                        class="btn btn-success btn-sm">Resolvido</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Cadastro / edição de itens -->
        <div class="container mt-4">
            <div class="accordion" id="accordionItens">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button <?= $idItem === '' ? 'collapsed' : '' ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseCadastro"
                            aria-expanded="<?= $idItem === '' ? 'false' : 'true' ?>" aria-controls="collapseCadastro">
                            <?= $idItem === '' ? 'Cadastrar item' : 'Editar item' ?>
                        </button>
                    </h2>
                    <div id="collapseCadastro" class="accordion-collapse collapse <?= $idItem === '' ? '' : 'show' ?>">
                        <div class="accordion-body">
                            <form method="POST" action="cadastrarItem.php" enctype="multipart/form-data"
                                class="mx-auto" style="max-width: 600px;">
                                <!-- Vazio = novo item; preenchido = edição -->
                                <input type="hidden" name="idItem" value="<?= h($idItem) ?>">

<<<<<<< HEAD
                            <div class="container accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
                                            <h4>Cadastrar Produtos</h4>
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse " aria-labelledby="panelsStayOpen-headingOne">
                                        <div class="accordion-body">
                                            <section class="container card shadow-sm p-4 .accordion mx-auto" style="max-width: 600px;">
                                                <h1 class="text-center">Cadastrar Produtos</h1>
                                                <div>
                                                    <form method="POST" action="salvarProduto.php" enctype="multipart/form-data">

                                                        <!-- Campo oculto para saber se é edição -->
                                                        <input type="hidden" name="idItem" value="<?php echo $idItem; ?>">
                                                        <input type="hidden" name="imagemAntiga" value="<?php echo $imagemProduto; ?>">

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nome:</label>
                                                            <input type="text" name="nomeItem" class="form-control" value="<?php echo $nomeItem; ?>" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Descrição:</label>
                                                            <input type="text" name="descricaoItem" class="form-control" value="<?php echo $descricaoItem; ?>">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Valor:</label>
                                                            <input type="number" step="0.01" name="valor" class="form-control" value="<?php echo $valor; ?>" required>
                                                        </div>

                                                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('hiddenFile').click()"
                                                            ondragover="event.preventDefault();this.classList.add('dragover')"
                                                            ondragleave="this.classList.remove('dragover')"
                                                            ondrop="event.preventDefault();this.classList.remove('dragover')">
                                                            <input type="file" id="hiddenFile" multiple>
                                                            <div style="font-size:2.5rem;margin-bottom:12px;">📁</div>
                                                            <h6 class="fw-bold mb-1">Drop files here or click to upload</h6>
                                                            <p class="text-muted small mb-3">Support for JPG, PNG, PDF up to 10MB</p>
                                                            <button type="button" class="btn btn-sm text-white px-4" style="background:#fd4766;"
                                                                onclick="event.stopPropagation();document.getElementById('hiddenFile').click()">
                                                                Browse Files
                                                            </button>
                                                        </div>
                                                        <!-- <div class="mb-3">
                                                            <label class="form-label fw-semibold">Imagem do Item:</label>
                                                            <input type="file" name="imagemProduto" class="form-control" accept="image/*">
                                                            <?php if (!empty($imagemProduto)): ?>
                                                                <small class="text-muted">Imagem atual: <?php echo $imagemProduto; ?></small>
                                                            <?php endif; ?>
                                                        </div> -->

                                                        <div class="d-flex justify-content-center gap-2 mt-4">
                                                            <!-- O texto do botão muda dinamicamente -->
                                                            <button type="submit" class="btn btn-primary px-4">
                                                                <?php echo !empty($idItem) ? "Atualizar" : "Cadastrar"; ?>
                                                            </button>
                                                            <a href="painel.php" class="btn btn-outline-secondary px-3">Cancelar</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
=======
                                <div class="mb-3">
                                    <label for="nomeItem" class="form-label fw-semibold">Nome</label>
                                    <input type="text" id="nomeItem" name="nomeItem" class="form-control"
                                        value="<?= h($nomeItem) ?>" required>
>>>>>>> a38dbd5a65aa58bc8ec56821ffb7438dfbd935a6
                                </div>
                                <div class="mb-3">
                                    <label for="descricaoItem" class="form-label fw-semibold">Descrição</label>
                                    <input type="text" id="descricaoItem" name="descricaoItem" class="form-control"
                                        value="<?= h($descricaoItem) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="valor" class="form-label fw-semibold">Valor</label>
                                    <input type="number" step="0.01" min="0" id="valor" name="valor"
                                        class="form-control" value="<?= h($valor) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="imagemProduto" class="form-label fw-semibold">Imagem do item</label>
                                    <input type="file" id="imagemProduto" name="imagemProduto" class="form-control"
                                        accept="image/*">
                                    <?php if (!empty($imagemProduto)): ?>
                                        <small class="text-muted">Imagem atual: <?= h($imagemProduto) ?>
                                            (envie outra para trocar)</small>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-center gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <?= $idItem === '' ? 'Cadastrar' : 'Atualizar' ?>
                                    </button>
                                    <a href="painel.php" class="btn btn-outline-secondary px-3">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de itens -->
        <div class="container mt-4 mb-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Lista de produtos</h4>
                </div>
                <div class="card-body">
                    <?php if (mysqli_num_rows($itens) === 0): ?>
                        <h5>Nenhum produto encontrado</h5>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                        <th>Imagem</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = mysqli_fetch_assoc($itens)): ?>
                                        <tr>
                                            <td><?= h($item['idItem']) ?></td>
                                            <td><?= h($item['nomeItem']) ?></td>
                                            <td><?= h($item['descricaoItem']) ?></td>
                                            <td>R$ <?= number_format((float) $item['valor'], 2, ',', '.') ?></td>
                                            <td>
                                                <?php if (!empty($item['imagemProduto'])): ?>
                                                    <img src="img/<?= h($item['imagemProduto']) ?>" width="60"
                                                        alt="<?= h($item['nomeItem']) ?>">
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="painel.php?editar=<?= (int) $item['idItem'] ?>"
                                                    class="btn btn-success btn-sm">Editar</a>
                                                <form action="excluiritem.php" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Tem certeza que deseja excluir este item?')">
                                                    <input type="hidden" name="idItem"
                                                        value="<?= (int) $item['idItem'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>