<?php
// app/Views/posts/show.php
// Conteúdo para $contentForLayout
?>

<?php if (!empty($post['featured_image'])): ?>
    <!-- Container com altura máxima para a imagem de destaque -->
    <div class="post-featured-image-container">
        <img src="/uploads/images/<?php echo htmlspecialchars($post['featured_image']); ?>"
            class="img-fluid"
            alt="<?php echo htmlspecialchars($post['title']); ?>"
            style="width: 100%; height: 100%; object-fit: cover;">
    </div>
<?php endif; ?>

<article class="blog-post">
    <h2 class="display-5 link-body-emphasis mb-1"><?php echo isset($contentTitle) ? htmlspecialchars($contentTitle) : 'Título do Post'; ?></h2>
    <p class="blog-post-meta text-muted">
        Publicado em: <?php echo date('d/m/Y H:i', strtotime($post['published_at'])); ?>
        <?php if (isset($post['updated_at']) && $post['updated_at'] !== $post['published_at']): ?>
            | Atualizado em: <?php echo date('d/m/Y H:i', strtotime($post['updated_at'])); ?>
        <?php endif; ?>
    </p>
    <hr>

    <div class="post-content">
        <?php
        // nl2br() converte quebras de linha (\n) em tags <br />.
        // Se o seu conteúdo já for HTML (de um editor WYSIWYG, por exemplo),
        // você não precisaria de htmlspecialchars() aqui, mas precisaria sanitizar na entrada.
        // Para conteúdo que pode ser texto simples com quebras de linha:
        echo $post['content'] ?? 'Conteúdo do post não disponível.'; // Exibe o HTML diretamente
        ?>
    </div>
</article>

<hr class="my-4">
<a href="/posts" class="btn btn-outline-secondary">
    &laquo; Voltar para a lista de posts
</a>