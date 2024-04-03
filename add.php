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

  $user_id = $_SESSION['user_id']; // Replace with the appropriate way to get user ID

  $sql = "INSERT INTO post (title, details, date_posted, time_posted, user_id)
          VALUES (?, ?, ?, ?, ?)";
  $stmt = $mysqli->prepare($sql);

  $stmt->bind_param('ssssi', $title, $details, $date, $time, $user_id);

  if ($stmt->execute()) {
    header("location: home.php");
  } else {
    error_log("Error creating post: " . $stmt->error);
    echo "An error occurred. Please try again later.";
  }

  $stmt->close();
  $mysqli->close();
} else {
  header("location:home.php");
}
?>
