<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Autenticação'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; align-items: center; padding-top: 40px; padding-bottom: 40px; background-color: #f5f5f5; min-height: 100vh; }
        .form-signin { width: 100%; max-width: 330px; padding: 15px; margin: auto; }
    </style>
</head>
<body class="text-center">
    <?php echo $contentForLayout; // Conteúdo da view específica (login.php) ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>