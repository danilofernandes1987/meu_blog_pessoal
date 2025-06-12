<?php
// public/index.php

// Inicia a sessão ANTES de qualquer outra coisa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Define a constante PUBLIC_PATH com o caminho absoluto para este diretório.
 * __DIR__ retorna o caminho do diretório onde este arquivo (index.php) está.
 * Esta é a forma mais confiável de saber onde a pasta pública realmente está.
 */
define('PUBLIC_PATH', __DIR__);

// Inclui o autoloader do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// --- CARREGA AS VARIÁVEIS DE AMBIENTE DO .ENV ---
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Aponta para a raiz do projeto
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // Se o arquivo .env não for encontrado, o ideal é parar a aplicação
    // com uma mensagem clara, especialmente em desenvolvimento.
    die("Não foi possível carregar o arquivo .env. Por favor, crie um a partir do .env.example. Erro: " . $e->getMessage());
}
// --- FIM DO CARREGAMENTO DO .ENV ---

// Importa a classe Router
use App\Core\Router;

// Define um fuso horário padrão (boa prática, se ainda não estiver configurado no php.ini)
// date_default_timezone_set('America/Sao_Paulo'); // Ajuste para seu fuso

// Instancia o Router. O construtor do Router fará todo o trabalho.
new Router();
