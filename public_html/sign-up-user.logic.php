<?php
    session_start();
    if (empty($_POST)) {
        header('Location: index.php');
    } else {
        require_once('../lib/User.class.php');
        require_once('../lib/Mail.class.php');
        if (empty($_POST['username'])
            || empty($_POST['username'])
            || empty($_POST['password'])
            || empty($_POST['firstname'])
            || empty($_POST['lastname'])
            || empty($_POST['email'])
            || empty($_POST['phone'])) {
            $success = false;
            $message = "Information required is not complete!";
        } else {
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone'];
            $fields = array(
                'username' => $username,
                'password' => $password,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'phone_number' => $phone_number,
            );
            try {
                $user = new User();
                $user->create($fields);
                $success = true;
                $message = 'Welcome to sign up with FineTable :)';
                // Send welcome email
                $mail = Mail::getInstance();
                $mailto = $_POST['email'];
                $subject = 'Welcome to FineTable!';
                $body = '
                <div class="mail-body" style="padding: 20px; border: 5px solid rgb(254, 127, 65); border-radius: 4px; margin: 0 auto; width: 90%;">
                    <p>Hello '. $firstname . ',</p>
                    <p>Thank you for joining FineTable. We will do our best to help you find the best restaurant for you!.</p>
                    <p>Click <a href="http://sfsuswe.com/~f15g06" target="_blank">FineTable</a> to start look for restaurant!</p>
                    <p>Thank you!</p>
                    <p>FineTable team</p>
                </div>
                ';
                $mail->add_mailto($mailto);
                $mail->add_subject($subject);
                $mail->add_body($body);
                $mail->send();
            } catch (Exception $e) {
                $success = false;
                $message = $e->getMessage();
            }
        }
        $result = array(
            'success' => $success,
            'message' => $message
        );
        echo json_encode($result);
    }
?>
