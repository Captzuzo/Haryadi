<?php
// routes/api.php

use Haryadi\Core\Router;

Router::post('/auth/login', 'AuthController@login');

// API Version 1 Routes
Router::get('/api/v1/status', function($request) {
    \Haryadi\Core\Response::json([
        'api_version' => 'v1',
        'status' => 'active',
        'timestamp' => date('c'),
        'framework' => 'Haryadi Framework'
    ]);
}, 'api.status');

Router::get('/api/v1/users', ['Api\UserController@index'], 'api.users.index');
Router::get('/api/v1/users/{id}', ['Api\UserController@show'], 'api.users.show');
Router::post('/api/v1/users', ['Api\UserController@store'], 'api.users.store');
Router::put('/api/v1/users/{id}', ['Api\UserController@update'], 'api.users.update');
Router::delete('/api/v1/users/{id}', ['Api\UserController@destroy'], 'api.users.delete');

// API Auth routes
Router::post('/api/v1/auth/login', ['Api\AuthController@login'], 'api.auth.login');
Router::post('/api/v1/auth/register', ['Api\AuthController@register'], 'api.auth.register');
Router::post('/api/v1/auth/logout', ['Api\AuthController@logout'], 'api.auth.logout');
Router::get('/api/v1/auth/user', ['Api\AuthController@user'], 'api.auth.user');

// API Products routes (contoh resource)
Router::get('/api/v1/products', ['Api\ProductController@index'], 'api.products.index');
Router::get('/api/v1/products/{id}', ['Api\ProductController@show'], 'api.products.show');
Router::post('/api/v1/products', ['Api\ProductController@store'], 'api.products.store');
Router::put('/api/v1/products/{id}', ['Api\ProductController@update'], 'api.products.update');
Router::delete('/api/v1/products/{id}', ['Api\ProductController@destroy'], 'api.products.delete');

// API Health check
Router::get('/api/health', function($request) {
    \Haryadi\Core\Response::json([
        'status' => 'ok',
        'service' => 'Haryadi Framework API',
        'version' => '1.0.0',
        'timestamp' => date('Y-m-d H:i:s'),
        'environment' => env('APP_ENV', 'local'),
        'database' => [
            'connected' => true,
            'name' => env('DB_NAME', 'haryadi_app')
        ]
    ]);
}, 'api.health');