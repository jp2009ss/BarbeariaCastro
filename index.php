<?php
session_start();
require('conexao.php');

$logado = isset($_SESSION['usuario_id']);

// Busca os itens cadastrados no banco
$resultadoProdutos = mysqli_query($conexao, "SELECT * FROM itens ORDER BY idItem DESC");
if (!$resultadoProdutos) {
    die('Erro na consulta: ' . mysqli_error($conexao));
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbearia Castro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- NavBar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-suit-club"></i> Barbearia Castro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categorias">Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produtos.html">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#duvidas">Dúvidas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contatos">Contatos</a>
                    </li>
                    <li class="nav-item">
                        <?php if ($logado) { ?>
                            <a class="nav-link" href="painel.php">Painel</a>
                        <?php } else { ?>
                            <a class="nav-link" href="login.php">Entrar</a>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <!-- Carousel (seção invertida) -->
        <section id="inicio" class="secao-invertida">
            <div id="carouselExample" class="carousel slide">
                <div class="carousel-inner">

                    <!-- Carousel 01 -->
                    <div class="carousel-item active text-center p-5">
                        <h1>Corte seu cabelo e melhore sua aparência.</h1>
                        <p class="fs-5">Os melhores cortes para arrumar sua lataria.</p>
                        <a href="produtos.html" class="btn btn-primary">Conhecer produtos</a>
                    </div>

                    <!-- Carousel 02 -->
                    <div class="carousel-item text-center p-5">
                        <h1>Semana de Promoção</h1>
                        <p class="fs-5">Ofertas especiais em cortes e produtos.</p>
                        <a href="produtos.html" class="btn btn-primary">Ver ofertas</a>
                    </div>

                    <!-- Carousel 03 -->
                    <div class="carousel-item text-center p-5">
                        <h1>Tudo o que você procura está aqui</h1>
                        <p class="fs-5">Cortes, barba e produtos em um só lugar.</p>
                        <a href="#categorias" class="btn btn-primary">Explorar categorias</a>
                    </div>

                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
        </section>

        <!-- Alert -->
        <section class="container mt-4">
            <div class="alert alert-primary text-center" role="alert">
                <i class="bi bi-truck"></i>
                <strong>Frete grátis!</strong>
                Para compras acima de R$ 150,00.
            </div>
        </section>

        <!-- Card Categorias (itens do banco) -->
        <section class="container text-center mt-5 py-5" id="categorias">
            <!-- Titulo -->
            <div>
                <h2 class="fw-bold">Nossas Categorias</h2>
                <p class="mb-5">Encontre tudo o que você precisa para o seu cabelo e barba.</p>
            </div>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                <?php if (mysqli_num_rows($resultadoProdutos) === 0) { ?>
                    <p class="opacity-75">Nenhum item cadastrado ainda.</p>
                <?php } ?>

                <?php while ($produto = mysqli_fetch_assoc($resultadoProdutos)) { ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <!-- Exibe a imagem se ela existir -->
                        <?php if (!empty($produto['imagemProduto'])) { ?>
                        <img src="img/<?= htmlspecialchars($produto['imagemProduto']) ?>" class="card-img-top"
                            alt="<?= htmlspecialchars($produto['nomeItem']) ?>"
                            style="height: 160px; object-fit: cover;">
                        <?php } ?>

                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title h5">
                                <?= htmlspecialchars($produto['nomeItem']) ?>
                            </h3>
                            <p class="card-text flex-grow-1">
                                <?= htmlspecialchars($produto['descricaoItem']) ?>
                            </p>
                            <p class="card-text fw-bold text-success">R$
                                <?= number_format((float) $produto['valor'], 2, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>

        <!-- Cortes (separado por bordas, sem fundo diferente) -->
        <section class="container-fluid border-top border-bottom pt-1">
            <div class="container text-center mt-5" id="produtos">
                <!-- Titulo -->
                <div>
                    <h2 class="mt-5 fw-bold">Cortes</h2>
                    <p class="opacity-75">Veja alguns dos cortes feitos por nosso profissional.</p>
                </div>

                <div class="row pb-5 py-5 g-3">
                    <div class="col-12 col-md-4">
                        <!-- Card 01 -->
                        <div class="card mx-auto" style="width: 18rem;">
                            <img src="img/Mídia (1).jpg" class="card-img-top" alt="Amostra de corte 1"
                                style="height: 220px; object-fit: cover;">
                            <div class="card-body">
                                <span class="badge">Amostra</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <!-- Card 02 -->
                        <div class="card mx-auto" style="width: 18rem;">
                            <img src="img/shared image (5).jpg" class="card-img-top" alt="Amostra de corte 2"
                                style="height: 220px; object-fit: cover;">
                            <div class="card-body">
                                <span class="badge">Amostra</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <!-- Card 03 -->
                        <div class="card mx-auto" style="width: 18rem;">
                            <img src="img/Mídia.jpg" class="card-img-top" alt="Amostra de corte 3"
                                style="height: 220px; object-fit: cover;">
                            <div class="card-body">
                                <span class="badge">Amostra</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefícios -->
        <section class="py-5">
            <div class="container">
                <div class="row text-center g-4">
                    <!-- ENTREGA -->
                    <div class="col-12 col-md-4">
                        <div class="fs-1">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h5>Entrega</h5>
                        <p>Consulte disponibilidade e prazo para sua região.</p>
                    </div>
                    <!-- COMPRA SEGURA -->
                    <div class="col-12 col-md-4">
                        <div class="fs-1">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5>Compra Segura</h5>
                        <p>Atendimento personalizado antes da compra.</p>
                    </div>
                    <!-- SUPORTE -->
                    <div class="col-12 col-md-4">
                        <div class="fs-1">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                        <h5>Suporte</h5>
                        <p>Tire suas dúvidas sobre cuidados para o cabelo.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Accordion (seção invertida) -->
        <section class="container-fluid secao-invertida p-5">
            <div class="container">
                <div class="text-center mt-5" id="duvidas">
                    <!-- Titulo -->
                    <div>
                        <h2 class="mt-5 fw-bold">Perguntas Frequentes</h2>
                    </div>
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <!-- Pergunta 01 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    Vocês realizam entrega?
                                </button>
                            </h3>
                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Sim. Consulte nossa equipe pelo WhatsApp para verificar
                                    disponibilidade e valor.</div>
                            </div>
                        </div>
                        <!-- Pergunta 02 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                    aria-controls="flush-collapseTwo">
                                    Como escolher um corte?
                                </button>
                            </h3>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Nosso profissional pode recomendar excelentes cortes para
                                    você.</div>
                            </div>
                        </div>
                        <!-- Pergunta 03 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThree" aria-expanded="false"
                                    aria-controls="flush-collapseThree">
                                    Quais são as formas de pagamento?
                                </button>
                            </h3>
                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Entre em contato com a nossa equipe para consultar as formas
                                    de pagamento disponíveis para seu pedido.</div>
                            </div>
                        </div>
                        <!-- Pergunta 04 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#resposta4" aria-expanded="false" aria-controls="resposta4">
                                    Posso retirar meu produto na loja?
                                </button>
                            </h3>
                            <div id="resposta4" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Sim. A retirada poderá ser combinada previamente com nossa
                                    equipe.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Formulario -->
        <section class="pb-5" id="contatos">
            <div class="container mt-5 pt-2" id="contato">
                <!-- Titulo -->
                <div class="text-center">
                    <h2 class="mt-5 fw-bold">Entre em Contato</h2>
                    <p class="opacity-75">Envie sua dúvida para nossa equipe.</p>
                </div>

                <div class="row justify-content-center tec">
                    <div class="col-12 col-md-6">
                        <form action="cadastroContato.php" method="POST">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control" id="nome"
                                    placeholder="Entre com o seu nome completo" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="nome@email.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="tel" name="telefone" class="form-control" id="telefone"
                                    placeholder="(34) 99999-9999" required>
                            </div>

                            <label for="assunto" class="form-label">Dúvidas</label>
                            <select name="assunto" id="assunto" class="form-select">
                                <option value="Produto">Dúvida sobre produto</option>
                                <option value="Entrega">Entrega</option>
                                <option value="Pagamento">Pagamento</option>
                            </select>

                            <div class="mb-3 mt-3">
                                <label for="mensagem" class="form-label">Mensagem</label>
                                <textarea class="form-control" id="mensagem" name="mensagem" rows="3"
                                    required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-2"></i>
                                Enviar mensagem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chamada final (seção invertida) -->
        <section class="secao-invertida text-center py-5">
            <div class="container">
                <h2 class="fw-bold">Fale com a Barbearia Castro</h2>
                <p>Faça seu pedido ou acompanhe nossas novidades nas redes sociais.</p>
                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <!-- WHATSAPP (troque o número 5534999999999 pelo real) -->
                    <a href="https://wa.me/5534999999999?text=Ol%C3%A1%2C%20gostaria%20de%20conhecer%20os%20produtos%20da%20Barbearia%20Castro"
                        target="_blank" rel="noopener" class="btn btn-primary">
                        <i class="bi bi-whatsapp"></i>
                        WhatsApp
                    </a>
                    <!-- INSTAGRAM (troque "nomedoperfil" pelo perfil real) -->
                    <a href="https://www.instagram.com/nomedoperfil/" target="_blank" rel="noopener"
                        class="btn btn-outline-primary">
                        <i class="bi bi-instagram"></i>
                        Instagram
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer - Rodapé -->
    <footer class="pt-4">
        <div class="container">
            <div class="row">
                <div class="col col-md">
                    <h5><i class="bi bi-suit-club"></i> Barbearia Castro</h5>
                    <p>Cortes &amp; Produtos</p>
                </div>
                <div class="col col-md text-md-end">
                    <p><strong>Atendimento:</strong> Segunda a sábado</p>
                    <p>© 2026 Barbearia Castro - Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>