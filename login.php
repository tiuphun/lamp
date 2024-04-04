<html>
	<head>
		<title>Login</title>
        <link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<h2>Login</h2>
		<?php
            session_start();
			include 'includes/loader.php';
            displayAndClearMessages();
			echo getLoginForm();
        ?>
	</body>
</html>