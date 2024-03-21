<!DOCTYPE html>
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
    </nav>
  <?php
    $searchQuery = htmlspecialchars($_GET['query']); // Get the search query
    // Assuming $numResults is the number of search results
    echo "<h2>Search Results for \"{$searchQuery}\"</h2>";
  ?>
  <form action="index.php" method="get">
    <button type="submit" class="back-button">&#8592; Back</button> </br>
  </form>
  </body>
</html>

<?php
    $mysqli = new mysqli("localhost", "root", "", "tieu_db");

    if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
    }

    if (!isset($_GET['query']) || trim($_GET['query']) == '') {
      echo "Search string cannot be empty";
      exit();
    }
    
    $query = $_GET['query'];

    $stmt = $mysqli->prepare("SELECT * FROM post WHERE details LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $query);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
      echo "<table>";
      echo "<tr>
              <th>Id</th>
              <th>Title</th>
              <th>Details</th>
              <th>Post Time</th>
              <th>Edit Time</th>
            </tr>";

      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo '<td align="center">' . $row['id'] . "</td>";
        echo '<td align="center">' . $row['title'] . "</td>";
        echo '<td align="center">' . nl2br($row['details']) . "</td>";
        echo '<td align="center">' . $row['date_posted'] . " - " . $row['time_posted'] . "</td>";
        echo '<td align="center">' . $row['date_edited'] . " - " . $row['time_edited'] . "</td>";
        echo "</tr>";
      }

      echo "</table>";
    } else {
      echo "No results found";
      echo "Error: " . $mysqli->error;
    }

    $mysqli->close();
?>

