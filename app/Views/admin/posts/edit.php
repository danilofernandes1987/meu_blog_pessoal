<?php // app/Views/admin/posts/edit.php 
?>

<h2><?php echo htmlspecialchars($contentTitle ?? 'Editar Post'); ?></h2>
<hr>

<?php /* Exibir erros de validação gerais */ ?>
<?php if (isset($errors['database'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($errors['database']); ?></div>
<?php endif; ?>

<?php
// Prioriza old_input da sessão (se houve erro de validação), senão usa dados do post do banco
$titleValue = $old_input['title'] ?? $post['title'] ?? '';
$slugValue = $old_input['slug'] ?? $post['slug'] ?? '';
$contentValue = $old_input['content'] ?? $post['content'] ?? '';
$statusValue = $old_input['status'] ?? $post['status'] ?? 'draft';
?>

<form method="POST" action="/admin/posts/update/<?php echo $post['id']; ?>" enctype="multipart/form-data">

    <?= csrfInput(); ?>
    <div class="mb-3">
        <label for="featured_image" class="form-label">Imagem de Destaque</label>
        <?php if (!empty($post['featured_image'])): ?>
            <div class="mb-2">
                <p>Imagem Atual:</p>
                <img src="/uploads/images/<?php echo htmlspecialchars($post['featured_image']); ?>" alt="Imagem de destaque atual" class="img-thumbnail" style="max-width: 200px;">
                <p class="form-text mt-1">Envie um novo arquivo abaixo para substituir a imagem atual.</p>
            </div>
        <?php endif; ?>
        <input class="form-control" type="file" id="featured_image" name="featured_image">
        <?php if (isset($errors['featured_image'])): ?>
            <div class="invalid-feedback d-block"><?php echo htmlspecialchars($errors['featured_image']); ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Título do Post</label>
        <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo htmlspecialchars($titleValue); ?>">
        <?php if (isset($errors['title'])): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['title']); ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="slug" class="form-label">Slug (URL amigável)</label>
        <input type="text" class="form-control <?php echo isset($errors['slug']) ? 'is-invalid' : ''; ?>" id="slug" name="slug" value="<?php echo htmlspecialchars($slugValue); ?>" placeholder="ex: meu-post-editado">
        <div class="form-text">Use apenas letras minúsculas, números e hífens. Ex: "meu-post-legal".</div>
        <?php if (isset($errors['slug'])): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['slug']); ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="content" class="form-label">Conteúdo</label>
        <textarea id="content" class="form-control <?php echo isset($errors['content']) ? 'is-invalid' : ''; ?>" id="content" name="content" rows="10" required><?php echo htmlspecialchars($contentValue); ?></textarea>
        <?php if (isset($errors['content'])): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['content']); ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select <?php echo isset($errors['status']) ? 'is-invalid' : ''; ?>" id="status" name="status">
            <option value="published" <?php echo ($statusValue === 'published') ? 'selected' : ''; ?>>Publicado</option>
            <option value="draft" <?php echo ($statusValue === 'draft') ? 'selected' : ''; ?>>Rascunho</option>
        </select>
        <?php if (isset($errors['status'])): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['status']); ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="/admin/posts" class="btn btn-secondary">Cancelar</a>
</form>