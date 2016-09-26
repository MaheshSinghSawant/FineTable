<?php
require('PHPMailerAutoload.php');
class Mail {
    private static $_mail = null;
    private $_host = 'smtp.gmail.com',
            $_username = 'finetableprojectsfsu@gmail.com',
            $_password = 'MT06Team',
            $_SMTPSecure = 'tls',
            $_port = '587',
            $_name = 'FineTable';

    private function __construct() {
        $this->_mail = new PHPMailer();
        $this->_mail->isSMTP();
        $this->_mail->Host = $this->_host;
        $this->_mail->SMTPAuth = true;
        $this->_mail->Username = $this->_username;
        $this->_mail->Password = $this->_password;
        $this->_mail->SMTPSecure = $this->_SMTPSecure;
        $this->_mail->Port = $this->_port;
        $this->_mail->setFrom($this->_username, $this->_name);
        $this->_mail->isHTML(true);
    }

    public static function getInstance() {
        if (!isset(self::$_mail)) {
            self::$_mail = new Mail();
        }
        return self::$_mail;
    }

    public function add_mailto($mailto) {
        $this->_mail->addAddress($mailto);
    }

    public function add_cc($cc) {
        $this->_mail->addCC($cc);
    }

    public function add_bcc($bcc) {
        $this->_mail->addBCC($bcc);
    }

    public function add_subject($subject) {
        $this->_mail->Subject = $subject;
    }

    public function add_body($body) {
        $this->_mail->Body = $body;
    }

    public function send() {
        if ($this->_mail->send()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
