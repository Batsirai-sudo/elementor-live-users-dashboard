<?php

global $app;

use BatsiraiMuchareva\LiveUserDashboard\Controllers\UserController;

$app->router->get('/login', [UserController::class, 'authenticate']);
$app->router->get('/users', [UserController::class, 'users']);
$app->router->get('/user', [UserController::class, 'user']);
$app->router->get('/update-user', [UserController::class, 'update']);
$app->router->get('/ping-status', [UserController::class, 'user']);
