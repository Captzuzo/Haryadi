<?php

namespace App\Controllers;

use Haryadi\Controller\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        $this->view('home.index', [
            'title' => 'Welcome to Haryadi Framework',
            'message' => 'Your application is now ready!'
        ]);
    }
}