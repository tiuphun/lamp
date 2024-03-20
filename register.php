<html>
	<head>
		<title>Tiểu's Website ✨</title>
        <link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
	</head>
	<body>
		<h2>Registration Page</h2>
		<form action="index.php" method="get">
			<button type="submit" class="back-button">&#8592; Back</button>
		</form>
		<form action="register.php" method="POST" class="auth-form">
			Enter Username: <input type="text" name="username" required="required"/> <br/>
			Enter Password: <input type="password" name="password" required="required" /> <br/>
			<div class="button-container">
				<input type="submit" class="submit-button" value="Register"/>
			</div>
		</form>
	</body>
</html>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$mysqli = new mysqli("localhost", "root", "", "tieu_db");
	$username = $mysqli->real_escape_string($_POST['username']);
	$password = $mysqli->real_escape_string($_POST['password']);
	$usertype = "user";
    $bool = true;

	$query = $mysqli->query("SELECT * FROM users");
    while($row = $query->fetch_assoc()) {
        $table_users = $row['username'];
        if($username == $table_users) {
            $bool = false;
            echo '<script>alert("Username has been taken!");</script>';
            echo '<script>window.location.assign("register.php");</script>';
        }
    }

    if($bool) {
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $mysqli->query("INSERT INTO users (username, password, usertype) VALUES ('$username','$hashed_password', '$usertype')");
        echo '<script>alert("Successfully Registered!");</script>';
		echo '<script>window.location.assign("register.php");</script>';
	}
}
?>