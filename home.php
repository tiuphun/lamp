<?php
	session_start(); 
	include 'includes/loader.php';
	checkLoggedInStatus();
	displayAndClearMessages();

	$user = $_SESSION['user'];
	$usertype = $_SESSION['usertype'];

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
		<h2>Hello <?php Print "$user"?>👋</h2>
			<?php echo getAddPostForm(); ?>
		<h2 align="center">Posts</h2>
			<?php 	$query = fetchPosts($mysqli);
					echo generateTableHTML($query); ?>
		<script>
			function confirmDelete(id) {
				if (confirm('Are you sure you want to delete this record?')) {
					window.location.assign('delete.php?id=' + id);
				}
			}
		</script>
	</body>
</html>