<?php
	include 'includes/loader.php';
	session_start();
	checkAdminStatus();

	try {
		$mysqli = getDbConnection();
		$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		$user = fetchUserById($mysqli, $userId);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$username = $_POST['username'];
			$newPassword = $_POST['new_password'];
			$confirmPassword = $_POST['confirm_password'];
			editUser($mysqli, $user_id, $username, $newPassword, $confirmPassword);
		}
	} catch (Exception $e) {
		handleException($e);
        header("Location: admin.php");
        exit;
	}
?>

<html>
	<head>
		<title>Edit User</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
		<body>
			<?php echo getEditUserForm($user); ?>
		</body>
</html>
