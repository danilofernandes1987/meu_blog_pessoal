<?php
// app/Controllers/HomeController.php

namespace App\Controllers;

use App\Core\BaseController;

class HomeController extends BaseController
{

    public function index(): void
    {
        $data = [
            'contentTitle' => 'Danilo Fernandes da Silva',
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
