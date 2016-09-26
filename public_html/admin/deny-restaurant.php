<?php
  require_once('../../lib/Restaurant.class.php');

  $rest_to_deny = $_GET['id'];
  $rest = new Restaurant();
  $rest->approve_or_deny($rest_to_deny, false);

  header("Location: ../admin.php");
?>
