<?php


if(isset($_GET['cuser']) && !empty($_GET['cuser'])) {
	session_start();
	$_SESSION['currentUser'] = $_GET['cuser'];
	$_SERVER['isLogin'] = true;
	header("Location: /admin/dashboard/");
	exit();
} else {
	echo "An error occured.";
}