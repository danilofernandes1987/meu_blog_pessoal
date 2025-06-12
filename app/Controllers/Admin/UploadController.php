<?php
// app/Controllers/Admin/UploadController.php
namespace App\Controllers\Admin;

use App\Core\BaseAdminController;

class UploadController extends BaseAdminController
{
    /**
     * Processa o upload de uma imagem vinda do editor TinyMCE.
     */
    public function image()
    {
        // O construtor do BaseAdminController já garante que apenas usuários logados acessem.
        
        // Configurações de segurança e validação
        $accepted_origins = [
            "http://localhost",
            "http://127.0.0.1",
            "http://meublog.local", // Seu domínio de desenvolvimento
            "https://www.danilosilva.net" // Seu domínio de produção
        ];

        // O TinyMCE envia a origem na requisição
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if (in_array($origin, $accepted_origins)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        } else {
            http_response_code(403);
            echo json_encode(['error' => ['message' => 'Origem da requisição não permitida.']]);
            return;
        }

        // O TinyMCE envia o arquivo no campo 'file'
        if (!isset($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
            http_response_code(400);
            echo json_encode(['error' => ['message' => 'Nenhum arquivo de imagem enviado.']]);
            return;
        }

        // Usamos o método que já criamos no BaseAdminController para lidar com o upload
        $newImageName = $this->handleImageUpload($_FILES['file']);

        if ($newImageName === false) {
            // Se handleImageUpload falhou, ele já deve ter definido um erro na sessão.
            // Para uma resposta de API, retornamos um erro JSON.
            http_response_code(400); // Bad Request
            $errorMessage = $_SESSION['errors']['photo'] ?? 'Erro desconhecido durante o upload.';
            unset($_SESSION['errors']['photo']); // Limpa o erro da sessão
            echo json_encode(['error' => ['message' => $errorMessage]]);
            return;
        }

        // Se o upload foi bem-sucedido, retornamos o JSON que o TinyMCE espera
        $file_path = '/uploads/images/' . $newImageName;
        http_response_code(200);
        echo json_encode(['location' => $file_path]);
    }
}
