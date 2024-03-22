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
    <form action="login.php" method="get">
      <button type="submit" class="login-button">Login</button>
    </form>
    <form action="register.php" method="get">
      <button type="submit" class="register-button">Register</button>
    </form>
  </nav>
  <h1>Home</h1>

  <br/>
  <h2 align="center">Posts</h2>
  <table width="100%" border="1px">
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Details</th>
      <th>Post Time</th>
      <th>Edit Time</th>
    </tr>
    <?php
      session_start();
      $mysqli = new mysqli("localhost", "root", "", "tieu_db");

      if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
      }

      $query = $mysqli->query("SELECT * FROM post");

      if ($query) {
        while ($row = $query->fetch_assoc()) {
          echo "<tr>";
          echo '<td align="center">' . $row['id'] . "</td>";
          echo '<td align="center">' . $row['title'] . "</td>";  // Added title column
          echo '<td align="center">' . nl2br($row['details']) . "</td>";
          echo '<td align="center">' . $row['date_posted'] . " - " . $row['time_posted'] . "</td>";
          echo '<td align="center">' . $row['date_edited'] . " - " . $row['time_edited'] . "</td>";
          echo "</tr>";
        }
      } else {
        echo "Error: " . $mysqli->error;
      }

      $mysqli->close();
    ?>
  </table>
</body>
</html>
