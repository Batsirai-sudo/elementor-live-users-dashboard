<?php

global $app;

use BatsiraiMuchareva\LiveUserDashboard\Controllers\UserController;

$app->router->post('/login', [UserController::class, 'authenticate']);
$app->router->get('/users', [UserController::class, 'users']);
$app->router->get('/user/{id}', [UserController::class, 'user']);
$app->router->get('/polling', [UserController::class, 'polling']);