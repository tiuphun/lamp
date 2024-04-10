<?php
	session_start();
	include 'includes/loader.php';
	checkAdminStatus();
	displayAndClearMessages();
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// verifyCsrfToken($_POST['csrf_token']);
		try {
			$mysqli = getDbConnection();
			// $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
			// $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
			$username = $_POST['username'];
			$password = $_POST['password'];
			$stmt = addUser($mysqli, $username, $password);
		} catch (Exception $e) {
			handleException($e);
			if (isset($stmt) && $stmt->errno === 1062) {  
				$_SESSION['error_message'] = "Username already exists. Please choose a different username.";
			}
		}
		header("Location: admin.php");
	}
?>
