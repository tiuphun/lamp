<?php
	session_start();
	include 'utils.php';
	checkAdminStatus();
	try {
		$mysqli = getDbConnection();

		$newUsername = $_POST['username'];
		$newPassword = $_POST['password'];
		$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

		$sql = "INSERT INTO user (username, password) VALUES (?, ?)";
		$stmt = $mysqli->prepare($sql);

		if (!$stmt) {
			throw new Exception('Prepare failed: ' . $mysqli->error);
		}
		$stmt->bind_param('ss', $newUsername, $newHashedPassword);

		if (!$stmt->execute()) {
			throw new Exception('Error adding user: ' . $stmt->error);
			echo "New user added successfully!";
		} 
	} catch (Exception $e) {
		error_log($e->getMessage());
		echo 'An error occurred. Please try again later.';
		if ($stmt->errno === 1062) {  
			echo "Username already exists. Please choose a different username.";
		}
	} finally {
		$stmt->close();
		$mysqli->close();
	}
?>

<html>
	<head>
		<title>Add User</title>
	</head>
	<body>
		<script>
			alert("<?php echo $message; ?>");
			window.location.href = "admin.php"; 
		</script>
	</body>
</html>
