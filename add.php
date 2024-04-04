<?php
	session_start();
	include 'utils.php';
	checkLoggedInStatus();
	date_default_timezone_set('Asia/Ho_Chi_Minh');

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		try {
			$mysqli = getDbConnection();

			$title = $_POST['title'];
			$details = $_POST['details'];
			$time = strftime("%X");
			$date = strftime("%B %d, %Y");
			$user_id = $_SESSION['user_id'];

			$sql = "INSERT INTO post (title, details, date_posted, time_posted, user_id)
					VALUES (?, ?, ?, ?, ?)";
			$stmt = $mysqli->prepare($sql);
			if (!$stmt = $mysqli->prepare($sql)) {
				throw new Exception("Prepare failed: " . $mysqli->error);
			}
			
			$stmt->bind_param('ssssi', $title, $details, $date, $time, $user_id);
			if (!$stmt->execute()) {
				throw new Exception("Execute failed: " . $stmt->error);
			}
		} catch (Exception $e) {
			error_log($e->getMessage());
			echo "An error occurred. Please try again later.";
		} finally {
			$stmt->close();
			$mysqli->close();
			header("location:home.php");
		}
	}
?>
