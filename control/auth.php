<?php
session_start();

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset(); 
    session_destroy(); 
    header('Location: ../login.php'); 
    exit;
}

if (!isset($_SESSION['nip'])) {
    header('Location: ../login.php');
    exit;
}
?>

