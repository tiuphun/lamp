<?php
    include 'utils.php';
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
        <?php if ($id_exists): echo generateTableHTML($query); ?>
            <br/>
            <h2>Edit Post</h2>
            <form action="edit.php" method="POST">
                <input type="text" name="title" placeholder="Title" required/><br/>
                <textarea style="background-color: #eee; color: #666666; padding: 1em; border-radius: 30px; border: 2px solid transparent; outline: none; height: 275px; width: 340px; font-family: inherit; font-size: 16px; margin-top: 10px;"
                        class="box" placeholder="New details..." type="text" id="details" name="details" required></textarea><br>
                <input type="submit" class="submit-button" value="Update Post"/>
            </form>
        <?php else: ?>
            <h2 align="center">There is no data to be edited.</h2>
        <?php endif; ?>
    </body>
</html>