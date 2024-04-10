<?php
	session_start(); 
	include 'includes/loader.php';
	checkLoggedInStatus();
	displayAndClearMessages();

	$username = $_SESSION['user'];
	$usertype = $_SESSION['usertype'];
	$user_id = $_SESSION['user_id'];

	try {
		$mysqli = getDbConnection();
	} catch (Exception $e) {
		handleException($e);
	}
?>

<html>
	<head>
		<title>Home</title>
        <link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
	</head>
	<body>
		<h1>Home</h1>
		<h2>Hello <?php Print "$username"?>👋</h2>
			<?php echo getAddPostForm(); ?>
		<h2 align="center">Posts</h2>
			<?php 	$query = fetchPosts($mysqli);
					echo generateTableHTML($query); ?>
		<script>
			function confirmDelete(post_id) {
				if (confirm('Are you sure you want to delete this record?')) {
					window.location.assign('delete.php?id=' + post_id);
				}
			}
		</script>
	</body>
</html>