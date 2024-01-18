<?php

namespace BatsiraiMuchareva\LiveUserDashboard\Traits;

trait HasFileOperations
{
    public function readData(): mixed
    {
        if (file_exists($this->filePath)) {
            $content = file_get_contents($this->filePath);
            return json_decode($content, true);
        }

        return [];
    }

    protected function writeData($data): void
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $jsonData);
    }
}