<?php
// app/Core/Router.php
namespace App\Core;

use App\Controllers\ErrorController; // Para usar nosso ErrorController

class Router {
    // Propriedades para armazenar o controller, método e parâmetros resolvidos
    protected string $currentControllerName = 'HomeController'; // Nome base, sem namespace ou sufixo "Controller"
    protected string $currentMethodName = 'index';
    protected array $params = [];

    /**
     * O construtor agora analisa a URL e despacha a rota.
     */
    public function __construct() {
        $urlParts = $this->parseUrl();

        // 1. Determinar o Controller
        if (!empty($urlParts[0])) {
            $controllerCandidateBaseName = ucfirst(strtolower($urlParts[0]));
            $controllerCandidateFullName = "App\\Controllers\\" . $controllerCandidateBaseName . 'Controller';

            // Verifica se o arquivo do controller existe para evitar erros fatais com class_exists em classes não encontradas pelo autoloader.
            // O autoloader PSR-4 espera que App\Controllers\NomeController esteja em app/Controllers/NomeController.php
            if (file_exists(__DIR__ . '/../Controllers/' . $controllerCandidateBaseName . 'Controller' . '.php')) {
                $this->currentControllerName = $controllerCandidateBaseName . 'Controller'; // Armazena com sufixo Controller
                unset($urlParts[0]);
            } else {
                // Se um controller foi especificado na URL mas não encontrado, é um 404.
                // (A menos que seja "home", que é o default e já está em $this->currentControllerName)
                if (strtolower($urlParts[0]) !== 'home') { // Evita 404 se a URL for /home e HomeController for o default
                    $this->trigger404("Controller '" . $controllerCandidateBaseName . "Controller' não encontrado.");
                    return; // Para a execução do construtor
                }
                // Se for 'home' e não existir, o class_exists mais abaixo pegará.
                // $this->currentControllerName já é 'HomeController' por padrão.
            }
        }
        // Se $urlParts[0] estava vazio, $this->currentControllerName permanece 'HomeController' (o default).

        $fqcnController = "App\\Controllers\\" . $this->currentControllerName;

        // 2. Determinar o Método
        if (isset($urlParts[1]) && !empty($urlParts[1])) {
            // Verifica se o método existe no controller (FQCN) que determinamos
            if (method_exists($fqcnController, $urlParts[1])) {
                $this->currentMethodName = $urlParts[1];
                unset($urlParts[1]);
            } else {
                // Se um método foi especificado na URL mas não existe no controller, é um 404.
                $this->trigger404("Método '" . $urlParts[1] . "' não encontrado no controller '" . $this->currentControllerName . "'.");
                return; // Para a execução do construtor
            }
        }
        // Se $urlParts[1] estava vazio ou não definido, $this->currentMethodName permanece 'index' (o default).

        // 3. Pegar os Parâmetros restantes
        $this->params = $urlParts ? array_values($urlParts) : [];

        // 4. Despachar a rota (chamar o controller e método)
        $this->dispatchRoute($fqcnController, $this->currentMethodName, $this->params);
    }

    /**
     * Analisa a URL a partir de $_GET['url'].
     * @return array As partes da URL.
     */
    protected function parseUrl(): array {
        $url = isset($_GET['url']) ? $_GET['url'] : ''; // Pega a string da URL ou string vazia se não definida
        $url = rtrim($url, '/'); // Remove barras finais
        $url = filter_var($url, FILTER_SANITIZE_URL); // Remove caracteres ilegais da URL
        
        // Se a URL após a limpeza for uma string vazia (ex: acesso à raiz '/'),
        // explode('/', '') resulta em um array com um elemento string vazia: [''].
        // Isso é útil para a lógica de controller/método padrão.
        return explode('/', $url);
    }

    /**
     * Instancia o controller e chama o método com os parâmetros.
     */
    protected function dispatchRoute(string $controllerClass, string $method, array $params): void {
        // Verificação final se a classe do controller existe (o autoloader tentará carregá-la)
        if (!class_exists($controllerClass)) {
            $this->trigger404("Classe do Controller '" . $controllerClass . "' não encontrada após tentativa de autoload.");
            return;
        }

        $controllerInstance = new $controllerClass(); // Cria a instância do controller

        // Verificação final se o método existe no objeto (especialmente para o método 'index' padrão)
        if (!method_exists($controllerInstance, $method)) {
            $this->trigger404("Método '" . $method . "' não existe na classe do controller '" . $controllerClass . "'.");
            return;
        }

        // Chama o método do controller, passando os parâmetros
        call_user_func_array([$controllerInstance, $method], $params);
    }

    /**
     * Dispara o ErrorController para exibir uma página 404.
     */
    protected function trigger404(string $message = "Recurso não encontrado."): void {
        $errorController = new ErrorController();
        $errorController->show404($message);
        exit; // Garante que nada mais seja executado
    }
}
?>