<?php 
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'CRUD_project');
  define('DB_PASS', '');
  define('DB_USER', 'root');
  
  try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS);
    $pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO:: FETCH_ASSOC);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);
  } catch (Exception $e) {
      echo 'erreur de connextion à la base de données' .$e->getMessage();
      die();
  }
?>

