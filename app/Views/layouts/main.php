<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Meu Site Pessoal'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="/css/style.css">
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
                        <a class="nav-link" href="/home/curriculo">Currículo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/posts">Posts</a>
                    </li>
                    <!-- Se não for mais necessário, pode remover o link comentado abaixo:
        <li class="nav-item">
          <a class="nav-link" href="/contato">Contato</a>
        </li>
        -->
                </ul>
            </div>
        </div>
    </nav>


    <main class="site-content container">
        <?php echo $contentForLayout; ?>
    </main>

    <footer class="site-footer bg-light text-center py-3 mt-auto">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo isset($myName) ? htmlspecialchars($myName) : 'Seu Nome'; ?>. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>