<html>
<head>
    <title>Search</title>
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
</nav>

<?php
    include 'utils.php';
    $mysqli = getDbConnection();
    
    $query = $_GET['query'];
    if (empty($query)) {
        header("Location: home.php");
        exit();
    }

    $stmt = $mysqli->prepare("SELECT * FROM post WHERE title LIKE CONCAT('%', ?, '%') OR details LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();

    $result = $stmt->get_result();

    $searchQuery = htmlspecialchars($query); 
    echo "<h2>Search Results for \"{$searchQuery}\"</h2>";

    if ($result && $result->num_rows > 0) {
        echo generateTableHTML($query);
    } else {
        error_log("Error: " . $mysqli->error);
        echo "No results found";
    }

    $mysqli->close();
?>
</body>
</html>