<html>
	<head>
		<title>Login</title>
        <link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<h2>Login</h2>
		<?php
            session_start();
            displayAndClearMessages();
        ?>
		<form action="checklogin.php" method="POST" class="auth-form">
			<input type="text" name="username" placeholder="Username" required="required"/> <br/>
			<input type="password" name="password" placeholder="Password" required="required" /> <br/>
			<div class="button-container">
				<input type="submit" class="submit-button" value="Login"/>
			</div>
		</form>
	</body>
</html>