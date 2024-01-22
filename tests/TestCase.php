<?php

namespace Tests;

use \PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function setUp(): void
    {
        $this->defineDatabaseFile();

        parent::setUp();
    }

    private function defineDatabaseFile(): void
    {
        define("DATA_PATH", 'src' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'data.test.json');
    }
}