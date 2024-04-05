<?php
    session_start();
    include 'includes/loader.php';
?>
<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    </head>
    <body>
        <?php echo getRegisterForm(); ?>
    </body>
</html>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        verifyCsrfToken($_POST['csrf_token']);
        try {
            registerUser($_POST['username'], $_POST['password']);
            $_SESSION['message'] = 'Successfully Registered!';
            header("Location: register.php");
            exit;
        } catch (Exception $e) {
            handleException($e);
            header("Location: register.php");
            exit;
        }
    }
    displayAndClearMessages();
?>