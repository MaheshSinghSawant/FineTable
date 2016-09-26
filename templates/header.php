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
    <link rel="stylesheet" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="class-project text-center">SFSU/FAU/Fulda Software Engineering Project, Fall 2015. For Demonstration Purposes Only</div>
    <div class="site-wrapper">
        <header class="container-fluid">
            <div class="container">
                <div class="col-md-2 col-sm-12 logo">
                    <a href="index.php">FineTable</a>
                </div>
                <div class="col-md-10 col-sm-12">
                    <nav class="pull-right">
                        <!-- <a class="home-link" href="index.php">Home</a> -->
                        <a class="sign-up-restaurant" href="sign-up-restaurant.php">Register your restaurant</a>
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

        <section class="container-fluid carousel">
            <section class="container search-bar-container">
                <div class="container search-bar">
                    <div class="search-bar-background"></div>
                    <div class="col-md-3 col-sm-offset-1 search-instruction">
                        Search for a table!
                    </div>
                    <div class="col-md-7">
                        <form class="" action="search.php" method="get">
                            <div class="search-bar-input col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control body-search-input" placeholder="Search restaurant name / location / cuisine" name="search_key" value="<?php if (isset($search_key)) echo $search_key ?>">
                                    <!-- <input type="hidden" name="perpage" value="15"> -->
                                    <input type="hidden" name="pagenumber" value="1">
                                    <div class="input-group-btn">
                                        <input type="submit" class="btn btn-primary" value="Find a table!">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </section>
