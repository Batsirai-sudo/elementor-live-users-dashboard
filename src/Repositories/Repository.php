<?php

namespace BatsiraiMuchareva\LiveUserDashboard\Repositories;

use BatsiraiMuchareva\LiveUserDashboard\Traits\HasFileOperations;
use Exception;

abstract class Repository
{
    use HasFileOperations;

    protected string $filePath;

    public function __construct()
    {
        $this->filePath = DATA_PATH;
    }

    public function get(array $filters = [])
    {
        $data = $this->readData();

        if (!empty($filters)) {
            $filteredData = array_filter($data, function ($item) use ($filters) {
                foreach ($filters as $column => $value) {
                    if (!isset($item[$column]) || $item[$column] !== $value) {
                        return false;
                    }
                }
                return true;
            });

            return empty($filteredData) ? null : reset($filteredData);
        }

        return $data;
    }

    /**
     * @throws Exception
     */
    public function update(string $id, array $payload): bool
    {
        $data = $this->readData();

        $index = array_search($id, array_column($data, 'id'));

        if ($index !== false) {

            $data[$index] = array_merge($data[$index], $payload);
            $this->writeData($data);

            return true;
        } else {
            throw new Exception('Data with ID ' . $id . ' not found for update.');
        }
    }
}