<?php // app/Views/admin/posts/index.php 
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><?php echo htmlspecialchars($contentTitle ?? 'Gerenciar Posts'); ?></h2>
    <a href="/admin/posts/create" class="btn btn-success">Criar Novo Post</a>
</div>

<?php if (!empty($posts)): ?>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Criado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo $post['id']; ?></td>
                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                    <td><?php echo htmlspecialchars($post['slug']); ?></td>
                    <td>
                        <span class="badge bg-<?php echo ($post['status'] === 'published') ? 'success' : 'secondary'; ?>">
                            <?php echo htmlspecialchars(ucfirst($post['status'])); ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></td>
                    <td>

                        <!-- Ver -->
                        <a href="/posts/show/<?php echo htmlspecialchars($post['slug']); ?>"
                            class="btn btn-info btn-sm icon-btn"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Ver Post (Público)" target="_blank">
                            <i class="bi bi-eye"></i>
                        </a>

                        <!-- Editar -->
                        <a href="/admin/posts/edit/<?php echo $post['id']; ?>"
                            class="btn btn-primary btn-sm icon-btn"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <!-- Excluir -->
                        <a href="/admin/posts/delete/<?php echo $post['id']; ?>"
                            class="btn btn-danger btn-sm icon-btn"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Excluir"
                            onclick="return confirm('Tem certeza que deseja excluir este post?');">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>


        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">
        Nenhum post encontrado. <a href="/admin/posts/create">Crie o primeiro!</a>
    </div>
<?php endif; ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>
