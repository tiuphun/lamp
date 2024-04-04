<?php
	session_start();
	include 'includes/loader.php';
	checkAdminStatus();
    displayAndClearMessages();
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrfToken($_POST['csrf_token']);
		try {
            $mysqli = getDbConnection();
            $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
            deleteUser($mysqli, $userId);
        } catch (Exception $e) {
        	handleException($e);
    	} 
	}
?>
