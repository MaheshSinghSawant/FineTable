<?php
    // print_r($_POST);
    session_start();
    if (empty($_POST['username']) || empty($_POST['password'])) {
        header('Location: index.php');
    } else {
        require_once('../lib/User.class.php');
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $user = new User();
        if ($user->find($username)) {
            if ($user->login()) {
                $_SESSION['login_user'] = $user->get_user_name();
                $_SESSION['login_user_id'] = $user->get_user_id();
                $_SESSION['login_user_firstname'] = $user->get_user_firstname();
                $result = array(
                    'success' => true,
                    'message' => 'Hello ' . $user->get_user_firstname() . ', welcome back to FineTable!',
                    'userinfo' => $user->get_user_info_json()
                );
                setcookie('login_user', $user->get_user_name(), time() + 86400);
                setcookie('login_user_id', $user->get_user_id(), time() + 86400);
                setcookie('login_user_firstname', $user->get_user_firstname(), time() + 86400);
                setcookie('login_user_email', $user->get_user_email(), time() + 86400);
                setcookie('login_user_phone', $user->get_user_phone(), time() + 86400);
            } else {
                $result = array(
                    "success" => false,
                    "message" => "Username or password is invalid!"
                );
            }
        } else {
            $result = array(
                "success" => false,
                "message" => "Username or password is invalid!"
            );
        }
        echo json_encode($result);
    }


?>
