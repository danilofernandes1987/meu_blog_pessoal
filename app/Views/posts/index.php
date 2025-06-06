<?php
// app/Views/posts/index.php
// Este é o conteúdo que será inserido em $contentForLayout no layouts/main.php
?>

<h2><?php echo isset($contentTitle) ? htmlspecialchars($contentTitle) : 'Blog'; ?></h2>
<hr class="mb-4">

<?php if (!empty($posts)): ?>
    <div class="row row-cols-1 row-cols-md-2 g-4"> <?php // Grid para os cards 
                                                    ?>
        <?php foreach ($posts as $post): ?>
            <div class="col">
                <div class="card h-100"> <?php // h-100 para cards da mesma altura na linha 
                                            ?>
                    <?php if (!empty($post['featured_image'])): ?>
                        <a href="/posts/show/<?php echo htmlspecialchars($post['slug']); ?>">
                            <img src="/uploads/images/<?php echo htmlspecialchars($post['featured_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        </a>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="/posts/show/<?php echo htmlspecialchars($post['slug']); ?>" class="text-decoration-none text-body">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h5>
                        <p class="card-text flex-grow-1">
                            <?php echo htmlspecialchars($post['excerpt'] ?? ''); ?>...
                        </p>
                        <a href="/posts/show/<?php echo htmlspecialchars($post['slug']); ?>" class="btn btn-primary mt-auto align-self-start">Leia Mais</a>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">
                            Publicado em: <?php echo date('d/m/Y', strtotime($post['created_at'])); ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Navegação das páginas de posts" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php // Link "Anterior" 
                ?>
                <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="/posts?page=<?php echo $currentPage - 1; ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php // Links das Páginas 
                ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                        <a class="page-link" href="/posts?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php // Link "Próxima" 
                ?>
                <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="/posts?page=<?php echo $currentPage + 1; ?>" aria-label="Próxima">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        Nenhum post encontrado no momento. Volte em breve!
    </div>
<?php endif; ?>