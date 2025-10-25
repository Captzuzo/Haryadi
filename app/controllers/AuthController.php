<?php
namespace App\Controllers;

use Haryadi\Core\Request;
use Haryadi\Core\Session;
use Haryadi\Core\Database;

class AuthController
{
    public function login(Request $request)
    {
        // Set header JSON
        header('Content-Type: application/json');
        
        try {
            // Get JSON data
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Data JSON tidak valid'
                ]);
                return;
            }

            // Validasi input
            if (empty($data['email']) || empty($data['password'])) {
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => 'Email dan password harus diisi',
                    'errors' => [
                        'email' => empty($data['email']) ? 'Email harus diisi' : null,
                        'password' => empty($data['password']) ? 'Password harus diisi' : null
                    ]
                ]);
                return;
            }

            // Gunakan Database langsung
            $db = Database::getInstance()->getConnection();
            
            // Cari user di database
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);
            $user = $stmt->fetch(\PDO::FETCH_OBJ);

            if (!$user) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Email tidak ditemukan',
                    'errors' => [
                        'email' => 'Email tidak terdaftar'
                    ]
                ]);
                return;
            }

            // Verifikasi password
            if (!password_verify($data['password'], $user->password)) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Password salah',
                    'errors' => [
                        'password' => 'Password yang dimasukkan salah'
                    ]
                ]);
                return;
            }

            // Login berhasil
            echo json_encode([
                'success' => true,
                'message' => 'Login berhasil! 🎉',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role ?? 'user'
                    ],
                    'token' => $this->generateToken($user->id),
                    'redirect' => '/dashboard'
                ]
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ]);
        }
    }

    public function authenticate()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $user = User::where('email', $email)->first();
        
        if ($user && password_verify($password, $user->password)) {
            Session::set('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]);
            
            Session::flash('success', 'Selamat datang, ' . $user->name . '!');
            return redirect('/dashboard');
        }
        
        Session::flash('error', 'Email atau password salah.');
        return redirect('/login');
    }
    
    public function logout()
    {
        Session::destroy();
        Session::flash('success', 'Anda telah logout.');
        return redirect('/login');
    }

    private function generateToken($userId)
    {
        $payload = [
            'user_id' => $userId,
            'exp' => time() + (24 * 60 * 60) // 24 jam
        ];
        
        return base64_encode(json_encode($payload));
    }

     public function handle($request, $next)
    {
        if (!Session::has('user')) {
            Session::flash('error', 'Anda harus login terlebih dahulu.');
            return redirect('/login');
        }
        
        return $next($request);
    }
}

class AdminMiddleware {
    
    public function handle($request, $next)
    {
        $user = Session::get('user');
        
        if (!$user || $user['role'] !== 'admin') {
            Session::flash('error', 'Akses ditolak. Halaman ini hanya untuk administrator.');
            return redirect('/dashboard');
        }
        
        return $next($request);
    }
}