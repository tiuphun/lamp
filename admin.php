<html>
	<head>
		<title>Admin Page</title>
        <link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
        <h1>Users</h1>
    </body>
</html>

<?php
    session_start();
    include 'utils.php';
    checkAdminStatus();
    displayAndClearMessages();

    try {
        $mysqli = getDbConnection();
        $result = $mysqli->query('SELECT * FROM user');
        if (!$result) {
            throw new Exception('Query failed: ' . $mysqli->error);
        }
        $users = $result->fetch_all(MYSQLI_ASSOC);
        echo generateUserTableHTML($users);
        echo '
            <h2>Add User</h2>
            <form action="add_user.php" method="POST" class="auth-form">
                <input type="text" name="username" placeholder="Username" id="username" required></br>
                <input type="password" name="password" placeholder="Password" id="password" required></br>
                <input type="submit" class="submit-button" value="Add User">
            </form>';
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo 'An error occurred. Please try again later.';
    } finally {
        $mysqli->close();
    }
?>