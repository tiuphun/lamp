<?php
	session_start();
	include 'includes/loader.php';
	checkAdminStatus();
	displayAndClearMessages();
	try {
		$mysqli = getDbConnection();
		$newUsername = $_POST['username'];
		$newPassword = $_POST['password'];
		addUser($mysqli, $username, $password);
	} catch (Exception $e) {
		handleException($e);
		if ($stmt->errno === 1062) {  
			echo "Username already exists. Please choose a different username.";
		}
	}
?>
