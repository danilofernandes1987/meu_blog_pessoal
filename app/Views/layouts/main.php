<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // Lógica para Título e Descrição Dinâmicos
    $page_title = $post['meta_title'] ?? $pageTitle ?? $siteName ?? 'Danilo Silva';
    $page_description = $post['meta_description'] ?? $siteConfig['defaultMetaDescription'] ?? 'Site pessoal e blog de Danilo Fernandes da Silva.';
    ?>

    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">

    <!-- Em app/Views/layouts/main.php -->

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?php echo isset($post) ? 'article' : 'website'; ?>">
    <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <?php if (isset($post['featured_image'])): ?>
        <meta property="og:image" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/uploads/images/' . htmlspecialchars($post['featured_image']); ?>">
    <?php else: ?>
        <meta property="og:image" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/images/danilo.png'; ?>">
    <?php endif; ?>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <?php if (isset($post['featured_image'])): ?>
        <meta property="twitter:image" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/uploads/images/' . htmlspecialchars($post['featured_image']); ?>">
    <?php else: ?>
        <meta property="twitter:image" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/images/danilo.png'; ?>">
    <?php endif; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="/css/style.css">
    <script>
        function loadGoogleAnalytics() {
            const gaScript = document.createElement('script');
            gaScript.async = true;
            gaScript.src = 'https://www.googletagmanager.com/gtag/js?id=G-R3CK5WRW24';
            document.head.appendChild(gaScript);

            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', 'G-R3CK5WRW24');
            console.log("Google Analytics carregado após consentimento.");
        }
    </script>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <!-- Site Name como marca -->
            <a class="navbar-brand" href="/">
                <?php echo isset($siteName) ? htmlspecialchars($siteName) : 'Meu Blog Pessoal'; ?>
            </a>

            <!-- Botão de toggler (o "hambúrguer") -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Itens do menu dentro do collapse -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/curriculo">Currículo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/posts">Posts</a>
                    </li>
                </ul>
                <!-- Formulário de Busca com Ícone Interno -->
                <form class="col-12 col-lg-auto mb-3 mb-lg-0" role="search" action="/search" method="GET">
                    <div style="position: relative;">
                        <input type="search" name="q" class="form-control form-control-dark text-bg-dark" placeholder="Buscar posts..." aria-label="Search" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" style="padding-right: 2.5rem;">
                        <i class="bi bi-search" style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); pointer-events: none; color: #ced4da;"></i>
                    </div>
                </form>
            </div>
        </div>
    </nav>


    <main class="site-content container">
        <?php echo $contentForLayout; ?>
    </main>

    <!-- BANNER DE CONSENTIMENTO DE COOKIES -->
    <div id="cookie-consent-banner" class="cookie-consent-banner">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
            <p class="mb-2 mb-md-0">
                Este site utiliza cookies para garantir a melhor experiência de navegação e para análise de tráfego. Ao continuar, você concorda com o uso de cookies.
                <!-- No futuro, você pode criar uma página de Política de Privacidade e linkar aqui -->
                <!-- <a href="/politica-de-privacidade">Saiba mais</a> -->
            </p>
            <button id="accept-cookie-btn" class="btn btn-primary btn-sm">Aceitar</button>
        </div>
    </div>

    <footer class="site-footer bg-light text-center py-3 mt-auto">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo isset($myName) ? htmlspecialchars($myName) : 'Seu Nome'; ?>. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Prism.js JS Core - ADICIONADO -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <!-- Carrega automaticamente as linguagens mais comuns - ADICIONADO -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

    <!-- Nosso novo script de gerenciamento de cookies -->
    <script src="/js/cookie-consent.js"></script>
</body>

</html>