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

        return [];
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
        $pattern = preg_replace('/\/\{([a-zA-Z0-9_-]+)\}/', '/(?<$1>[a-zA-Z0-9_-]+)', $path);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';

        $this->routes['get'][$pattern] = ['callback' => $callback];
    }
    public function post($path, $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }

    private function setResponseHeaders(): void
    {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
    }

    private function handleOptionsRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $this->setResponseHeaders();
            exit;
        }
    }

    public function execute(): void
    {
        $this->setResponseHeaders();

        $this->handleOptionsRequest();


        $path = $this->getPath();
        $method = $this->getRequestMethod();

        foreach ($this->routes[$method] as $pattern => $route) {

            if (preg_match($pattern, $path, $matches)) {

                array_shift($matches);

                $params = ['params' => array_merge($matches, $this->getRequestData())];

                $controller = new $route['callback'][0]();
                $response = call_user_func_array([$controller, $route['callback'][1]], empty($params['params']) ? [] : $params);

                echo json_encode($response);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}