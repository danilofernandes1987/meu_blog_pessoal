<?php
// app/Core/Router.php
namespace App\Core;

use App\Controllers\ErrorController; // Certifique-se que ErrorController está sendo importado

class Router
{
    protected string $currentControllerName = 'HomeController'; // Nome base default
    protected string $currentMethodName = 'index';    // Método default
    protected array $params = [];                    // Parâmetros default
    protected string $currentControllerNamespace = "App\\Controllers\\"; // Namespace default

    public function __construct()
    {
        $urlParts = $this->parseUrl(); // Ex: para /auth/login -> ['auth', 'login']
        // Ex: para /admin/dashboard/index -> ['admin', 'dashboard', 'index']
        // Ex: para / -> ['']

        // 1. Verifica se é uma rota administrativa e define o namespace e o controller base
        if (!empty($urlParts[0]) && strtolower($urlParts[0]) === 'admin') {
            $this->currentControllerNamespace = "App\\Controllers\\Admin\\";
            unset($urlParts[0]); // Remove 'admin'
            $urlParts = array_values($urlParts); // Re-indexa: agora $urlParts[0] é o controller admin

            // Controller default para a área admin é 'DashboardController'
            $this->currentControllerName = !empty($urlParts[0]) ? ucfirst(strtolower($urlParts[0])) . 'Controller' : 'DashboardController';
            if (!empty($urlParts[0])) {
                unset($urlParts[0]); // Remove o nome do controller dos urlParts
            }
        } else {
            // Rotas públicas
            $this->currentControllerNamespace = "App\\Controllers\\";
            // Controller default para área pública é 'HomeController'
            // Se $urlParts[0] for uma string vazia (raiz do site), !empty será false.
            $this->currentControllerName = (!empty($urlParts[0])) ? ucfirst(strtolower($urlParts[0])) . 'Controller' : 'HomeController';
            if (!empty($urlParts[0])) { // Só faz unset se realmente havia algo em $urlParts[0]
                unset($urlParts[0]);
            }
        }

        $urlParts = array_values($urlParts); // Re-indexa após remover o controller

        // 2. Determinar o Método
        if (!empty($urlParts[0])) { // Agora $urlParts[0] é o nome do método, se existir
            $this->currentMethodName = $urlParts[0];
            unset($urlParts[0]);
        } // Senão, $this->currentMethodName permanece 'index' (o default)

        // 3. Pegar os Parâmetros restantes
        $this->params = $urlParts ? array_values($urlParts) : []; // Re-indexa para os parâmetros

        // Monta o nome completo da classe (Fully Qualified Class Name)
        $fqcnController = $this->currentControllerNamespace . $this->currentControllerName;

        // Verificação de existência do arquivo do controller (para debug e evitar erros fatais)
        // Constrói o caminho esperado do arquivo baseado no FQCN
        $relativePath = str_replace(['App\\', '\\'], ['', '/'], $fqcnController) . '.php'; // Ex: Controllers/Admin/DashboardController.php
        $expectedControllerPath = __DIR__ . '/../../app/' . $relativePath;


        if (!file_exists($expectedControllerPath)) {
            $this->trigger404("Arquivo do Controller '" . $this->currentControllerName . "' não encontrado em '" . $expectedControllerPath . "'. Verifique o nome e o namespace.");
            return; // Para a execução
        }

        // 4. Despachar a rota
        $this->dispatchRoute($fqcnController, $this->currentMethodName, $this->params);
    }

    /**
     * Analisa a URL a partir de $_GET['url'].
     * @return array As partes da URL.
     */
    protected function parseUrl(): array
    {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $parts = explode('/', $url);
        // Se a URL for a raiz "", explode retornará ['']. Isso é tratado na lógica do construtor.
        return $parts;
    }

    /**
     * Instancia o controller e chama o método com os parâmetros.
     */
    protected function dispatchRoute(string $controllerClass, string $method, array $params): void
    {
        if (!class_exists($controllerClass)) {
            $this->trigger404("Classe do Controller '" . $controllerClass . "' não encontrada. Verifique o namespace e se o autoload do Composer está atualizado.");
            return;
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $method)) {
            $this->trigger404("Método '" . $method . "' não existe na classe do controller '" . $controllerClass . "'.");
            return;
        }

        call_user_func_array([$controllerInstance, $method], $params);
    }

    /**
     * Dispara o ErrorController para exibir uma página 404.
     */
    protected function trigger404(string $message = "Recurso não encontrado."): void
    {
        // Adicionando o namespace completo para ErrorController para clareza
        $errorController = new \App\Controllers\ErrorController();
        $errorController->show404($message);
        exit;
    }
}
