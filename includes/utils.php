<?php
function getDbConnection() {
    $mysqli = new mysqli("localhost", "root", "", "tieu_db");
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

function addUser($mysqli, $username, $password) {
    if (empty($username) || empty($password)) {
        throw new Exception("Please fill in all the fields.");
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param('ss', $username, $hashedPassword);

        if (!$stmt->execute()) {
            throw new Exception('Error adding user: ' . $stmt->error);
        } 
        $_SESSION['message'] = "New user added successfully!";
        header("Location: admin.php");
        exit;
    }
}

function editUser($mysqli, $user_id, $username, $newPassword, $confirmPassword) {
    if ($user_id <= 0) {
        throw new Exception('Invalid user ID.');
    }

    if (empty($username) || empty($newPassword) || empty($confirmPassword)) {
        throw new Exception("Please fill in all the fields.");
    } elseif ($newPassword !== $confirmPassword) {
        throw new Exception("The new passwords do not match.");
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET username = ?, password = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ssi', $username, $hashedPassword, $user_id);

        if (!$stmt->execute()) {
            throw new Exception('Error updating user: ' . $stmt->error);
        }

        $_SESSION['message'] = 'User edited successfully!';
        header("Location: admin.php");
        exit;
    }
}

function deleteUser($mysqli, $user_id) {
    if ($user_id <= 0) {
        throw new Exception("Invalid user ID.");
    }

    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $mysqli->error);
    }

    $stmt->bind_param('i', $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }

    echo "<script type='text/javascript'>
            alert('User deleted successfully!');
            window.location.href = 'admin.php';
          </script>";
}

function loginUser($mysqli, $username, $password) {
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param('s', $username);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result(); 
    $user = $result->fetch_assoc(); 

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $username;
        $_SESSION['usertype'] = $user['usertype'];
        $_SESSION['user_id'] = $user['id'];
        header("location: home.php");
        exit;
    } else {
        $_SESSION['error_message'] = 'Incorrect username or password.';
        header("Location: login.php");
        exit;
    }
    $stmt->close();
	$mysqli->close();
}

