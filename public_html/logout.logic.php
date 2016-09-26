<?php
    session_start();
    if(session_destroy()) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    setcookie('login_user', '', time() - 3600);
    setcookie('login_user_id', '', time() - 3600);
    setcookie('login_user_firstname', '', time() - 3600);
?>
