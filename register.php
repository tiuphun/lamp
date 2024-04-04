<?php
    include 'utils.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        try {
            registerUser($_POST['username'], $_POST['password']);
            $_SESSION['message'] = 'Successfully Registered!';
            header("Location: register.php");
            exit;
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: register.php");
            exit;
        }
    }
    displayAndClearMessages();
?>

<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <h2>Register</h2>
        <form action="register.php" method="POST" class="auth-form">
            <input type="text" name="username" placeholder="Username" required="required"/> <br/>
            <input type="password" name="password" placeholder="Password" required="required" /> <br/>
            <div class="button-container">
                <input type="submit" class="submit-button" value="Register"/>
            </div>
        </form>
    </body>
</html>