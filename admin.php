<html>
	<head>
		<title>Tiểu's Website ✨</title>
        <link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
	</head>
	<body>
        <h1>Admin Page</h1>
        <form action="index.php" method="get">
			<button type="submit" class="back-button">&#8592; Back</button>
		</form>
    </body>
</html>

<?php
session_start();
// Check if the logged in user has the usertype 'admin'
if ($_SESSION['usertype'] === 'admin') {
    $mysqli = new mysqli('localhost', 'root', '', 'tieu_db');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    // Fetch the list of users from the database
    $result = $mysqli->query('SELECT * FROM user');
    $users = $result->fetch_all(MYSQLI_ASSOC);
    // Display the list of users as a table
    echo '<h2>Users</h2>';
    echo '<table>';
    echo '<tr><th>Username</th><th>Password</th><th>Actions</th></tr>';
    // Fetch the list of users from the database and loop through them
    foreach ($users as $user) {
        echo '<tr>';
        echo '<td>' . $user['username'] . '</td>';
        echo '<td>' . $user['password'] . '</td>';
        echo '<td><button onclick="location.href=\'edit_user.php?id=' . $user['id'] . '\'" type="button">Edit</button>  ';
        echo '<form action="delete_user.php" method="POST" style="display: inline;">';
        echo '<input type="hidden" name="user_id" value="' . $user['id'] . '">';
        echo '<button type="submit" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</button>';
        echo '</form>';
        echo '</td>';        
        echo '</tr>';
    }
    echo '</table>';

    // Display the form for adding a new user
    echo '<h2>Add User</h2>';
    echo '<form action="add_user.php" method="POST" class="auth-form">';
    echo '<label for="username">Username:</label>';
    echo '<input type="text" name="username" id="username" required></br>';
    echo '<label for="password">Password:</label>';
    echo '<input type="password" name="password" id="password" required></br>';
    echo '<input type="submit" class="submit-button" value="Add User">';
    echo '</form>';
} else {
    echo 'You do not have permission to access this page.';
}
?>