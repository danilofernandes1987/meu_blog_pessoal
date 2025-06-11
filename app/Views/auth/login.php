<?php // app/Views/auth/login.php 
?>
<main class="form-signin-container">
    <div class="card form-signin-card p-4">

        <!-- Logo + Título -->
        <div class="text-center">
            <!-- Troque o src pela logo real do seu site -->
            <img src="/images/logo.png" alt="Logo" class="logo">
            <h1 class="h4 mb-4">Login Administrativo</h1>
        </div>

        <form method="POST" action="/auth/login">
            <?= csrfInput(); ?>

            <!-- Mensagem de erro -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Usuário -->
            <div class="form-floating mb-3">
                <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="username"
                    placeholder="Usuário"
                    value="<?= htmlspecialchars($username_attempt ?? '') ?>"
                    required
                    autofocus>
                <label for="username">Usuário</label>
            </div>

            <!-- Senha -->
            <div class="form-floating mb-3">
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    placeholder="Senha"
                    required>
                <label for="password">Senha</label>
            </div>

            <!-- Botão Entrar -->
            <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">
                Entrar
            </button>

            <!-- Link “Esqueceu a senha?” -->
            <div class="text-center">
                <a href="/auth/forgot-password" class="small">
                    Esqueceu a senha?
                </a>
            </div>

            <!-- Rodapé -->
            <p class="mt-4 mb-0 text-center text-muted">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($siteName ?? '') ?>
            </p>
        </form>
    </div>
</main>