<?php

namespace BatsiraiMuchareva\LiveUserDashboard\Controllers;

use BatsiraiMuchareva\LiveUserDashboard\Services\UserService;
use Exception;

class UserController
{
    protected UserService $service;

    public function __construct()
    {
        $this->service =  new UserService();
    }

    public function authenticate($params): array
    {
        return $this->service->authenticate($params);
    }

    public function users(): array
    {
        return $this->service->getUsers();
    }

    public function user( $params): ?array
    {
        return $this->service->getUser($params['id']);
    }

    /**
     * @throws Exception
     */
    public function polling()
    {
        return $this->service->pollingUsers();
    }
}