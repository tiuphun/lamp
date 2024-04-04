<?php
	session_start(); 
	include 'nav.php';
	include 'utils.php';
	checkLoggedInStatus();
	$user = $_SESSION['user'];
	$usertype = $_SESSION['usertype'];

	displayAndClearMessages();
?>

<html>
	<head>
		<title>Home</title>
        <link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
	</head>
	
	<body>
		<h1>Home</h1>
		<h2>Hello <?php Print "$user"?>👋</h2>
		
		<form action="add.php" method="POST">
			<div class="form-group">
				<input style="margin-top: 10px" 
					type="text" placeholder="Title" id="title" name="title" required/>
				<textarea style="background-color: #eee; color: #666666; padding: 1em; border-radius: 30px; border: 2px solid transparent; outline: none; height: 275px; width: 340px; font-family: inherit; font-size: 16px; margin-top: 10px;"
					class="box" placeholder="Details" type="text" id="details" name="details" required></textarea>
			</div>
			<input type="submit" class="submit-button" value="Add to list"/>
		</form>
		<h2 align="center">Posts</h2>
			<?php
				include 'utils.php';
				try {
					$mysqli = new mysqli("localhost", "root", "", "tieu_db");
					if ($mysqli->connect_error) {
						throw new Exception("Connection failed: " . $mysqli->connect_error);
					}
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
		</table>
		<script>
			function myFunction(id)
			{
			var r=confirm("Are you sure you want to delete this record?");
				if (r==true) {
					window.location.assign("delete.php?id=" + id);
				}
			}
		</script>
	</body>
</html>