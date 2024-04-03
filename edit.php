<html>
    <head>
        <title>Tiểu's Website ✨</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <?php
    session_start(); //starts the session
    if($_SESSION['user']){ //checks if user is logged in
    }
    else{
        header("location:index.php"); // redirects if user is not logged in
    }
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $user = $_SESSION['user']; //assigns user value
    $id_exists = false;
    ?>
    <body>
        <form action="home.php" method="get">
			<button type="submit" class="back-button">&#8592; Back</button>
		</form>
        <h2 align="center">Currently Selected</h2>
        <h4>You are editing as <?php Print "$user"?></h4>
        <table border="1px" width="100%">
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Details</th>
                <th>Post Time</th>
                <th>Edit Time</th>
                <!-- <th>Public Post</th> -->
            </tr>
            <?php
                if(!empty($_GET['id']))
                {
                    $id = $_GET['id'];
                    $_SESSION['id'] = $id;
                    $mysqli = new mysqli("localhost", "root", "", "tieu_db");

                    if ($mysqli->connect_error) {
                        error_log("Failed to connect: " . $mysqli->connect_error);
                        die("Connection failed. Please try again later.");
                    }

                    $stmt = $mysqli->prepare("SELECT * FROM post WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $id_exists = true;
                            Print "<tr>";
                            Print '<td align="center">' . $row['id'] . "</td>";
                            Print '<td align="center">' . $row['title'] . "</td>";
                            Print '<td align="center">' . nl2br($row['details']) . "</td>";
                            Print '<td align="center">' . $row['date_posted'] . " - " . $row['time_posted'] . "</td>";
                            Print '<td align="center">' . $row['date_edited'] . " - " . $row['time_edited'] . "</td>";
                            // Print '<td align="center">'. $row['public']. "</td>";
                            Print "</tr>";
                        }
                    } else {
                        error_log("Error: " . $mysqli->error);
                        echo "An error occurred. Please try again later.";
                    }
                    
                }
            ?>
        </table>
        <br/>
<?php
        if($id_exists)
        {
        Print '
        <h2>Edit Post</h2>
        <form action="edit.php" method="POST">
            <input type="text" name="title" placeholder="Title" required/><br/>
            <textarea style="background-color: #eee; color: #666666; padding: 1em; border-radius: 30px; border: 2px solid transparent; outline: none; height: 275px; width: 340px; font-family: inherit; font-size: 16px; margin-top: 10px;"
					class="box" placeholder="New details..." type="text" id="details" name="details" required></textarea><br>
            <input type="submit" class="submit-button" value="Update Post"/>
        </form>
        ';
        }
        else
        {
            Print '<h2 align="center">There is no data to be edited.</h2>';
        }
        ?>
    </body>
</html>

<?php
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $mysqli = new mysqli("localhost", "root", "", "tieu_db");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        
        $title = $_POST['title'];
        $details = $_POST['details'];
        $id = $_SESSION['id'];
        $time = strftime("%X"); //time
        $date = strftime("%B %d, %Y"); //date

        $stmt = $mysqli->prepare("UPDATE post SET title=?, details=?, date_edited=?, time_edited=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $details, $date, $time, $id);
        $stmt->execute();

        header("location: home.php");
    }
?>