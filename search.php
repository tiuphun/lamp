<?php
    session_start();
    include 'includes/loader.php';
    try {
        $mysqli = getDbConnection();
        $query = $_GET['query'];

        $result = searchPosts($mysqli, $query);
        $searchQuery = htmlspecialchars($query); 
        echo "<h2>Search Results for \"{$searchQuery}\"</h2>";

        if ($result->num_rows > 0) {
            echo generateTableHTML($result);
        } else {
            echo "No results found";
        }
    } catch (Exception $e) {
        handleException($e);
    } finally {
        $mysqli->close();
    }
?>
<html>
    <head>
        <title>Search</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    </head>
    <body>
    </body>
</html>