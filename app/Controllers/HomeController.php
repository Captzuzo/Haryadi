<?php
namespace App\Controllers;

use Haryadi\Controller\Controller;
use Haryadi\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return response($this->view('home', ['name' => 'Haryadi']));
    }
}
