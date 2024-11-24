<?php
  include 'dbconnect.php';
  session_start();
  $query = $pdo->prepare('DELETE FROM adhérents WHERE id =?');
  $query->execute([$_GET['id']]);

  header('Location: manage_members.php?danger=1');
  exit();
?>