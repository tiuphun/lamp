<?php
session_start();
  if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    echo 'You do not have permission to access this page.';
    exit;
  }
function deleteUser($userId) {
  $mysqli = new mysqli("localhost", "root", "", "tieu_db");  

  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }

  $sql = "DELETE FROM user WHERE id = ?";
  $stmt = $mysqli->prepare($sql);

  $stmt->bind_param('i', $userId);  // 'i' specifies integer type for the parameter

  if ($stmt->execute()) {
    $message = "User deleted successfully!";
  } else {
    $message = "Error deleting user: " . $mysqli->error;
  }

  $stmt->close();
  $mysqli->close();

  return $message;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $userId = (int)$_POST['user_id'];  // Cast to integer for additional validation
  $message = deleteUser($userId); 


  // Display the message and redirect (optional)
  if (isset($message)): ?>
    <script>
      alert("<?php echo $message; ?>");
      window.location.href = "admin.php";  // Redirect after deletion (optional)
    </script>
  <?php endif;
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
      window.location.href = "admin.php";  // Redirect after deletion (optional)
    </script>
  <?php endif; ?>
</body>
</html>
