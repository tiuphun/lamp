<html>
	<head>
		<title>Tiểu's Website ✨</title>
        <link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
	</head>
	<body>
		<h2>Login Page</h2>
		<form action="index.php" method="get">
			<button type="submit" class="back-button">&#8592; Back</button>
		</form>
		<form action="checklogin.php" method="POST" class="auth-form">
			Enter Username: <input type="text" name="username" required="required"/> <br/>
			Enter Password: <input type="password" name="password" required="required" /> <br/>
			<div class="button-container">
				<input type="submit" class="submit-button" value="Login"/>
			</div>
		</form>
	</body>
</html>