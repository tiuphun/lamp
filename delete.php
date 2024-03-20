<?php
    session_start(); //starts the session
    if($_SESSION['user']){ //checks if user is logged in
    }
    else{
        header("location:index.php"); // redirects if user is not logged in
    }

    if($_SERVER['REQUEST_METHOD'] == "GET")
    {
        $mysqli = new mysqli("localhost", "root", "", "tieu_db");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        } 

        $id = $mysqli->real_escape_string($_GET['id']);
        
        // if ($mysqli->query("DELETE FROM post WHERE id='$id'")) {
        //     $_SESSION['message'] = 'Record deleted successfully';
        // } else {
        //     $_SESSION['message'] = 'Failed to delete record';
        // }

        $mysqli->query("DELETE FROM post WHERE id='$id'");
        header("location: home.php");
    }
?>