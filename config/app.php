<?php
// config/app.php
return [
    'siteName'         => $_ENV['APP_NAME'] ?? 'Meu Site Pessoal',
    'siteOwner'        => $_ENV['APP_OWNER'] ?? 'Proprietário Padrão',
    'defaultPageTitle' => 'Danilo Fernandes da Silva',
    'postsPerPage'     => (int) ($_ENV['POSTS_PER_PAGE'] ?? 6),
    'shortSiteOwner'   => 'Danilo Silva',
    'contactEmail'     => $_ENV['CONTACT_EMAIL'] ?? 'email@padrao.com',
    // Adicione outras configurações globais conforme necessário
    // 'metaDescription' => 'Um site pessoal sobre minhas aventuras em PHP.',
    // 'analyticsId'     => 'UA-XXXXX-Y',
    'mail' => [
        'host'       => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com',
        'port'       => (int) ($_ENV['MAIL_PORT'] ?? 587),
        'smtp_auth'  => true,
        'smtp_secure'=> 'tls', // ou 'ssl' para a porta 465
        'username'   => $_ENV['MAIL_USERNAME'] ?? '',
        'password'   => $_ENV['MAIL_PASSWORD'] ?? '',
        'from_email' => $_ENV['MAIL_FROM_ADDRESS'] ?? '',
        'from_name'  => $_ENV['APP_NAME'] ?? 'Meu Site Pessoal', // O nome do remetente
    ]
];
?>
