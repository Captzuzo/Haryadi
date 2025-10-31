<?php
namespace App\Controllers;

use Haryadi\Controller\Controller;
use Haryadi\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->all();
        // very basic example
        if (($data['email'] ?? '') === 'admin@example.com' && ($data['password'] ?? '') === 'secret') {
            return response('Login success');
        }
        return response('Invalid credentials', 401);
    }
}
