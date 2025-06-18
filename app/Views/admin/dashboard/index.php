<?php // app/Views/admin/dashboard/index.php ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><?php echo htmlspecialchars($contentTitle ?? 'Painel'); ?></h2>
    <a href="/admin/posts/create" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill me-2"></i>Criar Novo Post
    </a>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card text-bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Posts</h5>
                        <p class="fs-1 fw-bold"><?php echo $totalPosts ?? 0; ?></p>
                    </div>
                    <i class="bi bi-file-earmark-text-fill fs-1 opacity-50"></i>
                </div>
                <a href="/admin/posts" class="stretched-link text-white">Ver todos</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card text-bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Experiências</h5>
                        <p class="fs-1 fw-bold"><?php echo $totalExperiences ?? 0; ?></p>
                    </div>
                    <i class="bi bi-briefcase-fill fs-1 opacity-50"></i>
                </div>
                <a href="/admin/curriculo" class="stretched-link text-white">Gerenciar</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card text-bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Formações</h5>
                        <p class="fs-1 fw-bold"><?php echo $totalEducations ?? 0; ?></p>
                    </div>
                    <i class="bi bi-mortarboard-fill fs-1 opacity-50"></i>
                </div>
                <a href="/admin/curriculo" class="stretched-link text-white">Gerenciar</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card text-bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Cursos</h5>
                        <p class="fs-1 fw-bold"><?php echo $totalCourses ?? 0; ?></p>
                    </div>
                    <i class="bi bi-patch-check-fill fs-1 opacity-50"></i>
                </div>
                <a href="/admin/curriculo" class="stretched-link text-white">Gerenciar</a>
            </div>
        </div>
    </div>
</div>

<!-- Atividade Recente -->
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-clock-history me-2"></i>Atividade Recente (Últimos Posts)</h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($recentPosts)): ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($recentPosts as $post): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="/admin/posts/edit/<?php echo $post['id']; ?>" class="fw-bold text-decoration-none"><?php echo htmlspecialchars($post['title']); ?></a>
                            <small class="d-block text-muted">Criado em: <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></small>
                        </div>
                        <a href="/posts/show/<?php echo htmlspecialchars($post['slug']); ?>" class="btn btn-outline-secondary btn-sm" target="_blank">Ver</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="p-3">Nenhuma atividade recente para mostrar.</p>
        <?php endif; ?>
    </div>
</div>
