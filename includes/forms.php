<?php
function getEditPostForm() {
    return '
        <h2>Edit Post</h2>
        <form action="edit.php" method="POST">
            <input type="text" name="title" placeholder="Title" required/><br/>
            <textarea style="background-color: #eee; color: #666666; padding: 1em; border-radius: 30px; border: 2px solid transparent; outline: none; height: 275px; width: 340px; font-family: inherit; font-size: 16px; margin-top: 10px;"
                    class="box" placeholder="New details..." type="text" id="details" name="details" required></textarea><br>
            <input type="submit" class="submit-button" value="Update Post"/>
        </form>
    ';
}

function getAddPostForm() {
    return <<<HTML
        <form action="add.php" method="POST">
            <div class="form-group">
                <input style="margin-top: 10px" 
                    type="text" placeholder="Title" id="title" name="title" required/>
                <textarea style="background-color: #eee; color: #666666; padding: 1em; border-radius: 30px; border: 2px solid transparent; outline: none; height: 275px; width: 340px; font-family: inherit; font-size: 16px; margin-top: 10px;"
                    class="box" placeholder="Details" type="text" id="details" name="details" required></textarea>
            </div>
            <input type="submit" class="submit-button" value="Add to list"/>
        </form>
    HTML;
}

function getEditUserForm($user) {
    $username = htmlspecialchars($user['username']);
    return <<<HTML
        <form method="POST" action="" class="auth-form">
            <input type="text" name="username" id="username" placeholder="New username" value="$username" required><br>
            <input type="password" name="new_password" id="new_password" placeholder="New password" required><br>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required><br>
            <input type="submit" class="submit-button" value="Edit User">
        </form>
    HTML;
}

function getAddUserForm() {
    $csrfToken = generateCsrfToken();
    return <<<HTML
        <form action="add_user.php" method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="$csrfToken">
            <input type="text" name="username" placeholder="Username" id="username" required><br>
            <input type="password" name="password" placeholder="Password" id="password" required><br>
            <input type="submit" class="submit-button" value="Add User">
        </form>
    HTML;
}

function getLoginForm() {
    $csrfToken = generateCsrfToken();
    return <<<HTML
        <form action="checklogin.php" method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="$csrfToken">
            <input type="text" name="username" placeholder="Username" required="required"/> <br/>
            <input type="password" name="password" placeholder="Password" required="required" /> <br/>
            <div class="button-container">
                <input type="submit" class="submit-button" value="Login"/>
            </div>
        </form>
    HTML;
}
function getRegistrationForm() {
    $csrfToken = generateCsrfToken();
    return <<<HTML
        <h2>Register</h2>
        <form action="register.php" method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="$csrfToken">
            <input type="text" name="username" placeholder="Username" required="required"/> <br/>
            <input type="password" name="password" placeholder="Password" required="required" /> <br/>
            <div class="button-container">
                <input type="submit" class="submit-button" value="Register"/>
            </div>
        </form>
    HTML;
}


?>