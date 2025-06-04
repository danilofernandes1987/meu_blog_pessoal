<?php
// config/database.php
return [
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'meu_blog_db',
    'username'  => 'danilo',
    'password'  => 'myuserpass',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
    'options'   => [
        // Opções do PDO:
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lançar exceções em erros
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retornar resultados como arrays associativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Usar prepared statements nativos
    ],
];
?>