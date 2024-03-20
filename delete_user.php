<?php
function deleteUser($userId) {
  $mysqli = new mysqli("localhost", "root", "", "tieu_db");  

  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }


  $userId = $mysqli->real_escape_string($userId);

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
  return $message; 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $userId = $_POST['user_id'];
  $message = deleteUser($userId);  

  // **Session check for admin privileges (**Optional)**
  // Uncomment the following block if deletion requires admin rights
  if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    ?>
    <form id="deleteForm" method="POST" action="">
        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
        <input type="submit" value="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
    </form>
  <?php
  } else {
    echo "Unauthorized access! You require admin privileges to delete users.";
    exit;
  }
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
      window.location.href = "admin.php";  
    </script>
  <?php endif; ?>
</body>
</html>
