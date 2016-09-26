<?php
  require_once('../../lib/User.class.php');

  $user_to_delete = $_GET['id'];
  $user = new User();
  $user->delete($user_to_delete);

  header("Location: ../admin.php");
?>
