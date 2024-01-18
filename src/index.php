<?php


define('ROOT_DIR', dirname(__DIR__).DIRECTORY_SEPARATOR);
const DATA_PATH = ROOT_DIR . 'src' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'data.json';

require_once ROOT_DIR .'vendor/autoload.php';

use BatsiraiMuchareva\LiveUserDashboard\Application;

$app = Application::getInstance();

require_once ROOT_DIR.'src'.DIRECTORY_SEPARATOR.'routes.php';

$app->run();