<?php
// app/Controllers/Admin/DashboardController.php
namespace App\Controllers\Admin; 

use App\Core\BaseAdminController;

class DashboardController extends BaseAdminController {

    // O construtor do BaseAdminController já cuida da verificação de login.

    public function index(): void {
        $data = [
            'pageTitle' => 'Painel Administrativo',
            'contentTitle' => 'Bem-vindo(a) ao Painel, ' . htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') . '!',
            'adminUsername' => $_SESSION['admin_username'] ?? ''
        ];

        // Usa um novo layout específico para a área administrativa
        $this->view('admin.dashboard.index', $data, 'layouts.admin');
    }
}
?>