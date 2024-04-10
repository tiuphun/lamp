<?php
	session_start();
	include 'includes/loader.php';
?>
<html>
	<head>
		<title>Login</title>
        <link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
	</head>
	<body>
		<h2>Login</h2>
		<?php
            displayAndClearMessages();
			echo getLoginForm();
        ?>
	</body>
</html>