<?php // app/Views/auth/login.php 
?>
<main class="form-signin">
    <form method="POST" action="/auth/login"> <?php // Action aponta para a rota que processará o login 
                                                ?>
        <!-- <img class="mb-4" src="/images/logo-placeholder.png" alt="Logo" width="72" height="57"> <?php // Coloque um logo se tiver, ou remova 
                                                                                                        ?> -->
        <h1 class="h3 mb-3 fw-normal">Login Administrativo</h1>
        <?= csrfInput(); ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="form-floating">
            <input type="text" class="form-control" id="username" name="username" placeholder="Usuário" value="<?php echo htmlspecialchars($username_attempt ?? ''); ?>" required autofocus>
            <label for="username">Usuário</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
            <label for="password">Senha</label>
        </div>

        <?php /* Se quiser um "Lembrar-me", precisaria de mais lógica e cookies
        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Lembrar-me
            </label>
        </div>
        */ ?>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>
        <p class="mt-5 mb-3 text-muted">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteName ?? ''); ?></p>
    </form>
</main>