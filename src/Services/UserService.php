<?php

namespace BatsiraiMuchareva\LiveUserDashboard\Services;

use BatsiraiMuchareva\LiveUserDashboard\Application;
use BatsiraiMuchareva\LiveUserDashboard\Repositories\UserRepository;
use Exception;

class UserService
{
    const SECRET_KEY = 'my_secret_key';
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

    public function updateUser(): bool
    {
        $userIP = $_SERVER['REMOTE_ADDR'] ?? null;

        if ($userIP === '::1') {
            $userIP = '127.0.0.1';
        }

        $formattedTime = date("Y-m-d H:i:s");

        return $this->repository->update($this->application->session->get('user_id'), [
            'updated_at' => $formattedTime,
            'user_ip' => $userIP
        ]);
    }

    /**
     * @throws Exception
     */
    public function pollingUsers()
    {
       $this->updateUser();

       return $this->getUsers();
    }

    public function authenticate($data): array
    {
        $user = $this->repository->find([
            'email' => $data['email'],
            'name' => $data['name'],
        ]);

        if (!$user) {

            http_response_code(401); // Unauthorized

            return [
                'success' => false,
                'error' => 'Invalid credentials to access the Dashboard'
            ];
        }

        $formattedTime = date("Y-m-d H:i:s", time());

        $this->repository->update($user['id'], [
            'entrance_time' => $formattedTime,
            'visit_count' =>  isset($user['visit_count']) ? intval($user['visit_count']) + 1 : 1,
            'user_agent' => $this->getUserAgent()
        ]);

        return array_merge([
            'success' => true,
            'token' => $this->generateToken($user['id'])
        ], $user);
    }

    public function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }


    public function generateToken(string $userID): string
    {
        return base64_encode($userID . '|' . md5($userID . self::SECRET_KEY));
    }
}