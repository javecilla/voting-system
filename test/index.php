<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\Router;

Router::handle('GET', '/', './build/home.php');