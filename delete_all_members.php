<?php
  include 'dbconnect.php';
  session_start();
  $sql = "DELETE FROM adhérents";
  $query = $pdo->prepare($sql);

  if ($query->execute()) {
      header("Location: manage_members.php?success_all=1");
  } else {
      header("Location: manage_members.php?error_all=1");
  }
?>
