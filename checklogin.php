<?php
	session_start();
	include 'includes/loader.php';
	try {
		$mysqli = getDbConnection();
		$username = $_POST['username'];
		$password = $_POST['password'];
		loginUser($mysqli, $username, $password);
	} catch (Exception $e) {
		handleException($e);
		header("Location: login.php");
		exit;
	} 
?>
