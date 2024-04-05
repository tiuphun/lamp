<?php
    include 'includes/loader.php';
    session_start(); 
    checkLoggedInStatus();
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['user'];
    $id_exists = false;

    if(!empty($_GET['id'])) {
        $post_id = $_GET['id'];
        $_SESSION['post_id'] = $post_id;
        $post = getPostData($post_id);
        $id_exists = !empty($post);
    }
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        verifyCsrfToken($_POST['csrf_token']);
        editPost($_SESSION['post_id'], $_POST['title'], $_POST['details'], $user_id);
        header("location: home.php");
    }
?>

<html>
    <head>
        <title>Edit Post</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    </head>
    
    <body>
        <h1 align="center">Currently Selected</h1>
        <h4>You are editing as <?php echo $username; ?></h4>
        <?php if ($id_exists): ?>
            <?php echo generateTableHTML($post); ?>
            <?php echo getEditPostForm(); ?>
        <?php else: ?>
            <h2 align="center">There is no data to be edited.</h2>
        <?php endif; ?>
        <script>
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this post?')) {
                    window.location.href = 'delete.php?id=' + id;
                }
            }
        </script>
    </body>
</html>