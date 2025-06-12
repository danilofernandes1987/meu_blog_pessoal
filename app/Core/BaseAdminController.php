<?php
// app/Core/BaseAdminController.php
namespace App\Core;

// BaseAdminController herda de BaseController para usar o método view(), config, etc.
class BaseAdminController extends BaseController
{

    public function __construct()
    {
        parent::__construct(); // Chama o construtor do BaseController

        // Verifica se o usuário administrador está logado
        if (!isset($_SESSION['admin_user_id'])) {
            // Se não estiver logado, registra a tentativa de acesso (opcional)
            // error_log("Acesso não autenticado à área administrativa. IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A'));

            // Guarda a URL que o usuário tentou acessar para redirecioná-lo após o login (opcional)
            // $_SESSION['redirect_url_after_login'] = $_SERVER['REQUEST_URI'] ?? '/admin/dashboard';

            // Redireciona para a página de login
            header('Location: /auth/login');
            exit;
        }

        // Opcional: Verificar se a sessão expirou por tempo, se implementado
        // if (isset($_SESSION['admin_login_time']) && (time() - $_SESSION['admin_login_time'] > 1800)) { // Ex: 30 minutos
        //     // Lógica de logout por expiração
        //     $authController = new \App\Controllers\AuthController(); // Cuidado com dependência direta
        //     $authController->logout(); // Ou chame um método de logout mais genérico
        //     exit;
        // }
        // $_SESSION['admin_login_time'] = time(); // Atualiza o tempo da última atividade
    }

    /**
     * Sobrescreve o método view para usar um layout administrativo padrão, se desejado.
     * Ou simplesmente confie que os controllers admin especificarão o layout admin.
     * Por enquanto, vamos deixar como está e o controller admin especificará o layout.
     */
    // protected function view(string $viewName, array $data = [], string $layoutName = 'layouts.admin'): void {
    //     parent::view($viewName, $data, $layoutName);
    // }

    /**
     * Lida com o upload da imagem de destaque.
     * @param array $fileInput O array do arquivo vindo de $_FILES['featured_image'].
     * @param string|null $oldImageFilename O nome do arquivo da imagem antiga, para exclusão durante a atualização.
     * @return string|null|false Retorna o nome do novo arquivo em sucesso, null se nenhum arquivo foi enviado, ou false em caso de erro de upload/validação.
     */
    protected function handleImageUpload(array $fileInput, ?string $oldImageFilename = null): string|null|false
    {
        // Verifica se um arquivo foi enviado e se não houve erros no upload
        if (isset($fileInput) && $fileInput['error'] === UPLOAD_ERR_OK) {
            $uploadDir = public_path('uploads/images/');

            // Certifique-se de que o diretório de upload existe
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }


            $maxFileSize = 2 * 1024 * 1024; // 2 MB
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            // 1. Validação de tamanho
            if ($fileInput['size'] > $maxFileSize) {
                // erro de tamanho
                $_SESSION['errors']['featured_image'] = 'O arquivo de imagem é muito grande (máximo 2MB).';
                return false;
            }

            // 2. Validação de tipo de arquivo
            $fileType = mime_content_type($fileInput['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                // erro de tipo
                $_SESSION['errors']['featured_image'] = 'Tipo de arquivo inválido. Apenas JPG, PNG, GIF e WebP são permitidos.';
                return false;
            }

            // 3. Gerar nome de arquivo único para evitar conflitos
            $fileName = time() . '-' . preg_replace('/[^A-Za-z0-9.\-]/', '', basename($fileInput['name']));
            $targetFile = $uploadDir . $fileName;

            // 4. Mover o arquivo para o diretório de uploads
            if (move_uploaded_file($fileInput['tmp_name'], $targetFile)) {
                // 5. Se moveu com sucesso e existe uma imagem antiga, delete-a
                if ($oldImageFilename && file_exists($uploadDir . $oldImageFilename)) {
                    unlink($uploadDir . $oldImageFilename);
                }
                return $fileName; // Retorna o nome do novo arquivo
            } else {
                // erro ao mover
                $_SESSION['errors']['featured_image'] = 'Ocorreu um erro ao salvar a imagem.';
                return false;
            }
        } elseif (isset($fileInput) && $fileInput['error'] !== UPLOAD_ERR_NO_FILE) {
            // Se houve um erro de upload que não seja "nenhum arquivo enviado"
            $_SESSION['errors']['featured_image'] = 'Ocorreu um erro durante o upload da imagem.';
            return false;
        }

        // Se nenhum arquivo novo foi enviado, retorna null
        return null;
    }
}
