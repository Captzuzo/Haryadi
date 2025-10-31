<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Haryadi/Support/helpers.php';

// start session and ensure CSRF token exists
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$token = csrf_token();

// create a fake POST request
$post = [
    'name' => 'Tester',
    'email' => 'tester@example.com',
    'message' => 'Halo, ini pesan tes',
    '_token' => $token,
];

// populate superglobals so csrf_validate() (which checks $_POST/$_SERVER) can read them
$_POST = $post;
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/contact/submit';

$request = new \Haryadi\Http\Request([], $_POST, $_SERVER);

$controller = new App\Controllers\ContactController();
$response = $controller->submit($request);
if ($response instanceof \Haryadi\Http\Response) {
    $response->send();
} else {
    echo (string) $response;
}
