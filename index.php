<?php
require_once __DIR__ . '/src/vendor/autoload.php';

use App\Router;

// #ROUTES FOR ADMIN SIDE PANEL
Router::handle('GET', '/admin/dashboard/', './build/admin/dashboard.php');
Router::handle('GET', '/admin/candidate-management/', './build/admin/cmanagement.php');
Router::handle('GET', '/admin/voting-records/', './build/admin/vrecords.php');

// #ROUTES FOR USER PAGE NAVIGATION
Router::handle('GET', '/select-campus/', './build/user/campus.php');
Router::handle('GET', '/candidates/sta-maria/', './build/user/stamaria.php');
Router::handle('GET', '/candidates/balagtas/', './build/user/balagtas.php');