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

    public function authenticate($data): array
    {
        return $this->service->authenticate($data);
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
    public function update(string $id, array $data): bool
    {
        return $this->service->update($id, $data);
    }

    public function status()
    {

    }

}