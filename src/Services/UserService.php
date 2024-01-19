<?php

namespace BatsiraiMuchareva\LiveUserDashboard\Services;

use BatsiraiMuchareva\LiveUserDashboard\Application;
use BatsiraiMuchareva\LiveUserDashboard\Repositories\UserRepository;
use Exception;

class UserService
{
    protected Application $application;
    protected UserRepository $repository;

    public function __construct()
    {
        $this->application = Application::getInstance();
        $this->repository = new UserRepository();
    }

    public function getUsers()
    {
        return $this->repository->get();
    }

    public function getUser(string $id): ?array
    {
        return $this->repository->findById($id);
    }

    /**
     * @throws Exception
     */
    public function update($id, $data): bool
    {
         return $this->repository->update($id, $data);
    }

    public function authenticate($data): array
    {
        $user = $this->repository->find([
            'email' => $data['email'],
            'name' => $data['name'],
        ]);

        if ($user) {
            $this->application->session->set('user', $user['id']);
        }

        return $user;
    }
}