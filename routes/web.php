<?php
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use Haryadi\Routing\Route;

Route::get('/', [HomeController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/contact/submit', [App\Controllers\ContactController::class, 'submit']);
