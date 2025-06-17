<?php
// app/Controllers/CurriculoController.php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\WorkExperienceModel;
use App\Models\EducationModel;
use App\Models\CourseModel;
use App\Models\SiteSettingsModel;

class CurriculoController extends BaseController
{
    public function index()
    {
        // Instancia todos os models necessários
        $experienceModel = new WorkExperienceModel();
        $educationModel = new EducationModel();
        $courseModel = new CourseModel();
        $settingsModel = new SiteSettingsModel();

        // Busca os dados
        $experiences = $experienceModel->findAll();
        $educations = $educationModel->findAll();
        $courses = $courseModel->findAll();
        // Você pode adicionar um campo para 'resumo profissional' na tabela site_settings
        $professionalSummary = $settingsModel->getSetting('professional_summary');

        $data = [
            'pageTitle'           => 'Currículo',
            'contentTitle'        => 'Currículo - Danilo Fernandes da Silva',
            'professionalSummary' => $professionalSummary,
            'experiences'         => $experiences,
            'educations'          => $educations,
            'courses'             => $courses,
        ];
        
        $this->view('curriculo.index', $data);
    }
}
