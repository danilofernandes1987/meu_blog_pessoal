<?php // app/Views/admin/posts/create.php 
?>

<h2><?php echo htmlspecialchars($contentTitle ?? 'Criar Novo Post'); ?></h2>
<hr>

<?php if (isset($errors['database'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($errors['database']); ?></div>
<?php endif; ?>

<form method="POST" action="/admin/posts/store">
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

    <button type="submit" class="btn btn-primary">Salvar Post</button>
    <a href="/admin/posts" class="btn btn-secondary">Cancelar</a>
</form>

<script src="https://cdn.tiny.cloud/1/5037jfqsjqwoqlp4q4lrwa8n0ehwfxypfz9crmb3fl5oiyo0/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#content', // Seleciona a textarea com id="content"
        plugins: 'code table lists link image media help wordcount autosave preview searchreplace visualblocks',
        toolbar: 'undo redo | formatselect | bold italic underline strikethrough | ' +
            'alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | lists table | ' +
            'link image media | searchreplace visualblocks | ' +
            'removeformat | code | preview | help',
        menubar: 'file edit view insert format tools table help',
        height: 400,
        // language: 'pt_BR', // Se quiser em português, pode ser necessário baixar um pacote de idioma ou verificar se a CDN suporta via config
        // Para fazer upload de imagens (requer configuração no lado do servidor também, mais complexo):
        // images_upload_url: '/admin/images/upload', // Exemplo de URL de upload
        // images_upload_base_path: '/media/', // Exemplo
        // automatic_uploads: true,
        // file_picker_types: 'image media',
    });
</script>