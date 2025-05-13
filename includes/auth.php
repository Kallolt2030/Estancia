<?php
session_start();
if (!isset($_SESSION['nip'])) {
    header('Location: /public/login.php');
    exit;
}
?>
