<?php
  require_once('../../lib/Restaurant.class.php');

  $rest_to_approve = $_GET['id'];
  $rest = new Restaurant();
  $rest->approve_or_deny($rest_to_approve, true);

  header("Location: ../admin.php");
?>
