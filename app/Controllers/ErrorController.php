<?php
// app/Controllers/ErrorController.php
namespace App\Controllers;

use App\Core\BaseController; // Certifique-se que o BaseController está sendo usado

class ErrorController extends BaseController
{

    /**
     * Exibe a página de erro 404 personalizada.
     * Pode opcionalmente receber uma mensagem para log interno.
     */
    public function show404(string $internalMessage = 'Recurso não encontrado'): void
    {
        // 1. Define o código de status HTTP para 404 Not Found
        http_response_code(404);

        // 2. Prepara dados para a view (título da aba, etc.)
        $data = [
            'pageTitle' => 'Página Não Encontrada (Erro 404)',
            // Não precisamos de 'pageHeading' aqui, pois a view errors/404.php já tem seu próprio título grande.
        ];

        // (Opcional, mas recomendado) Logar o erro para análise interna
        // error_log("Erro 404 disparado: " . $internalMessage . " - URL: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));

        echo $internalMessage;

        // 3. Renderiza a view de erro 404 usando o layout padrão
        $this->view('errors.404', $data);
    }
}
