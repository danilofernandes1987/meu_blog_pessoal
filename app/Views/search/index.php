<?php // app/Views/search/index.php ?>

<h2><?php echo htmlspecialchars($contentTitle ?? 'Busca'); ?></h2>

<?php if (!empty($searchTerm)): ?>
    <p class="lead">
        Exibindo resultados para: <strong>"<?php echo htmlspecialchars($searchTerm); ?>"</strong>.
        <span class="text-muted">(<?php echo $totalResults; ?> post(s) encontrado(s))</span>
    </p>
    <hr>

    <?php if (!empty($results)): ?>
        <?php foreach ($results as $post): ?>
            <article class="mb-4">
                <h4>
                    <a href="/posts/show/<?php echo htmlspecialchars($post['slug']); ?>" class="text-decoration-none">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                </h4>
                <p class="text-muted small">Publicado em: <?php echo date('d/m/Y', strtotime($post['created_at'])); ?></p>
                <p><?php echo htmlspecialchars(strip_tags($post['excerpt'])); ?>...</p>
            </article>
        <?php endforeach; ?>

        <!-- Paginação para os resultados da busca -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Navegação dos resultados da busca" class="mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/search?q=<?php echo urlencode($searchTerm); ?>&page=<?php echo $currentPage - 1; ?>">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                            <a class="page-link" href="/search?q=<?php echo urlencode($searchTerm); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/search?q=<?php echo urlencode($searchTerm); ?>&page=<?php echo $currentPage + 1; ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-warning">Nenhum resultado encontrado para sua busca.</div>
    <?php endif; ?>

<?php else: ?>
    <p>Por favor, digite um termo na caixa de busca acima para começar.</p>
<?php endif; ?>
