<?php

define('ROOT_DIR', dirname(__DIR__).DIRECTORY_SEPARATOR);
const DATA_PATH =  'src' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'data.json';

require_once 'vendor/autoload.php';

date_default_timezone_set('Africa/Johannesburg');

use BatsiraiMuchareva\LiveUserDashboard\Application;

$app = Application::getInstance();

require_once  'src' . DIRECTORY_SEPARATOR . 'routes.php';

$app->run();