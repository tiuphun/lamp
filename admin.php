<?php
    session_start();
    include 'includes/loader.php';
    checkAdminStatus();
    displayAndClearMessages();
    try {
        $mysqli = getDbConnection();
        $users = fetchAllUsers($mysqli);
    } catch (Exception $e) {
        handleException($e);
    }
?>

<html>
    <head>
        <title>Admin Page</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <h1>Users</h1>
            <?php echo generateUserTableHTML($users); ?>
        <h2>Add User</h2>
            <?php echo getAddUserForm(); ?>
    </body>
</html>