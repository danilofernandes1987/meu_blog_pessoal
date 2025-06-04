<?php
// app/Core/BaseAdminController.php
namespace App\Core;

// BaseAdminController herda de BaseController para usar o método view(), config, etc.
class BaseAdminController extends BaseController {

    public function __construct() {
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
}
?>