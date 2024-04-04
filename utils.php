<?php
function getDbConnection() {
    $mysqli = new mysqli("localhost", "root", "", "tieu_db");
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

function registerUser($username, $password) {
    $mysqli = getDbConnection();
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        throw new Exception("Username has been taken!");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $usertype = "user";
    $stmt = $mysqli->prepare("INSERT INTO user (username, password, usertype) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $usertype);
    $stmt->execute();
    $mysqli->close();
}


function getPostData($id) {
    $mysqli = getDbConnection();
    $stmt = $mysqli->prepare("SELECT * FROM post WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $mysqli->close();
    return $result->fetch_assoc();
}

function updatePostData($id, $title, $details) {
    $mysqli = getDbConnection();
    $time = strftime("%X"); 
    $date = strftime("%B %d, %Y");
    $stmt = $mysqli->prepare("UPDATE post SET title=?, details=?, date_edited=?, time_edited=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $details, $date, $time, $id);
    $stmt->execute();
    $mysqli->close();
}

function generateTableHTML($queryResult) {
    $tableHTML = "<table border=\"1px\" width=\"100%\"><tr><th>ID</th><th>Title</th><th>Details</th><th>Post Time</th><th>Edit Time</th><th>Author</th><th>Edit</th><th>Delete</th></tr>";

    while($row = $queryResult->fetch_assoc()) {
        $tableHTML .= "<tr>";
        $tableHTML .= '<td align="center">'. htmlspecialchars($row['id']) . "</td>";
        $tableHTML .= '<td align="center">'. htmlspecialchars($row['title']) . "</td>"; 
        $tableHTML .= '<td align="center">'. nl2br(htmlspecialchars($row['details'])) . "</td>";
        $tableHTML .= '<td align="center">'. htmlspecialchars($row['date_posted']). " - ". htmlspecialchars($row['time_posted'])."</td>";
        $tableHTML .= '<td align="center">'. htmlspecialchars($row['date_edited']). " - ". htmlspecialchars($row['time_edited']). "</td>";
        $tableHTML .= '<td align="center">'. htmlspecialchars($row['username']) . "</td>"; 
        $tableHTML .= '<td align="center"><button onclick="location.href=\'edit.php?id='. htmlspecialchars($row['id']) .'\'" class="edit-button">Edit</button></td>';
        $tableHTML .= '<td align="center"><button onclick="myFunction('.htmlspecialchars($row['id']).')" class="delete-button">Delete</button></td>';
        $tableHTML .= "</tr>";
    }

    $tableHTML .= "</table>";
    return $tableHTML;
}

function generateUserTableHTML($users) {
    $tableHTML = "<table border=\"1px\" width=\"100%\"><tr><th>ID</th><th>Password</th><th>Edit</th><th>Delete</th></tr>";

    foreach ($users as $user) {
        $tableHTML .= '<tr>
            <td>' . htmlspecialchars($user['username']) . '</td>
            <td>***************************</td>
            <td>
                <button onclick="location.href=\'edit_user.php?id=' . $user['id'] . '\'" type="button">Edit</button>
                <form action="delete_user.php" method="POST" style="display: inline;">
                    <input type="hidden" name="user_id" value="' . $user['id'] . '">
                    <button type="submit" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</button>
                </form>
            </td>
        </tr>';
    }
    $tableHTML .= "</table>";
    return $tableHTML;
}

function displayAndClearMessages() {
    if (isset($_SESSION['error_message'])) {
        $error_message = json_encode($_SESSION['error_message']);
        echo "<script type='text/javascript'>alert($error_message);</script>";
        unset($_SESSION['error_message']);
    }
    if (isset($_SESSION['message'])) {
        $message = json_encode($_SESSION['message']);
        echo "<script type='text/javascript'>alert($message);</script>";
        unset($_SESSION['message']);
    }
}

function checkLoggedInStatus() {
    if (!isset($_SESSION['user'])) {
        header("location:index.php");
        exit;
    }
}

function checkAdminStatus() {
    if ($_SESSION['usertype'] !== 'admin') {
        header('Location: home.php');
        exit;
    }
}
?>