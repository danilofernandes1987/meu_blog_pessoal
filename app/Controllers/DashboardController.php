<?php
// app/Controllers/DashboardController.php
namespace App\Controllers;

use App\Core\BaseController;

class DashboardController extends BaseController {
    public function __construct() {
        parent::__construct();
        // Passo futuro: Adicionar verificação de autenticação aqui ou em um BaseAdminController
        if (!isset($_SESSION['admin_user_id'])) {
            // Se não estiver logado, redireciona para o login
            // Idealmente, teríamos um BaseAdminController para isso.
            // Por agora, esta verificação simples.
            header('Location: /auth/login');
            exit;
        }
    }

    public function index(): void {
        $data = [
            'pageTitle' => 'Painel Administrativo',
            'contentTitle' => 'Bem-vindo(a) ao Painel, ' . htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') . '!',
        ];
        // Esta view usará o layout principal do site por enquanto.
        // No futuro, poderíamos ter um layout específico para o admin.
        $this->view('dashboard.index', $data);
    }
}
?>