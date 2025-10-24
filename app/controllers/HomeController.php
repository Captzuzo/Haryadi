<?php
namespace App\Controllers;

use Haryadi\Core\Controller;

class HomeController extends Controller
{
    // public function index()
    // {
    //     // Method ini akan menggunakan $this->view() dari parent class
    //     $this->view('home', [
    //         'title' => 'Selamat Datang di Haryadi Framework',
    //         'message' => 'Membangun aplikasi web menakjubkan dengan mudah!',
    //         'features' => [
    //             'Arsitektur MVC Modern',
    //             'Sistem Routing Elegan', 
    //             'Database Abstraction Layer',
    //             'RESTful API Ready',
    //             'Flexible Templating'
    //         ]
    //     ]);
    // }

    public function index()
    {
        $this->view('welcome', [
            'title' => 'Haryadi Framework',
            'tagline' => 'Framework PHP Modern untuk Indonesia'
        ]);
    }

    public function about()
    {
        // Simple JSON response
        $this->success([
            'page' => 'Tentang Kami',
            'framework' => 'Haryadi Framework',
            'version' => '1.0.0',
            'description' => 'Framework PHP MVC modern untuk membangun aplikasi web dan API.'
        ], 'Data about page');
    }

    public function contact()
    {
        // Simple view untuk contact form
        $this->view('contact', [
            'title' => 'Hubungi Kami'
        ]);
    }

    public function saveContact()
    {
        // Simple form validation dan processing
        $data = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        // Simulasi save data
        $savedData = [
            'id' => rand(1000, 9999),
            ...$data,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->success($savedData, 'Pesan berhasil dikirim!', 201);
    }

    public function userProfile($id)
    {
        // Simple parameter handling
        $user = [
            'id' => (int) $id,
            'name' => 'User ' . $id,
            'email' => 'user' . $id . '@example.com',
            'joined_at' => '2024-01-01'
        ];

        $this->success($user, 'Data user ditemukan');
    }

    public function apiStatus()
    {
        // Simple API status check
        $this->success([
            'status' => 'healthy',
            'timestamp' => date('c'),
            'environment' => env('APP_ENV', 'local')
        ], 'API berjalan dengan baik');
    }

    // Method untuk 404 page - DENGAN PARAMETER YANG SESUAI
    public function notFound(string $message = 'Halaman tidak ditemukan'): void
    {
        // Anda bisa custom message di sini
        $customMessage = 'Halaman yang Anda cari tidak ditemukan.';
        $this->view('404', [
            'title' => 'Halaman Tidak Ditemukan',
            'message' => $customMessage
        ]);
    }

    /**
     * Method tambahan untuk welcome page
     */
    
}