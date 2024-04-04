<?php
    session_start();
	include 'includes/loader.php';
?>

<html>
<head>
    <title>Index</title>
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
				$query = $query = fetchPosts($mysqli);
				echo generateTableHTML($query);
			} catch (Exception $e) {
				handleException($e);
			}
		?>
	</body>
</html>