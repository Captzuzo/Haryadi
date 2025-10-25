<?php
// app/middleware/AuthMiddleware.php

namespace App\Middleware;

use Haryadi\Core\Request;
use Haryadi\Core\Response;
use Haryadi\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request): bool
    {
        // Cek jika user sudah login
        if (!Session::get('logged_in')) {
            // Redirect ke halaman login
            header('Location: /login');
            return false;
        }

        // Lanjutkan request
        return true;
    }
}