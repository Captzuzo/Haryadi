<?php

use App\Controllers\HomeController;
use Haryadi\Routing\Route;

Route::get('/', [HomeController::class, 'index']);