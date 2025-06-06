<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\AdminUserModel;

class AuthController extends BaseController
{
    private AdminUserModel $adminUserModel;

    public function __construct()
    {
        parent::__construct(); // Chama o construtor do BaseController
        $this->adminUserModel = new AdminUserModel();
    }

    public function index(): void
    {
        header('Location: /auth/login'); // Redireciona para a página de login
        exit;
    }

    /**
     * Exibe o formulário de login ou processa a tentativa de login.
     * Acessado via GET ou POST para /auth/login (ou a rota que seu Router definir para ele)
     */
    public function login(): void
    {
        // Se já estiver logado, redireciona para o dashboard administrativo
        if (isset($_SESSION['admin_user_id'])) {
            header('Location: /admin/dashboard'); // Rota do painel admin
            exit;
        }

        $errorMessage = '';
        $usernameAttempt = ''; // Para repopular o campo username em caso de erro

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validatePostRequest();
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $usernameAttempt = $username; // Guarda a tentativa

            if (empty($username) || empty($password)) {
                $errorMessage = 'Por favor, preencha o usuário e a senha.';
            } else {
                $adminUser = $this->adminUserModel->findByUsername($username);

                if ($adminUser && password_verify($password, $adminUser['password_hash'])) {
                    // Login bem-sucedido
                    session_regenerate_id(true); // Previne session fixation
                    $_SESSION['admin_user_id'] = $adminUser['id'];
                    $_SESSION['admin_username'] = $adminUser['username'];
                    $_SESSION['admin_name'] = $adminUser['name'];
                    // Poderia adicionar um timestamp de login aqui se quisesse: $_SESSION['admin_login_time'] = time();

                    header('Location: /admin/dashboard'); // Redireciona para o painel admin
                    exit;
                } else {
                    // error_log("Falha no login para usuário: $username"); // Bom para logar tentativas falhas
                    $errorMessage = 'Usuário ou senha inválidos.';
                }
            }
        }

        // Se for método GET ou se o login POST falhou, exibe o formulário
        $data = [
            'pageTitle' => 'Login Administrativo',
            'error' => $errorMessage,
            'username_attempt' => $usernameAttempt
        ];
        // Usaremos um layout minimalista para a página de login
        $this->view('auth.login', $data, 'layouts.auth_minimal');
    }

    /**
     * Faz o logout do administrador.
     * Acessado via /auth/logout
     */
    public function logout(): void
    {
        // Limpa todas as variáveis de sessão específicas da sua aplicação
        unset($_SESSION['admin_user_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_name']);
        // Ou $_SESSION = []; para limpar tudo, mas pode afetar outras coisas se a sessão for usada para mais.

        // Destrói a sessão completamente
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        session_regenerate_id(true); // Regenera ID para a próxima sessão (mais seguro)

        header('Location: /'); // Redireciona para a página de login
        exit;
    }
}
