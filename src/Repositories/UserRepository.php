<?php

namespace BatsiraiMuchareva\LiveUserDashboard\Repositories;

class UserRepository extends Repository
{
    public function findById(string $id): array
    {
        return $this->get(['id' => $id]);
    }

    public function find(array $data): array
    {
        return $this->get($data);
    }
}