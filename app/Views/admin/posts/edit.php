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

<form method="POST" action="/admin/posts/update/<?php echo $post['id']; ?>"> <?php // Rota para o método update(), passando o ID 
                                                                                ?>
    <div class="mb-3">
        <label for="title" class="form-label">Título do Post</label>
        <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo htmlspecialchars($titleValue); ?>" required>
        <?php if (isset($errors['title'])): ?>
            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['title']); ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="slug" class="form-label">Slug (URL amigável)</label>
        <input type="text" class="form-control <?php echo isset($errors['slug']) ? 'is-invalid' : ''; ?>" id="slug" name="slug" value="<?php echo htmlspecialchars($slugValue); ?>" placeholder="ex: meu-post-editado" required>
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

<?php /* O script de slug pode ser o mesmo do create.php, ou você pode decidir se o slug deve ser editável ou gerado apenas na criação. */ ?>

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
