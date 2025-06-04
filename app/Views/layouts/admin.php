<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin'; ?> - <?php echo htmlspecialchars($siteName ?? ''); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin-style.css"> </head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard"><?php echo htmlspecialchars($siteName ?? 'Painel Admin'); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/admin/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/posts">Posts</a> </li>
                    </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            Logado como: <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light" href="/auth/logout">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php echo $contentForLayout; // Conteúdo da view específica do admin aqui ?>
    </div>

    <footer class="text-center text-muted py-3 mt-auto bg-light">
        <p>&copy; <?php echo date('Y'); ?> Painel Administrativo</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>