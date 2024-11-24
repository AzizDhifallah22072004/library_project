<?php 
  include 'header.phtml';
?>

<?php 
  session_start();
  
  if (!isset($_SESSION['full_name'])) {//si y a une session
      header('Location: login.php');
      exit();
  } 
  include 'dbconnect.php';

  $sql = "SELECT * FROM adhérents";
  $query = $pdo->prepare($sql);
  $query->execute();
  $mbrs = $query->fetchAll();
  $pageTitle = "Manage Members";
  $template = "manage_members";
  include 'layout.phtml';
?>