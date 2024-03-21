<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "tieu_db");

if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve user input (no escaping needed with prepared statements)
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare the SELECT statement with placeholder
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $mysqli->prepare($sql);

// Bind parameter
$stmt->bind_param('s', $username);

// Execute the prepared statement
if ($stmt->execute()) {
  $result = $stmt->get_result(); // Get the result of the prepared statement
  $user = $result->fetch_assoc(); // Fetch the user data (if any)
  
  if ($user) { // User found, check password using password_verify
    if (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $username;
      $_SESSION['usertype'] = $user['usertype'];
      header("location: home.php");
    } else {
      echo '<script>alert("Incorrect Password!");</script>';
      echo '<script>window.location.assign("login.php");</script>';
    }
  } else {
    echo '<script>alert("Username does not exist!");</script>';
    echo '<script>window.location.assign("login.php");</script>';
  }
} else {
  echo "Error: " . $stmt->error; // Use $stmt->error for prepared statements
}

// Close resources (prepared statement and connection)
$stmt->close();
$mysqli->close();
?>
