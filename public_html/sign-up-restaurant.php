<?php
    session_start();
    if (empty($_SESSION['login_user']) || empty($_SESSION['login_user_id']) || empty($_SESSION['login_user_firstname'])) {
        header('Location: index.php');
    }
    require_once('../templates/header.php');

    require_once('../templates/sign-up-restaurant.content.php');
    require_once('../templates/footer.php');
?>
