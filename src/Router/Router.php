<?php

namespace BatsiraiMuchareva\LiveUserDashboard\Router;


use BatsiraiMuchareva\LiveUserDashboard\Application;
use BatsiraiMuchareva\LiveUserDashboard\Services\UserService;

class Router
{
    protected array $routes = [];

    public function getRequestMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getRequestData(): array
    {
        $inputData = [];

        if ($this->getRequestMethod() === 'post') {
            // Get the raw data from the request body
            $rawData = file_get_contents("php://input");

            // Attempt to decode the JSON data
            $jsonData = json_decode($rawData, true);

            if ($jsonData !== null) {
                // If JSON decoding is successful, use the decoded data
                $inputData = array_map('htmlspecialchars', $jsonData);
            } else {
                // If JSON decoding fails, treat it as form data
                foreach ($_POST as $key => $value) {
                    $inputData[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }

        return $inputData;
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
        $pattern = preg_replace('/\/\{([a-zA-Z0-9_-]+)\}/', '/(?<$1>[a-zA-Z0-9_-]+)', $path);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';

        $this->routes['post'][$pattern] = ['callback' => $callback];

//        $this->routes['post'][$path] = $callback;
    }

    private function setResponseHeaders(): void
    {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, X-Token");
    }

    private function handleOptionsRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $this->setResponseHeaders();
            http_response_code(200);
            exit;
        }
    }

    public function execute(): void
    {
        $this->setResponseHeaders();

        $this->handleOptionsRequest();


        $path = $this->getPath();
        $method = $this->getRequestMethod();

        $unauthenticatedPaths = ['/login'];

        if (!in_array($path, $unauthenticatedPaths) && !$this->isUserAuthenticated()) {
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        foreach ($this->routes[$method] as $pattern => $route) {

            if (preg_match($pattern, $path, $matches)) {

                array_shift($matches);

                $params = ['params' => array_merge($matches, $this->getRequestData())];

                $controller = new $route['callback'][0]();
                $response = call_user_func_array([$controller, $route['callback'][1]], empty($params['params']) ? [] : $params);

                echo json_encode([
                    'data' => $response,
                    'user' => Application::user() ?? null
                ]);

                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

    private function isUserAuthenticated(): bool
    {
        $headers = $this->getHeaders();

        if (isset($headers['X-Token'])) {

            $token = $headers['X-Token'];

            list($encodedUserID, $hashedCheck) = explode('|', base64_decode($token));

            if (md5($encodedUserID . UserService::SECRET_KEY) === $hashedCheck) {

                Application::getInstance()->session->set('user_id', $encodedUserID);

                return true;
            } else {

                // Token is invalid
                return false;
            }
        }

        return false;
    }

    public function getHeaders(): false|array
    {
        return  getallheaders();

    }
}