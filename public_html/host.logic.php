<?php
  require_once('../lib/Reservation.class.php');

  $to_cancel = $_GET['id'];
  $res = new Reservation();
  $res->cancel($to_cancel);

  header("Location: host.php");
?>