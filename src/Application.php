<?php

namespace BatsiraiMuchareva\LiveUserDashboard;

use BatsiraiMuchareva\LiveUserDashboard\Router\Router;
use BatsiraiMuchareva\LiveUserDashboard\Services\SessionService;

class Application
{
    private static ?self $instance = null;

    public Router $router;

    public SessionService $session;

    public function __construct()
    {
        $this->router = new Router();
        $this->session = new SessionService();
    }

    public function run(): void
    {
        $this->router->execute();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}