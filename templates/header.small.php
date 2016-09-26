<?php
if(session_id() == '') {
    session_start();
}
if (isset($_COOKIE['login_user']) && isset($_COOKIE['login_user_id']) && isset($_COOKIE['login_user_firstname'])) {
    $_SESSION['login_user'] = $_COOKIE['login_user'];
    $_SESSION['login_user_id'] = $_COOKIE['login_user_id'];
    $_SESSION['login_user_firstname'] = $_COOKIE['login_user_firstname'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="FineTable is a class project of SFSU. It is a port for people to make reservation to restaurants.">
    <meta name="keyword" content="FineTable, open table, reservation, restaurant">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>FineTable - Make a reservation to your favorite restaurant</title>
    <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-select.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="class-project text-center">SFSU/FAU/Fulda Software Engineering Project, Fall 2015. For Demonstration Purposes Only</div>
    <div class="site-wrapper">
        <header class="container-fluid darker">
            <div class="container">
                <div class="col-md-2 col-sm-12 logo">
                    <a href="/">FineTable</a>
                </div>
                <div class="col-md-4 header-search">
                    <form class="header-search-form" action="index.html" method="post">
                        <div class="search-bar-input col-md-12">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control header-search-input" placeholder="Search restaurant name / location / cuisine" name="search-key">
                                <div class="input-group-btn">
                                    <input type="submit" class="btn btn-primary" value="Go!">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 col-sm-12">
                    <nav class="pull-right">
                        <a href="sign-up-restaurant.php">Register your restaurant</a>
                        <span class="nav-separator"></span>
                        <?php
                            if (isset($_SESSION['login_user'])) {
                                echo '<a class="username" href="#">Welcome, ' . $_SESSION['login_user_firstname'] . '</a>';
                                echo '<a href="logout.logic.php">Logout?</a>';
                            } else {
                                echo '<a class="login-link" href="login.php">Login</a>';
                                echo '<a class="signup-link" href="sign-up-user.php">Sign up</a>';
                            }
                        ?>
                    </nav>
                </div>
            </div>
        </header>
