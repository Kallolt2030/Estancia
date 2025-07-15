<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

if (!isset($_SESSION['nip'])) {
    header("Location: ../public/login.php");
    exit(); 
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}
?>
