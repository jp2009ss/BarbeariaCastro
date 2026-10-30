<?php
//Inicia a sessão PHP
session_start();

//Verifica se o usuário já esta logado
if (isset($_SESSION["usuario_id"])) {
    header("Location: painel.php");
    exit;
}

//Verifica se houve erro na tentativa de login
$erro = isset($_GET["erro"]);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Barbearia Castro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow-sm w-100" style="max-width: 420px;">
            <div class="card-body p-4">
                <h2 class="mb-3">Acesso Administrativo</h2>

                <!-- Mostra erro se o login estiver incorreto -->
                <?php if ($erro): ?>
                    <div class="alert alert-danger">E-mail ou senha inválidos.</div>
                <?php endif; ?>

                <form action="autenticar.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required
                            autocomplete="username">
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" id="senha" name="senha" class="form-control" required
                            autocomplete="current-password">
                    </div>

                    <!-- Botão Entrar -->
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>

                <!-- Voltar ao site -->
                <a href="index.php" class="d-block mt-3">Voltar ao site</a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>