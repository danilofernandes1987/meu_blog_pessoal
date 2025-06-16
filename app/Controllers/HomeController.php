<?php
// app/Controllers/HomeController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\SiteSettingsModel;
use App\Models\SoftSkillModel;

class HomeController extends BaseController
{

    private $siteSettingsModel;
    private $softSkillModel;

    public function __construct()
    {
        parent::__construct();
        $this->siteSettingsModel = new SiteSettingsModel();
        $this->softSkillModel = new SoftSkillModel();
    }

    public function index(): void
    {
        // Busca o texto de introdução e a lista de soft skills do banco de dados
        $introductionText = $this->siteSettingsModel->getSetting('homepage_introduction_text');
        $softSkills = $this->softSkillModel->findAllActive();

        $data = [
            'contentTitle'      => 'Danilo Fernandes da Silva',
            'introductionText'  => $introductionText,
            'publicProfilePhoto'  => $this->siteSettingsModel->getSetting('public_profile_photo'),
            'softSkills'        => $softSkills
        ];

        $this->view('home.index', $data);
    }

    public function curriculo(): void
    {
        $data = [
            'contentTitle' => 'Currículo'
        ];
        $this->view('home.curriculo', $data);
    }
}
