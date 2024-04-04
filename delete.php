<?php
	session_start();
	include 'includes/loader.php';
	checkLoggedInStatus();

	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		try {
			$mysqli = getDbConnection();
			$id = (int)$_GET['id'] ? (int)$_GET['id'] : 0;
			deletePost($mysqli, $postId);
		} catch (Exception $e) {
			handleException($e);
		} 
	}
?>
