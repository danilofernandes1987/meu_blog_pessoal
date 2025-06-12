<?php
// app/Core/path_helpers.php

if (!function_exists('base_path')) {
    /**
     * Retorna o caminho absoluto para a raiz do projeto.
     * @param string $path Caminho adicional a ser anexado.
     * @return string
     */
    function base_path(string $path = ''): string
    {
        $basePath = dirname(__DIR__, 2);
        return $basePath . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}

if (!function_exists('public_path')) {
    /**
     * Retorna o caminho absoluto para a pasta pública, usando a constante PUBLIC_PATH.
     * @param string $path Caminho adicional a ser anexado.
     * @return string
     */
    function public_path(string $path = ''): string
    {
        // Usa a constante definida em public_html/index.php
        return PUBLIC_PATH . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}
