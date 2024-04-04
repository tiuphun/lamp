<?php
	session_start();
	include 'utils.php';
	checkLoggedInStatus();

	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		try {
			$mysqli = getDbConnection();

			$id = (int)$_GET['id'];
			$sql = "DELETE FROM post WHERE id = ?";
			$stmt = $mysqli->prepare($sql);
			if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $mysqli->error);
            }

			$stmt->bind_param('i', $id);
			if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }

			$_SESSION['message'] = 'Post deleted successfully!';
            header("Location: home.php");
            exit;
		} catch (Exception $e) {
			error_log($e->getMessage());
			$_SESSION['error_message'] = 'An error occurred. Please try again later.';
		} finally {
			$stmt->close();
			$mysqli->close();
		}
	}
?>
