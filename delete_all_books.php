<?php
  include 'dbconnect.php';
  session_start();
  $sql = "DELETE FROM livres";
  $query = $pdo->prepare($sql);

  if ($query->execute()) {
      header("Location: manage_books.php?success_all=1");
  } else {
      header("Location: manage_books.php?error_all=1");
  }
?>
