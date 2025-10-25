<?php
// app/controllers/DashboardController.php

class DashboardController {
    
    public function index() {
        $data = [
            'title' => 'Dashboard Utama',
            'user' => [
                'name' => 'Haryadi',
                'role' => 'Super Admin',
                'email' => 'haryadi@example.com'
            ],
            'order_count' => '5'
        ];
        
        return TemplateEngine::render('app/views/dashboard.php', $data);
    }
}

// app/controllers/UserController.php
class UserController {
    
    public function index() {
        $data = [
            'title' => 'Manajemen Users',
            'user' => [
                'name' => 'Haryadi',
                'role' => 'Super Admin'
            ]
        ];
        
        return TemplateEngine::render('app/views/users/index.php', $data);
    }
}