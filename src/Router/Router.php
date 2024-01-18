<?php

namespace BatsiraiMuchareva\LiveUserDashboard\Router;


class Router
{
    protected array $routes = [];

    public function getRequestMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getRequestData()
    {
        $inputData = array();

        if($this->getRequestMethod() === 'post') {

            foreach($_POST as $key => $value){
                $inputData[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $inputData;
        }
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'];
        $queryQuestionMarkPos = strpos($path, '?');

        if ($queryQuestionMarkPos === false) {
            return $path;
        }

        return substr($path, 0, $queryQuestionMarkPos);
    }

    public function get($path, $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }

    public function execute(): void
    {
        header('Content-Type: application/json');

        $path = $this->getPath();
        $method = $this->getRequestMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            return;
        }

        $controller = new $callback[0]();
        $response = call_user_func([$controller, $callback[1]], $this->getRequestData());

        echo json_encode($response);
    }
}