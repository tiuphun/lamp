<?php
	session_start();
	include 'includes/loader.php';
	checkLoggedInStatus();
	$user_id = $_SESSION['user_id'];
	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		try {
			$mysqli = getDbConnection();
			$post_id = (int)$_GET['id'] ? (int)$_GET['id'] : 0;
			deletePost($mysqli, $post_id);
		} catch (Exception $e) {
			handleException($e);
		} 
	}
?>
