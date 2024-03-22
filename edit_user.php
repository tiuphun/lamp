<?php
session_start();

if ($_SESSION['usertype'] !== 'admin') {
    echo 'You do not have permission to access this page.';
    header('Location: home.php');
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'tieu_db');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Check if the user ID is provided in the URL query string
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validate user ID (optional, you can add more validation here)
if ($user_id <= 0) {
  echo "Invalid user ID.";
  exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the form data
  $username = $_POST['username'];
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_password'];

  // Validate the form data
  if (empty($username) || empty($newPassword) || empty($confirmPassword)) {
    echo "Please fill in all the fields.";
  } elseif ($newPassword !== $confirmPassword) {
    echo "The new passwords do not match.";
  } else {
    // Hash the new password before updating
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $sql = "UPDATE user SET username = ?, password = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sss', $username, $hashedPassword, $user_id);

    if ($stmt->execute()) {
        echo "<script type='text/javascript'>
                alert('User edited successfully!');
                window.location.href = 'admin.php';
              </script>";
      } else {
        echo "Error updating user: " . $mysqli->error;
    }

    $stmt->close();
  }
}

// Fetch the user details based on the ID
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();
} else {
  echo "User not found.";
  exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
</head>
<body>
  <h1>Edit User</h1>
  <form method="POST" action="" class="auth-form">
    <!-- <input type="hidden" name="id" value="<?php echo $user['id']; ?>">  <label for="username">Username:</label> -->
    <input type="text" name="username" id="username" placeholder="New username" value="<?php echo $user['username']; ?>" required><br>

    <!-- <label for="new_password">New Password:</label> -->
    <input type="password" name="new_password" id="new_password" placeholder="New password" required><br>

    <!-- <label for="confirm_password">Confirm New Password:</label> -->
    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required><br>

    <input type="submit" class="submit-button" value="Edit User">
  </form>
</body>
</html>