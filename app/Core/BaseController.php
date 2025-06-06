<?php
// app/Core/BaseController.php
namespace App\Core;

class BaseController
{

    protected array $siteConfig = [];

    public function __construct()
    {
        $configFile = __DIR__ . '/../../config/app.php';

        if (file_exists($configFile)) {
            $this->siteConfig = require $configFile;
        }
    }

    /**
     * Valida se a requisição é do tipo POST e se o token CSRF é válido.
     * Encerra a execução com uma mensagem de erro se a validação falhar.
     */
    protected function validatePostRequest(): void
    {
        // 1. Verifica se o método da requisição é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Se não for POST, encerra a execução.
            // Em um sistema mais complexo, poderia lançar uma exceção de "Método Não Permitido".
            // Para nosso caso, uma mensagem simples é suficiente.
            http_response_code(405); // 405 Method Not Allowed
            die('Erro: Esta ação só pode ser acessada via POST.');
        }

        // 2. Valida o token CSRF
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            // Se o token for inválido, encerra a execução.
            // setFlashMessage('global_error', 'Erro de validação de segurança. Por favor, tente novamente.', 'danger');
            // header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/')); // Redireciona para a página anterior
            // exit;
            // Por enquanto, uma mensagem de erro direta é mais simples de implementar e depurar.
            http_response_code(403); // 403 Forbidden
            die('Erro de validação de segurança (CSRF). A requisição foi bloqueada.');
        }
    }

    protected function view(string $viewName, array $data = [], string $layoutName = 'layouts.main'): void
    {
        // Dados padrão do site que estarão sempre disponíveis no layout e na view
        $baseData = [
            'siteName'          => $this->siteConfig['siteName'] ?? 'Site Padrão',
            'myName'            => $this->siteConfig['siteOwner'] ?? 'Proprietário Padrão',
            'pageTitle'         => $this->siteConfig['defaultPageTitle'] ?? 'Título Padrão',
            'shortSiteOwner'    => $this->siteConfig['shortSiteOwner'] ?? 'Nome Padrão',
        ];

        // Mescla os dados base com os dados específicos da página.
        // Dados específicos da página ($data) sobrescrevem os dados base se houver chaves iguais.
        $viewData = array_merge($baseData, $data);

        $viewPath = str_replace('.', '/', $viewName);
        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            extract($viewData); // Extrai $siteName, $myName, $pageTitle e os dados de $data
            ob_start();
            require $viewFile;
            $contentForLayout = ob_get_clean();

            $layoutPath = str_replace('.', '/', $layoutName);
            $layoutFile = __DIR__ . '/../Views/' . $layoutPath . '.php';

            if (file_exists($layoutFile)) {
                // As variáveis extraídas de $viewData já estão disponíveis para o layout
                require $layoutFile;
            } else {
                echo "Erro: Arquivo de Layout '$layoutName' não encontrado em '$layoutFile'";
            }
        } else {
            echo "Erro: View '$viewName' não encontrada em '$viewFile'";
        }
    }
}
