<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "tieu_db");

$username = $mysqli->real_escape_string($_POST['username']);
$password = $mysqli->real_escape_string($_POST['password']);

$query = $mysqli->query("SELECT * FROM users WHERE username='$username'");
$exists = $query->num_rows;
$table_users = "";
$table_password = "";
if($exists > 0) {
    while($row = $query->fetch_assoc()) {
        $table_users = $row['username'];
        $table_password = $row['password'];
        $usertype = $row['usertype'];
    }
    if(($username == $table_users) && password_verify($password, $table_password)) {
        $_SESSION['user'] = $username;
        $_SESSION['usertype'] = $usertype;
        header("location: home.php");
    } else {
        echo '<script>alert("Incorrect Password!");</script>';
        echo '<script>window.location.assign("login.php");</script>';
    }
} else {
    echo '<script>alert("Username does not exist!");</script>';
    echo '<script>window.location.assign("login.php");</script>';
}
?>