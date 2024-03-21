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

  // Prepare the INSERT statement
  $sql = "INSERT INTO post (title, details, date_posted, time_posted)
          VALUES (?, ?, ?, ?)";
  $stmt = $mysqli->prepare($sql);

  // Bind parameters using data types
  $stmt->bind_param('ssss', $title, $details, $date, $time);

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
