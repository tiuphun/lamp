<?php
	include 'utils.php';
	session_start();
	checkAdminStatus();

	try {
		$mysqli = getDbConnection();
		$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

		if ($user_id <= 0) {
			throw new Exception('Invalid user ID.');
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$username = $_POST['username'];
			$newPassword = $_POST['new_password'];
			$confirmPassword = $_POST['confirm_password'];

			if (empty($username) || empty($newPassword) || empty($confirmPassword)) {
				throw new Exception("Please fill in all the fields.");
			} elseif ($newPassword !== $confirmPassword) {
				throw new Exception("The new passwords do not match.");
			} else {
				$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
				$sql = "UPDATE user SET username = ?, password = ? WHERE id = ?";
				$stmt = $mysqli->prepare($sql);
				$stmt->bind_param('sss', $username, $hashedPassword, $user_id);

				if (!$stmt->execute()) {
					throw new Exception('Error updating user": ' . $stmt->error);
				}
				
				$_SESSION['message'] = 'User edited successfully!';
                header("Location: admin.php");
                exit;
			}
		}

		$sql = "SELECT * FROM user WHERE id = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows === 1) {
			$user = $result->fetch_assoc();
		} else {
			throw new Exception('User not found.');
		}
	} catch (Exception $e) {
		error_log($e->getMessage());
		$_SESSION['error_message'] = $e->getMessage();
        header("Location: admin.php");
        exit;
	} finally {
		$mysqli->close();
	}
?>

<html>
	<head>
		<title>Edit User</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<h1>Edit User</h1>
		<form method="POST" action="" class="auth-form">
			<input type="text" name="username" id="username" placeholder="New username" value="<?php echo $user['username']; ?>" required><br>
			<input type="password" name="new_password" id="new_password" placeholder="New password" required><br>
			<input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required><br>
			<input type="submit" class="submit-button" value="Edit User">
		</form>
	</body>
</html>