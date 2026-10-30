<?php

// Inicia ou recupera a sessão do usuário.
session_start();

// Impede que o navegador armazene a página em cache.
// Ajuda a evitar a exibição de conteúdo antigo após o logout.
header('Cache-Control: no-store, no-cache, must-revalidate');

// Verifica se o usuário está autenticado.
// A variável de sessão 'usuario_id' deve ser definida no login.
if (!isset($_SESSION['usuario_id'])) {

    // Redireciona o usuário para a página de login.
    header('Location: login.php');

    // Encerra a execução do código após o redirecionamento.
    exit;
}

// Importa o arquivo responsável pela conexão com o MySQL.
// Esse arquivo deve disponibilizar a variável $conexao.
require 'conexao.php';

// Executa uma consulta SQL no banco de dados.
$resultado = mysqli_query(

    // Conexão com o banco de dados MySQL.
    // Seleciona os campos desejados da tabela contato.
    // ORDER BY organiza os registros pelo ID em ordem decrescente.
    // DESC faz com que os maiores IDs apareçam primeiro.
    $conexao,
    "SELECT idContato, nome, email, telefone, assunto, mensagem, status
        FROM contato
        WHERE status = 0
        ORDER BY idContato DESC"
);

// Verifica se ocorreu algum erro na execução da consulta.
// mysqli_query retorna false quando a consulta falha.
if (!$resultado) {

    // Define o código HTTP 500, indicando erro interno no servidor.
    http_response_code(500);

    // Exibe uma mensagem de erro e encerra a execução.
    exit("Não foi possível carregar os contatos.");
}

// Função auxiliar para exibir informações com segurança no HTML.
// Recebe um valor e converte caracteres especiais em entidades HTML.
// Ajuda a prevenir ataques de Cross-Site Scripting (XSS)
// quando os dados são exibidos no conteúdo da página.
function h($valor)
{
    // Converte o valor recebido para texto (string).
    // ENT_QUOTES converte aspas simples e duplas.
    // ENT_SUBSTITUTE substitui sequências inválidas de caracteres.
    // UTF-8 define a codificação utilizada na conversão.
    return htmlspecialchars(
        (string)$valor,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}
// 1. Inicializa as variáveis vazias para evitar o aviso de "Undefined variable"
$idItem = "";
$nomeItem = "";
$descricaoItem = "";
$valor = "";
$imagemProduto = "";

// 2. Se o usuário clicou em Editar, busca os dados do banco
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $sqlEdit = "SELECT * FROM itens WHERE idItem = $id";
    $resultadoEdit = mysqli_query($conexao, $sqlEdit);

    if ($resultadoEdit && mysqli_num_rows($resultadoEdit) > 0) {
        $produtoEdit = mysqli_fetch_assoc($resultadoEdit);
        $idItem = $produtoEdit['idItem'];
        $nomeItem = $produtoEdit['nomeItem'];
        $descricaoItem = $produtoEdit['descricaoItem'];
        $valor = $produtoEdit['valor'];
        $imagemProduto = $produtoEdit['imagemProduto'];
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $item =  $_POST['item'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    require("conexao.php");

    $inserir = "INSERT INTO itens (nomeItem,descricaoItem,valor) VALUES ('$item','$descricao','$valor')";

    if (mysqli_query($conexao, $inserir)) {

        echo "
            <script>

            alert('Cadastro Realizado com sucesso!');
            window.location.href = 'painel.php';
            
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
// $sql = "SELECT * FROM itens ORDER by id DESC";

// $result = $conexao->query($sql);

// print_r($result);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel | BarbeariaCastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar bg- navbar-dark">
        <div class="container"><span class="navbar-brand">BarbeariaCastro | Administração</span>
            <form action="logout.php" method="POST"><button class="btn btn-outline-light">Sair</button></form>
        </div>
    </nav>

    <main>
        <section class="container">
            <h1>Mensagens recebidas</h1>
            <p>Olá,
                <?= $_SESSION['usuario_nome']; ?>.
            </p>
        </section>

        <section class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nome</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Assunto</th>
                        <th scope="col">Mensagem</th>
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
                            <td>

                                <a href="responder.php?id=<?= h($linha['idContato']) ?>" class="btn btn-primary">Responder</a>
                                <a href="resolvido.php?id=<?= h($linha['idContato']) ?>" class="btn btn-success">Resolvido</a>


                                <a href="responder.php?id=<?= h($linha['idContato']) ?>" class="btn btn-primary">Responder</a>
                                <a href="resolvido.php?id=<?= h($linha['idContato']) ?>" class="btn btn-primary">Resolvido</a>

                                <a href="responder.php" class="btn btn-primary">Responder</a>
                                <a href="resolvido.php" class="btn btn-success">Resolvido</a>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section><br>

        <!-- accordion-->
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <section class="container">

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

                                                        <style>
                                                            .upload-zone {
                                                                border: 2px dashed #dee2e6;
                                                                border-radius: 10px;
                                                                padding: 40px 20px;
                                                                text-align: center;
                                                                cursor: pointer;
                                                                transition: border-color 0.2s, background 0.2s;
                                                            }

                                                            .upload-zone:hover,
                                                            .upload-zone.dragover {
                                                                border-color: #fd4766;
                                                                background: #fff8f9;
                                                            }

                                                            .upload-zone input[type="file"] {
                                                                display: none;
                                                            }
                                                        </style>
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
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <!-- area de adminstração de produtos  -->
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>lista de Produtos</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
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
                                <?php
                                $sql = 'SELECT * FROM itens';
                                $itens = mysqli_query($conexao, $sql);
                                if (mysqli_num_rows($itens) > 0) {
                                    foreach ($itens as $item) {
                                ?>
                                        <tbody>
                                            <tr>
                                                <td><?= $item['idItem'] ?></td>
                                                <td><?= $item['nomeItem'] ?></td>
                                                <td><?= $item['descricaoItem'] ?></td>
                                                <td><?= $item['valor'] ?></td>
                                                <td>
                                                    <img src="img/<?= $item['imagemProduto'] ?>" width="60" alt="Imagem do Produto">
                                                </td>
                                                <td>
                                                    <a href="painel.php?editar=<?php echo $item['idItem']; ?>" class="btn btn-success btn-sm">Editar</a>
                                                    <form action="" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_produto" value="1" class="btn btn-danger btn-sm">Excluir</button>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php
                                    }
                                } else {
                                    echo '<h5>Nenhum produto encontrado</h5>';
                                }
                                    ?>
                                        </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>



    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        // Verifica se a URL contém o parâmetro "editar"
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('editar')) {
            // Usa exatamente o ID do seu accordion atual: "panelsStayOpen-collapseOne"
            const elementoCollapse = document.getElementById('panelsStayOpen-collapseOne');

            if (elementoCollapse) {
                // Abre o accordion automaticamente via Bootstrap
                const bsCollapse = new bootstrap.Collapse(elementoCollapse, {
                    toggle: true
                });
            }
        }
    </script>
</body>

</html>