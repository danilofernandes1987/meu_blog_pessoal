<?php
// app/Core/flash_helpers.php

if (!function_exists('setFlashMessage')) {
    /**
     * Define uma flash message na sessão.
     *
     * @param string $key Chave única para a mensagem (ex: 'post_feedback', 'user_error')
     * @param string $message A mensagem a ser exibida.
     * @param string $type Tipo da mensagem para estilização Bootstrap (success, danger, warning, info).
     */
    function setFlashMessage(string $key, string $message, string $type = 'success'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Garante que a sessão esteja iniciada
        }
        $_SESSION['flash_messages'][$key] = [
            'message' => $message,
            'type' => $type
        ];
    }
}

if (!function_exists('displayFlashMessage')) {
    /**
     * Exibe uma flash message (se existir) e a remove da sessão.
     *
     * @param string $key A chave da mensagem a ser exibida.
     * @return void
     */
    function displayFlashMessage(string $key): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Garante que a sessão esteja iniciada
        }

        if (isset($_SESSION['flash_messages'][$key])) {
            $flashMessage = $_SESSION['flash_messages'][$key];
            $message = htmlspecialchars($flashMessage['message']);
            $type = htmlspecialchars($flashMessage['type']);

            // Monta o alerta Bootstrap
            echo "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>";
            echo $message;
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";

            // Remove a mensagem da sessão para que não seja exibida novamente
            unset($_SESSION['flash_messages'][$key]);
        }
    }

    if (!function_exists('generateCsrfToken')) {
        /**
         * Gera e armazena um token CSRF na sessão, se não existir um.
         * @return string O token CSRF.
         */
        function generateCsrfToken(): string
        {
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Gera uma string aleatória segura
            }
            return $_SESSION['csrf_token'];
        }
    }

    if (!function_exists('validateCsrfToken')) {
        /**
         * Valida o token CSRF enviado com o que está na sessão.
         * @param string|null $submittedToken O token vindo do formulário ($_POST).
         * @return bool True se for válido, false caso contrário.
         */
        function validateCsrfToken(?string $submittedToken): bool
        {
            if (isset($_SESSION['csrf_token']) && $submittedToken && hash_equals($_SESSION['csrf_token'], $submittedToken)) {
                // O token é válido. Podemos removê-lo para que cada formulário tenha um novo (opcional, mas mais seguro).
                // unset($_SESSION['csrf_token']); // Se remover, precisa gerar um novo a cada requisição de formulário
                return true;
            }
            return false;
        }
    }

    if (!function_exists('csrfInput')) {
        /**
         * Gera o campo de input hidden para o token CSRF.
         * @return string O HTML do input.
         */
        function csrfInput(): string
        {
            return '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
        }
    }
}
