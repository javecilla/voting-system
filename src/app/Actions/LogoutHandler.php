<?php

if(isset($_GET['logout']) && !empty($_GET['logout']) && $_GET['logout'] === '1') {
	session_unset();
	session_destroy();
	header('Location: http://127.0.0.1:8080/auth/login/');
	exit();
}
