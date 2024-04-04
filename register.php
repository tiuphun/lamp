<?php
    include 'includes/loader.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        verifyCsrfToken($_POST['csrf_token']);
        try {
            registerUser($_POST['username'], $_POST['password']);
            $_SESSION['message'] = 'Successfully Registered!';
            header("Location: register.php");
            exit;
        } catch (Exception $e) {
            handleException($e);
            $_SESSION['error_message'] = "An error occurred. Please try again later.";
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
        <?php echo getRegisterForm(); ?>
    </body>
</html>