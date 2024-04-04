<?php
	session_start();
	include 'utils.php';
	try {
		$mysqli = getDbConnection();

		$username = $_POST['username'];
		$password = $_POST['password'];
		$sql = "SELECT * FROM user WHERE username = ?";
		$stmt = $mysqli->prepare($sql);

		if (!$stmt) {
			throw new Exception("Prepare failed: " . $mysqli->error);
		}
		$stmt->bind_param('s', $username);

		if (!$stmt->execute()) {
			throw new Exception("Execute failed: " . $stmt->error);
		}
		$result = $stmt->get_result(); 
		$user = $result->fetch_assoc(); 
		
		if ($user && password_verify($password, $user['password'])) {
			$_SESSION['user'] = $username;
			$_SESSION['usertype'] = $user['usertype'];
			$_SESSION['user_id'] = $user['id'];
			header("location: home.php");
			exit;
		} else {
			$_SESSION['error_message'] = 'Incorrect username or password.';
			header("Location: login.php");
			exit;
		}	
	} catch (Exception $e) {
		error_log($e->getMessage());
		$_SESSION['error_message'] = 'An error occurred. Please try again later.';
		header("Location: login.php");
		exit;
	} finally {
		$stmt->close();
		$mysqli->close();
	}
?>
