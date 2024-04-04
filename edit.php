<?php
    include 'includes/loader.php';
    session_start(); 
    checkLoggedInStatus();
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $user = $_SESSION['user'];
    $id_exists = false;

    if(!empty($_GET['id'])) {
        $id = $_GET['id'];
        $_SESSION['id'] = $id;
        $post = getPostData($id);
        $id_exists = !empty($post);
    }

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        verifyCsrfToken($_POST['csrf_token']);
        updatePostData($_SESSION['id'], $_POST['title'], $_POST['details']);
        $_SESSION['message'] = 'Post edited successfully!';
        header("location: home.php");
    }
?>

<html>
    <head>
        <title>Edit Post</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    
    <body>
        <h1 align="center">Currently Selected</h1>
        <h4>You are editing as <?php echo $user; ?></h4>
        <?php if ($id_exists): ?>
            <?php echo generateTableHTML($post); ?>
            <?php echo getEditPostForm(); ?>
        <?php else: ?>
            <h2 align="center">There is no data to be edited.</h2>
        <?php endif; ?>
    </body>
</html>