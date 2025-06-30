<?php // app/Views/admin/posts/create.php 
?>

<h2><?php echo htmlspecialchars($contentTitle ?? 'Criar Novo Post'); ?></h2>
<hr>

<?php if (isset($errors['database'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($errors['database']); ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" action="/admin/posts/store">
    <?= csrfInput(); ?>
    <div class="mb-3">
        <label for="featured_image" class="form-label">Imagem de Destaque</label>
        <input class="form-control" type="file" id="featured_image" name="featured_image">
        <div class="form-text">Envie uma imagem (JPG, PNG, GIF, WebP) para ser a capa do seu post.</div>
        <?php if (isset($errors['featured_image'])): ?>
            <div class="invalid-feedback d-block"><?php echo htmlspecialchars($errors['featured_image']); ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Título do Post</label>
        <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo htmlspecialchars($old_input['title'] ?? ''); ?>" required>
        <?php if (isset($errors['title'])): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['title']); ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="slug" class="form-label">Slug (URL amigável)</label>
        <input type="text" class="form-control <?php echo isset($errors['slug']) ? 'is-invalid' : ''; ?>" id="slug" name="slug" value="<?php echo htmlspecialchars($old_input['slug'] ?? ''); ?>" placeholder="ex: meu-novo-post-incrivel" required>
        <div class="form-text">Use apenas letras minúsculas, números e hífens.</div>
        <?php if (isset($errors['slug'])): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['slug']); ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="content" class="form-label">Conteúdo</label>
        <textarea class="form-control <?php echo isset($errors['content']) ? 'is-invalid' : ''; ?>" id="content" name="content" rows="15"><?php echo htmlspecialchars($old_input['content'] ?? ''); ?></textarea>
        <?php if (isset($errors['content'])): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['content']); ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select <?php echo isset($errors['status']) ? 'is-invalid' : ''; ?>" id="status" name="status">
            <option value="published" <?php echo (isset($old_input['status']) && $old_input['status'] === 'published') ? 'selected' : ''; ?>>Publicado</option>
            <option value="draft" <?php echo (isset($old_input['status']) && $old_input['status'] === 'draft') ? 'selected' : ((!isset($old_input['status'])) ? 'selected' : ''); ?>>Rascunho</option>
        </select>
        <?php if (isset($errors['status'])): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['status']); ?></div>
        <?php endif; ?>
    </div>

    <hr class="my-4">
    <h4>Otimização para SEO</h4>
    <div class="mb-3">
        <label for="meta_title" class="form-label">Meta Título</label>
        <input type="text" class="form-control" id="meta_title" name="meta_title" value="<?php echo htmlspecialchars($post['meta_title'] ?? ''); ?>">
        <div class="form-text">O título que aparecerá na aba do navegador e nos resultados do Google (recomendado: 50-60 caracteres). Se deixado em branco, o título principal do post será usado.</div>
    </div>
    <div class="mb-3">
        <label for="meta_description" class="form-label">Meta Descrição</label>
        <textarea class="form-control" id="meta_description" name="meta_description" rows="3"><?php echo htmlspecialchars($post['meta_description'] ?? ''); ?></textarea>
        <div class="form-text">Uma breve descrição do post para os resultados de busca (recomendado: 150-160 caracteres).</div>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Post</button>
    <a href="/admin/posts" class="btn btn-secondary">Cancelar</a>
</form>