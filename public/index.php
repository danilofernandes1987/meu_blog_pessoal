<?php
// public/index.php

// Inclui o autoloader do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Importa a classe Router
use App\Core\Router;

// Define um fuso horário padrão (boa prática, se ainda não estiver configurado no php.ini)
// date_default_timezone_set('America/Sao_Paulo'); // Ajuste para seu fuso

// Instancia o Router. O construtor do Router fará todo o trabalho.
new Router();

?>