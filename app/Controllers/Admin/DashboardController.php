<?php
// app/Controllers/Admin/DashboardController.php
namespace App\Controllers\Admin;

use App\Core\BaseAdminController;
use App\Models\PostModel;
use App\Models\WorkExperienceModel;
use App\Models\EducationModel;
use App\Models\CourseModel;

class DashboardController extends BaseAdminController
{

    // O construtor do BaseAdminController já cuida da verificação de login.

    public function index(): void
    {
        // Instancia todos os models necessários
        $postModel = new PostModel();
        $experienceModel = new WorkExperienceModel();
        $educationModel = new EducationModel();
        $courseModel = new CourseModel();

        // Busca os 5 posts mais recentes para a lista de "Atividade Recente"
        $recentPosts = $postModel->findAll(5, 0);

        // Prepara os dados para a view
        $data = [
            'pageTitle'         => 'Painel Administrativo',
            'contentTitle'      => 'Dashboard',
            'totalPosts'        => $postModel->countAll(),
            'totalExperiences'  => $experienceModel->countAll(),
            'totalEducations'   => $educationModel->countAll(),
            'totalCourses'      => $courseModel->countAll(),
            'recentPosts'       => $recentPosts
        ];

        $this->view('admin.dashboard.index', $data, 'layouts.admin');
    }
}
