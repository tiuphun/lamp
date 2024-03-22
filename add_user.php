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

// Retrieve user input (no need for escaping as we're using prepared statements)
$newUsername = $_POST['username'];
$newPassword = $_POST['password'];

// Hash password before storing in database
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Prepare the INSERT statement with placeholders
$sql = "INSERT INTO user (username, password) VALUES (?, ?)";
$stmt = $mysqli->prepare($sql);

// Bind parameters
$stmt->bind_param('ss', $newUsername, $newHashedPassword);

// Execute the prepared statement
if ($stmt->execute()) {
  $message = "New user created successfully!";
} else {
  $message = "Error: " . $stmt->error;  // Use $stmt->error for prepared statements


  if ($stmt->errno === 1062) {  
    $message .= "<br>Username already exists. Please choose a different username.";
  }
}

$stmt->close();
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
    window.location.href = "admin.php"; 
  </script>
</body>
</html>
