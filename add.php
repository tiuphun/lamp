<?php
session_start();

if (!isset($_SESSION['user'])) {
} else {
  header("location:index.php");
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $mysqli = new mysqli("localhost", "root", "", "tieu_db");

  $title = $_POST['title'];
  $details = $_POST['details'];
  $time = strftime("%X");
  $date = strftime("%B %d, %Y");

  // Get the user ID from the session (assuming you have it stored)
  $user_id = $_SESSION['user_id']; // Replace with the appropriate way to get user ID

  // Prepare the INSERT statement with user_id column
  $sql = "INSERT INTO post (title, details, date_posted, time_posted, user_id)
          VALUES (?, ?, ?, ?, ?)";
  $stmt = $mysqli->prepare($sql);
  // echo $mysqli->error;

  // Bind parameters using data types (include user_id)
  $stmt->bind_param('ssssi', $title, $details, $date, $time, $user_id);
  // echo $mysqli->error;

  // Execute the prepared statement
  if ($stmt->execute()) {
    header("location: home.php");
  } else {
    echo "Error creating post: " . $mysqli->error;
  }

  // Close the statement and connection
  $stmt->close();
  $mysqli->close();
} else {
  header("location:home.php");
}
?>
