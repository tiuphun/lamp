<?php

// Function to delete a user (**Security Measures Included**)
function deleteUser($userId) {
  $mysqli = new mysqli("localhost", "root", "", "tieu_db");  // Replace with your database connection details

  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }

  // **Sanitize user ID**
  $userId = $mysqli->real_escape_string($userId);

  // **Check if user exists**
  $query = $mysqli->query("SELECT * FROM users WHERE id = $userId");

  if ($query->num_rows == 1) {
    $sql = "DELETE FROM users WHERE id = $userId";

    if ($mysqli->query($sql) === TRUE) {
      $message = "User deleted successfully!";
    } else {
      $message = "Error deleting user: " . $mysqli->error;
    }
  } else {
    $message = "User with ID $userId not found!";
  }

  $mysqli->close();

  return $message;  // Return the message
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the user ID from the form
  $userId = $_POST['user_id'];

  // **Session check for admin privileges (**Optional)**
  // Uncomment the following block if deletion requires admin rights
//   if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
//     // Proceed with deletion
//   } else {
//     echo "Unauthorized access! You require admin privileges to delete users.";
//     exit;
//   }

  // Prompt the admin for confirmation
//   if (confirm('Are you sure you want to delete this user?')) {
//     $message = deleteUser($userId);  // Call the delete function
//   }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Delete User</title>
</head>
<body>
  <?php if (isset($message)): ?>
    <script>
      alert("<?php echo $message; ?>");
      window.location.href = "admin.php";  // Redirect to admin page
    </script>
  <?php endif; ?>

    <form id="deleteForm" method="POST" action="">
        <input type="hidden" name="user_id" value="1">
        <input type="submit" value="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
    </form>
</body>
</html>
