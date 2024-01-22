<?php

namespace BatsiraiMuchareva\LiveUserDashboard\Services;

class SessionService
{
    public function __construct()
    {
        session_start();
    }

    public function set($key, $value){
        return $_SESSION[$key] = $value;
    }

    public function get($key){
        return $_SESSION[$key] ?? false;
    }
}