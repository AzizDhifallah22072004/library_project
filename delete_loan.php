<?php
  include 'dbconnect.php';
  session_start();
  $query = $pdo->prepare('DELETE FROM emprunts WHERE id =?');
  $query->execute([$_GET['id']]);

  header('Location: manage_loan.php?danger=1');
  exit();
?>