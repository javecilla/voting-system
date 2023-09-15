<?php
require_once __DIR__ . '/src/vendor/autoload.php';

use App\Router;

// #ROUTES FOR ADMIN SIDE PANEL
Router::handle('GET', '/admin/dashboard/', './build/admin/dashboard.php');
Router::handle('GET', '/admin/voting-records/', './build/admin/vrecords.php');
Router::handle('GET', '/admin/candidate-management/', './build/admin/cmanagement.php');
Router::handle('GET', '/admin/candidates-ranking/', './build/admin/crankings.php');

// #ROUTES FOR USER PAGE NAVIGATION
Router::handle('GET', '/buwan-ng-wikang-pambansa-2023-lakan-lakanbini-lakandyosa/', './build/user/campus.php');
Router::handle('GET', '/lakan-lakanbini-lakandyosa-candidates/sta-maria-campus/', './build/user/stamaria.php');
Router::handle('GET', '/lakan-lakanbini-lakandyosa-candidates/balagtas-campus/', './build/user/balagtas.php');