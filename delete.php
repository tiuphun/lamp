<?php
session_start();

if (!isset($_SESSION['user'])) {
  header("location:index.php"); // Redirect if not logged in
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
  $mysqli = new mysqli("localhost", "root", "", "tieu_db");

  if ($mysqli->connect_error) {
    error_log("Connection failed: " . $mysqli->connect_error);
    die("Connection failed. Please try again later.");
  }

  $id = (int)$_GET['id'];

  $sql = "DELETE FROM post WHERE id = ?";
  $stmt = $mysqli->prepare($sql);

  // Bind parameter
  $stmt->bind_param('i', $id); // 'i' specifies integer type for parameter

  // Execute the prepared statement with error handling
  if ($stmt->execute()) {
    $_SESSION['message'] = 'Post deleted successfully!';
  } else {
    $_SESSION['message'] = 'Failed to delete post: ' . $stmt->error;  // Use $stmt->error
  }

  // Close the statement and connection
  $stmt->close();
  $mysqli->close();

  header("location: home.php"); // Redirect after processing
}
?>