function registerUser($username, $password) {
    $mysqli = getDbConnection();
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $_SESSION['error_message'] = 'Username has been taken!';
        throw new Exception("Username has been taken!");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $usertype = "user";
    $stmt = $mysqli->prepare("INSERT INTO user (username, password, usertype) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $usertype);
    $stmt->execute();
}

function fetchAllUsers($mysqli) {
    $result = $mysqli->query('SELECT * FROM user');

    if (!$result) {
        throw new Exception('Query failed: ' . $mysqli->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function fetchUserById($mysqli, $user_id) {
    if ($user_id <= 0) {
        throw new Exception("Invalid user ID.");
    }

    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $mysqli->error);
    }

    $stmt->bind_param('i', $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function searchPosts($mysqli, $query) {
    if (empty($query)) {
        header("Location: home.php");
        exit();
    }

    $stmt = $mysqli->prepare("SELECT post.*, post.id AS post_id, user.username 
                            FROM post INNER JOIN user ON post.user_id = user.id 
                            WHERE title LIKE CONCAT('%', ?, '%') OR details LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result) {
        throw new Exception("Error: " . $mysqli->error);
    }

    return $result;
}

function createPost($mysqli, $title, $details, $user_id) {
    $time = strftime("%X");
    $date = strftime("%B %d, %Y");

    $sql = "INSERT INTO post (title, details, date_posted, time_posted, user_id)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }
    
    $stmt->bind_param('ssssi', $title, $details, $date, $time, $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
}

function deletePost($mysqli, $post_id, $user_id) {
    if ($post_id <= 0) {
        throw new Exception("Invalid post ID.");
    }
    $post = getPostData($post_id);
    if ($post['user_id'] !== $user_id) {
        throw new Exception("You do not have permission to delete this post.");
        $_SESSION['error_message'] = "You do not have permission to delete this post.";
    }
    $sql = "DELETE FROM post WHERE id = ? AND user_id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $mysqli->error);
    }

    $stmt->bind_param('ii', $post_id, $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }

    $_SESSION['message'] = 'Post deleted successfully!';
    header("Location: home.php");
    exit;
}
function fetchPosts($mysqli) {
    $sql = "SELECT post.*, post.id AS post_id, post.user_id AS user_id, user.username 
            FROM post INNER JOIN user ON post.user_id = user.id";
    $query = $mysqli->query($sql);

    if (!$query) {
        throw new Exception("Query failed: " . $mysqli->error);
    }
    return $query;
}
function getPostData($post_id) {
    $mysqli = getDbConnection();
    $stmt = $mysqli->prepare("SELECT post.*, post.id AS post_id, post.user_id AS user_id, user.username
                             FROM post INNER JOIN user ON post.user_id = user.id 
                             WHERE post.id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function editPost($post_id, $title, $details, $user_id) {
    $mysqli = getDbConnection();
    $post = getPostData($post_id);
    if ($post['user_id'] !== $user_id) {
        $_SESSION['error_message'] = "You do not have permission to edit this post.";
    }
    else {
        $time = strftime("%X"); 
        $date = strftime("%B %d, %Y");
        $stmt = $mysqli->prepare("UPDATE post SET title=?, details=?, date_edited=?, time_edited=? 
                                WHERE id=? AND user_id=?");
        $stmt->bind_param("ssssii", $title, $details, $date, $time, $post_id, $user_id);
        $stmt->execute();
        if (!$stmt->execute()) {
            throw new Exception("Error updating post: " . $stmt->error);
        }
        $_SESSION['message'] = 'Post edited successfully!';
    }
}

function generateTableHTML($queryResult) {
    $tableHTML = "<table border=\"1px\" width=\"100%\"><tr><th>ID</th><th>Title</th><th>Details</th><th>Post Time</th><th>Edit Time</th><th>Author</th><th>Edit</th><th>Delete</th></tr>";
    
    // If $queryResult is an associative array, convert it to an array of associative arrays
    if (is_array($queryResult) && isset($queryResult['post_id'])) {
        $queryResult = array($queryResult);
    }

    // If $queryResult is a mysqli_result object, fetch all rows into an array
    if ($queryResult instanceof mysqli_result) {
        $queryResult = $queryResult->fetch_all(MYSQLI_ASSOC);
    }

    // Generate table rows
    foreach ($queryResult as $row) {
        $tableHTML .= generateTableRow($row);
    }

    $tableHTML .= "</table>";
    return $tableHTML;
}

function generateTableRow($row) {
    $tableRow = "<tr>";
    $tableRow .= '<td align="center">'. htmlspecialchars($row['post_id']) . "</td>";
    $tableRow .= '<td align="center">'. htmlspecialchars($row['title']) . "</td>"; 
    $tableRow .= '<td align="center">'. nl2br(htmlspecialchars($row['details'])) . "</td>";
    $tableRow .= '<td align="center">'. htmlspecialchars($row['date_posted']). " - ". htmlspecialchars($row['time_posted'])."</td>";
    $tableRow .= '<td align="center">'. htmlspecialchars($row['date_edited']). " - ". htmlspecialchars($row['time_edited']). "</td>";
    $tableRow .= '<td align="center">'. htmlspecialchars($row['username']) . "</td>"; 
    if (isset($_SESSION['user_id']) && $row['user_id'] == $_SESSION['user_id']) {
        $tableRow .= '<td align="center"><button onclick="location.href=\'edit.php?id='. htmlspecialchars($row['post_id']) .'\'" class="edit-button">Edit</button></td>';
        $tableRow .= '<td align="center"><button onclick="confirmDelete('.htmlspecialchars($row['post_id']).')" class="delete-button">Delete</button></td>';
    }
    
    $tableRow .= "</tr>";
    return $tableRow;
}

// function generateTableHTML($queryResult) {
//     $tableHTML = "<table border=\"1px\" width=\"100%\"><tr><th>ID</th><th>Title</th><th>Details</th><th>Post Time</th><th>Edit Time</th><th>Author</th><th>Edit</th><th>Delete</th></tr>";
//     if (is_array($queryResult)) {
//         $tableHTML .= "<tr>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($queryResult['post_id']) . "</td>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($queryResult['title']) . "</td>"; 
//         $tableHTML .= '<td align="center">'. nl2br(htmlspecialchars($queryResult['details'])) . "</td>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($queryResult['date_posted']). " - ". htmlspecialchars($queryResult['time_posted'])."</td>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($queryResult['date_edited']). " - ". htmlspecialchars($queryResult['time_edited']). "</td>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($queryResult['username']) . "</td>"; 
//         if ($queryResult['user_id'] == $_SESSION['user_id']) {
//             $tableHTML .= '<td align="center"><button onclick="location.href=\'edit.php?id='. htmlspecialchars($queryResult['post_id']) .'\'" class="edit-button">Edit</button></td>';
//             $tableHTML .= '<td align="center"><button onclick="confirmDelete('.htmlspecialchars($queryResult['post_id']).')" class="delete-button">Delete</button></td>';
//         }
        
//         $tableHTML .= "</tr>";
//     }
//     while($row = $queryResult->fetch_assoc()) {
//         $tableHTML .= "<tr>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($row['post_id']) . "</td>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($row['title']) . "</td>"; 
//         $tableHTML .= '<td align="center">'. nl2br(htmlspecialchars($row['details'])) . "</td>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($row['date_posted']). " - ". htmlspecialchars($row['time_posted'])."</td>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($row['date_edited']). " - ". htmlspecialchars($row['time_edited']). "</td>";
//         $tableHTML .= '<td align="center">'. htmlspecialchars($row['username']) . "</td>"; 
//         if ($row['user_id'] == $_SESSION['user_id']) {
//             $tableHTML .= '<td align="center"><button onclick="location.href=\'edit.php?id='. htmlspecialchars($row['post_id']) .'\'" class="edit-button">Edit</button></td>';
//             $tableHTML .= '<td align="center"><button onclick="confirmDelete('.htmlspecialchars($row['post_id']).')" class="delete-button">Delete</button></td>';
//         }
        
//         $tableHTML .= "</tr>";
//     }

//     $tableHTML .= "</table>";
//     return $tableHTML;
// }

function generateUserTableHTML($users) {
    $tableHTML = "<table border=\"1px\" width=\"100%\"><tr><th>ID</th><th>Password</th><th>Actions</th></tr>";

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

function handleException($e) {
    error_log($e->getMessage());
    echo "An error occurred. Please try again later.";
}

function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    $valid = hash_equals($_SESSION['csrf_token'], $token);
    if (!$valid) {
        http_response_code(403);
        die("Invalid CSRF token.");
    }
}

function fetchAssocFromResult($result) {
    if ($result instanceof mysqli_result) {
        return $result->fetch_assoc();
    } else {
        throw new Exception("Expected a mysqli_result object.");
    }
}

?>