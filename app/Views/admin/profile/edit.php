<?php // app/Views/admin/profile/edit.php 
?>

<h2><?php echo htmlspecialchars($contentTitle ?? 'Meu Perfil'); ?></h2>
<p>Aqui você pode atualizar suas informações pessoais e de segurança.</p>
<hr>

<?php displayFlashMessage('profile_feedback'); ?>

<div class="row">
    <!-- Coluna da Esquerda: Foto do Perfil -->
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header">
                Foto do Perfil
            </div>
            <div class="card-body text-center">
                <?php $photoSrc = (!empty($user['photo'])) ? '/uploads/images/' . htmlspecialchars($user['photo']) : '/images/placeholder-profile.png'; ?>
                <img src="<?php echo $photoSrc; ?>" alt="Foto atual" class="img-thumbnail rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                <p class="text-muted">Envie um novo arquivo no formulário ao lado para substituir a foto atual.</p>
            </div>
        </div>
    </div>

    <!-- Coluna da Direita: Formulários -->
    <div class="col-lg-8">
        <form method="POST" action="/admin/profile/update" enctype="multipart/form-data">
            <?php echo csrfInput(); ?>

            <div class="card">
                <div class="card-header">
                    Informações Gerais
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($old_input['name'] ?? $user['name'] ?? ''); ?>" required>
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['name']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Alterar Foto do Perfil</label>
                        <input class="form-control <?php echo isset($errors['photo']) ? 'is-invalid' : ''; ?>" type="file" id="photo" name="photo">
                        <?php if (isset($errors['photo'])): ?>
                            <div class="invalid-feedback d-block"><?php echo htmlspecialchars($errors['photo']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    Alterar Senha
                </div>
                <div class="card-body">
                    <p class="text-muted small">Deixe os campos abaixo em branco se não desejar alterar a senha.</p>
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Senha Antiga</label>
                        <input type="password" class="form-control <?php echo isset($errors['old_password']) ? 'is-invalid' : ''; ?>" id="old_password" name="old_password" autocomplete="off">
                        <?php if (isset($errors['old_password'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['old_password']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control <?php echo isset($errors['new_password']) ? 'is-invalid' : ''; ?>" id="new_password" name="new_password" autocomplete="new-password">
                            <?php if (isset($errors['new_password'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['new_password']); ?></div>
                            <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirm" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control <?php echo isset($errors['new_password_confirm']) ? 'is-invalid' : ''; ?>" id="new_password_confirm" name="new_password_confirm" autocomplete="new-password">
                        <?php if (isset($errors['new_password_confirm'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['new_password_confirm']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Salvar Alterações</button>
        </form>
    </div>
</div>