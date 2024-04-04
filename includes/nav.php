<nav>
    <form action="search.php" method="GET" class="search-form">
        <div class="box">
            <input type="text" placeholder="Search" id="search" name="query">
            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <?php
        if (isset($_SESSION['user'])) {
            // User is logged in
            echo '<form action="logout.php" method="get">
                    <button type="submit" class="logout-button">Log out</button>
                  </form>';
            if ($_SESSION['usertype'] == "admin") {
                echo '<form action="admin.php" method="get">
                        <button type="submit" class="admin-button">Admin</button>
                      </form>';
            }
        } else {
            // User is not logged in
            echo '<form action="login.php" method="get">
                    <button type="submit" class="login-button">Login</button>
                  </form>';
            echo '<form action="register.php" method="get">
                    <button type="submit" class="register-button">Register</button>
                  </form>';
        }
    ?>
</nav>