<?php
// app/Controllers/HomeController.php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\SiteSettingsModel;
use App\Models\SoftSkillModel;
use App\Models\PostModel;

class HomeController extends BaseController
{

    private $siteSettingsModel;
    private $softSkillModel;
    private $postModel;

    public function __construct()
    {
        parent::__construct();
        $this->siteSettingsModel = new SiteSettingsModel();
        $this->softSkillModel = new SoftSkillModel();
        $this->postModel = new PostModel();
    }

    public function index(): void
    {
        // Busca os dados dinâmicos existentes
        $introductionText = $this->siteSettingsModel->getSetting('homepage_introduction_text');
        $publicProfilePhoto = $this->siteSettingsModel->getSetting('public_profile_photo');
        $softSkills = $this->softSkillModel->findAllActive();
        
        // Busca os 5 posts mais recentes
        $recentPosts = $this->postModel->findRecent(5);

        $data = [
            'contentTitle'      => 'Danilo Fernandes da Silva',
            'introductionText'  => $introductionText,
            'publicProfilePhoto'=> $publicProfilePhoto,
            'softSkills'        => $softSkills,
            'recentPosts'       => $recentPosts,
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
