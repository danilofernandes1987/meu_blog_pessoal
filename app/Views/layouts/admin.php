<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin'; ?> - <?php echo htmlspecialchars($siteName ?? ''); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <!-- Brand / Logo -->
            <a class="navbar-brand" href="/admin/dashboard">
                <?= htmlspecialchars($shortSiteOwner ?? 'Painel Admin') ?>
            </a>

            <!-- Toggler / Hamburger -->
            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#adminNavbar"
                aria-controls="adminNavbar"
                aria-expanded="false"
                aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Itens do menu -->
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] === '/admin/dashboard' || $_SERVER['REQUEST_URI'] === '/admin/dashboard/') ? 'active' : '' ?>"
                            href="/admin/dashboard">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/posts') === 0) ? 'active' : '' ?>"
                            href="/admin/posts">
                            Posts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank"
                            href="/">
                            Site Principal
                        </a>
                    </li>
                </ul>

                <!-- Dropdown de usuário -->
                <?php $photoSrc = (!empty($_SESSION['admin_photo'])) ? '/uploads/images/' . htmlspecialchars($_SESSION['admin_photo']) : '/images/placeholder-profile.png'; ?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link p-0" href="#" role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="<?= $photoSrc ?>"
                                alt="User Image"
                                class="img-circle elevation-2 user-photo">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2">
                            <!-- Cabeçalho com o nome -->
                            <li class="dropdown-header text-center">
                                <?= htmlspecialchars($_SESSION['admin_name']) ?>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <!-- Rodapé com botões -->
                            <li>
                                <div class="d-flex justify-content-between px-2">
                                    <a href="/admin/profile" class="btn btn-sm btn-outline-primary">
                                        Perfil
                                    </a>
                                    <a href="/auth/logout" class="btn btn-sm btn-outline-danger">
                                        Sair
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php
        // Exibe a flash message se existir para a chave 'post_feedback'
        // ou qualquer outra chave que você venha a usar.
        displayFlashMessage('post_feedback');
        // Você pode adicionar chamadas para displayFlashMessage com outras chaves aqui se precisar
        // displayFlashMessage('user_feedback');
        // displayFlashMessage('general_error');
        ?>

        <?php echo $contentForLayout; // Conteúdo da view específica do admin aqui 
        ?>
    </div>

    <footer class="site-footer bg-light text-center py-3 mt-auto">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Painel Administrativo</p>
        </div>
    </footer>
    <!-- Carregamento dos Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Carrega a biblioteca do TinyMCE da CDN -->
    <script src="https://cdn.tiny.cloud/1/5037jfqsjqwoqlp4q4lrwa8n0ehwfxypfz9crmb3fl5oiyo0/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="/js/admin_scripts.js"></script>
</body>

</html>