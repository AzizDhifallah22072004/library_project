<?php
    session_start();
    session_destroy();
    
    //facultatif pour être sur effacer les variables
    unset($_SESSION['username']);
    unset($_SESSION['email']);
    header('Location: login.php');
?>