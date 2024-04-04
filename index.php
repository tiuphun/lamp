<?php
    session_start();
    include 'utils.php';
	include 'nav.php';
?>

<html>
<head>
    <title>Tiểu's Website ✨</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
</head>
<body>
    <h1>Home</h1>
    <br/>
    <h2 align="center">Posts</h2>

		<?php
			try {
				$mysqli = getDbConnection();
				$query = $mysqli->query("SELECT post.*, user.username FROM post INNER JOIN user ON post.user_id = user.id");

				if (!$query) {
					throw new Exception("Query failed: " . $mysqli->error);
				}
				echo generateTableHTML($query);
			} catch (Exception $e) {
				error_log($e->getMessage());
				echo "An error occurred. Please try again later.";
			} finally {
				$mysqli->close();
			}
		?>
	</body>
</html>