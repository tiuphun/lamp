<?php
session_start();

// Validate user access (should be an admin)
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
  echo 'You do not have permission to access this page.';
  exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'tieu_db');

if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

// **Sanitize user input to prevent SQL injection**
$newUsername = $mysqli->real_escape_string($_POST['username']);
$newPassword = $_POST['password'];

// **Hash password before storing in database**
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Create a new user in the database
$sql = "INSERT INTO users (username, password) VALUES ('$newUsername', '$newHashedPassword')";

if ($mysqli->query($sql) === TRUE) {
  $message = "New user created successfully!";
} else {
  $message = "Error: " . $sql . "<br>" . $mysqli->error;

  // Check for duplicate username error
  if (strpos($mysqli->error, "Duplicate entry")) {
    $message .= "<br>Username already exists. Please choose a different username.";
  }
}

$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Add User</title>
</head>
<body>
  <script>
    alert("<?php echo $message; ?>");
    window.location.href = "admin.php"; // Redirect to admin page after message
  </script>
</body>
</html>
