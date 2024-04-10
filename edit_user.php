<?php
	include 'includes/loader.php';
	session_start();
	checkAdminStatus();

	try {
		$mysqli = getDbConnection();
		$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		$user = fetchUserById($mysqli, $user_id);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			verifyCsrfToken($_POST['csrf_token']);
			
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
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
	</head>
		<body>
		<h1>Edit User</h1>
			<?php echo getEditUserForm($user); ?>
		</body>
</html>
