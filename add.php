<?php
session_start();
if(!isset($_SESSION['user'])){
} else {
    header("location:index.php");
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $mysqli = new mysqli("localhost", "root", "", "tieu_db");
    $title = $mysqli->real_escape_string($_POST['title']);
    $details = $mysqli->real_escape_string($_POST['details']);
    $time = strftime("%X");
    $date = strftime("%B %d, %Y");
    // $decision = "no";

    // foreach($_POST['public'] as $each_check) {
    //     if($each_check != null) {
    //         $decision = "yes";
    //     }
    // }

    $mysqli->query("INSERT INTO post (title, details, date_posted, time_posted) VALUES ('$title', '$details','$date','$time')");
    header("location: home.php");
} else {
    header("location:home.php");
}
?>