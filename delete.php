<?php
	session_start();
	include 'includes/loader.php';
	checkLoggedInStatus();

	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		try {
			$mysqli = getDbConnection();
			$post_id = (int)$_GET['post_id'] ? (int)$_GET['post_id'] : 0;
			deletePost($mysqli, $post_id);
		} catch (Exception $e) {
			handleException($e);
		} 
	}
?>
