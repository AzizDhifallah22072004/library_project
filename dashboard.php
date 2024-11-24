<?php 
    include 'header.phtml';
?>

<?php 
    session_start();
    
    if (!isset($_SESSION['full_name'])) {//si y a une session
        header('Location: login.php');
        exit();
    }
    $pageTitle = "Dashboard";
    $template = "dashboard";
    include 'layout.phtml';
?>

    
