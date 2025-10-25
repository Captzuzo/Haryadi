<?php
use Haryadi\Core\Router;
use Haryadi\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;


Router::get('/', function($request) {
    view('welcome');
}, 'home');

// ==================== AUTH ROUTES ====================
// Route untuk halaman login (GET)
Router::get('/login', function() {
    // Jika sudah login, redirect ke dashboard
    if (isset($_SESSION['user'])) {
        header('Location: /layouts/dashboard');
        exit;
    }
    
    return view('auth/login', ['title' => 'Masuk']);
});

// Route untuk proses login (POST)
Router::post('/login', function() {
    header('Content-Type: application/json');
    
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if ($data['email'] === 'admin@haryadi.com' && $data['password'] === 'password123') {
            // Simpan session/user data
            $_SESSION['user'] = [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@haryadi.com',
                'role' => 'admin'
            ];
            
            echo json_encode([
                'success' => true,
                'message' => 'Login berhasil! 🎉',
                'data' => [
                    'user' => $_SESSION['user'],
                    'redirect' => '/layouts/dashboard'
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Email atau password salah'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
});

// Route untuk dashboard
Router::get('/layouts/dashboard', function() {
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    
    return view('layouts/dashboard', [
        'title' => 'Dashboard',
        'user' => $_SESSION['user']
    ]);
});

Router::post('/api/auth/login', 'AuthController@login');
Router::post('/login', ['AuthController@authenticate'], 'login.authenticate');
Router::get('/register', ['AuthController@register'], 'register');
Router::post('/register', ['AuthController@store'], 'register.store');
Router::post('/logout', ['AuthController@logout'], 'logout');
Router::get('/dashboard', ['AuthController@dashboard'], 'dashboard');


// ==================== HOME ROUTES ====================
Router::get('/', ['HomeController@index'], 'index');
Router::get('/about', ['HomeController@about'], 'about');

// ==================== PROTECTED ROUTES EXAMPLE ====================
Router::get('/profile', function($request) {
    // Example protected route - cek session dulu
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    
    echo "<h1>Profile Page</h1>";
    echo "<p>Halo, {$_SESSION['user_name']}!</p>";
    echo "<a href='/dashboard'>← Kembali ke Dashboard</a>";
}, 'profile');

// ==================== API ROUTES ====================
Router::get('/api/status', ['HomeController@apiStatus'], 'api.status');

// ==================== FALLBACK ====================