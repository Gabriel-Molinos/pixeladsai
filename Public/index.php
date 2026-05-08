<?php

define('BASE_PATH', dirname(__DIR__));
session_start();

require BASE_PATH . '/vendor/autoload.php';

require BASE_PATH . '/app/Core/Functions.php';

require BASE_PATH . '/app/Core/Bootstrap.php';

use App\Core\Route;

$router = new Route();

require BASE_PATH . '/Routes/web.php';