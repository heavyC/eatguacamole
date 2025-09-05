<?php
	//ini_set('display_errors', 1);
	//error_reporting(E_ALL);

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	if (!logged_in()) {
		redirect_to("login.php");
	}
	#require_once("includes/header.php");

	//
	session_start();
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-5000, '/');
	}
	session_destroy();

	redirect_to("login.php?logout=1");
?>