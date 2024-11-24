<?php
  include 'dbconnect.php';
  session_start();
  $query = $pdo->prepare('DELETE FROM livres WHERE id =?');
  $query->execute([$_GET['id']]);

  header('Location: manage_books.php?danger=1');
  exit();
?>