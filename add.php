<?php
	session_start();
	include 'includes/loader.php';
	checkLoggedInStatus();
	date_default_timezone_set('Asia/Ho_Chi_Minh');

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		try {
			$mysqli = getDbConnection();
			$title = $_POST['title'];
			$details = $_POST['details'];
			$user_id = $_SESSION['user_id'];
			createPost($mysqli, $title, $details, $user_id);
		} catch (Exception $e) {
			handleException($e);
		} finally {
			header("location:home.php");
		}
	}
?>
