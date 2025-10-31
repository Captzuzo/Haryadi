<?php
namespace App\Controllers;

use Haryadi\Controller\Controller;
use Haryadi\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // validate CSRF
        if (!function_exists('csrf_validate') || !csrf_validate()) {
            return response('Invalid CSRF token', 400);
        }

        $data = $request->all();
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $message = trim($data['message'] ?? '');

        if ($name === '' || $message === '') {
            return response('Nama dan pesan harus diisi', 422);
        }

        // In a real app you'd store/send the message. Here we just return a success message.
        $body = "Terima kasih, " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ". Pesan Anda telah diterima.";
        return response($body, 200);
    }
}
