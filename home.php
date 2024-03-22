<?php
	session_start(); 
	if($_SESSION['user']){ 
	}
	else{
		header("location:index.php");
	}
	$user = $_SESSION['user']; 
	$usertype = $_SESSION['usertype'];

	if (isset($_SESSION['message'])) {
		echo "<script type='text/javascript'>alert('{$_SESSION['message']}');</script>";
		unset($_SESSION['message']);
	}
?>

<html>
	<head>
		<title>Tiểu's Website ✨</title>
        <link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
	</head>
	
	<body>
		<nav>
			<form action="search.php" method="GET" class="search-form">
                <div class="box">
                    <input type="text" placeholder="Search" id="search" name="query">
                    <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                </div>
            </form>
			<?php
				if ($usertype == "admin") {
					echo '<form action="admin.php" method="get">
							<button type="submit" class="admin-button">Admin</button>
						</form>';
					}
			?>
			<form action="logout.php" method="get">
					<button type="submit" class="logout-button">Log out</button>
			</form>
		</nav>
		<h1>Home</h1>
		<h2>Hello <?php Print "$user"?>👋</h2> <!--Displays user's name-->
		
		<form action="add.php" method="POST">
			<div class="form-group">
				<!-- <label for="title">Title: </label></br> -->
				<input style="margin-top: 10px" 
					type="text" placeholder="Title" id="title" name="title" required/>
				<!-- <label for="details">Details: </label></br> -->
				<textarea style="background-color: #eee; color: #666666; padding: 1em; border-radius: 30px; border: 2px solid transparent; outline: none; height: 275px; width: 340px; font-family: inherit; font-size: 16px; margin-top: 10px;"
					class="box" placeholder="What's in your mind?" type="text" id="details" name="details" required></textarea>
			</div>
			<input type="submit" class="submit-button" value="Add to list"/>
		</form>
		<h2 align="center">Posts</h2>
		<table border="1px" width="100%">
			<tr>
				<th>Id</th>
				<th>Title</th>
				<th>Details</th>
				<th>Post Time</th>
				<th>Edit Time</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
			<?php
				$mysqli = new mysqli("localhost", "root", "", "tieu_db");
				$query = $mysqli->query("SELECT * FROM post");
				while($row = $query->fetch_assoc())
				{
					echo "<tr>";
						echo '<td align="center">'. $row['id'] . "</td>";
						echo '<td align="center">'. $row['title'] . "</td>"; 
						echo '<td align="center">'. nl2br($row['details']) . "</td>";
						echo '<td align="center">'. $row['date_posted']. " - ". $row['time_posted']."</td>";
						echo '<td align="center">'. $row['date_edited']. " - ". $row['time_edited']. "</td>";
						echo '<td align="center"><a href="edit.php?id='. $row['id'] .'">edit</a> </td>';
						echo '<td align="center"><a href="#" onclick="myFunction('.$row['id'].')">delete</a> </td>';
					echo "</tr>";
				}
			?>
		</table>
		<script>
			function myFunction(id)
			{
			var r=confirm("Are you sure you want to delete this record?");
			if (r==true)
			  {
			  	window.location.assign("delete.php?id=" + id);
			  }
			}
		</script>
	</body>
</html>