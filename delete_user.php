<?php
	session_start();
	include 'utils.php';
	checkAdminStatus();
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		try {
            $mysqli = getDbConnection();

            $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
            if ($userId <= 0) {
                throw new Exception("Invalid user ID.");
            }

            $sql = "DELETE FROM user WHERE id = ?";
            $stmt = $mysqli->prepare($sql);

            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $mysqli->error);
            }

            $stmt->bind_param('i', $userId);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }

            echo "<script type='text/javascript'>
                    alert('User deleted successfully!');
                    window.location.href = 'admin.php';
                </script>";
        } catch (Exception $e) {
        	error_log($e->getMessage());
        	$_SESSION['error_message'] = 'An error occurred. Please try again later.';
    	} finally {
			$stmt->close();
			$mysqli->close();
    	}
	}
?>

<html>
    <head>
        <title>Delete User</title>
    </head>
    <body>
        <?php if (isset($_SESSION['error_message'])): ?>
            <script>
                alert("<?php echo htmlspecialchars($_SESSION['error_message']); ?>");
                window.location.href = "admin.php"; 
            </script>
        <?php 
            unset($_SESSION['error_message']);
        endif; ?>
    </body>
</html>
