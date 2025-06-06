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
    <header class="site-header bg-dark text-white py-3 mb-4">
        <div class="container">
            <h1 class="text-center"><?php echo isset($siteName) ? htmlspecialchars($siteName) : 'Meu Blog Pessoal'; ?></h1>
            <nav class="nav">
                <a class="nav-link text-white" href="/">Início</a>
                <a class="nav-link text-white" href="/home/curriculo">Currículo</a>
                <a class="nav-link text-white" href="/contact">Contato</a>
                <a class="nav-link text-white" href="/posts">Posts</a>
                <!-- <a class="nav-link text-white" href="/contato">Contato</a> -->
            </nav>
        </div>
    </header>

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