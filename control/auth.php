<?php
session_start();

// Si viene el parámetro de logout, cerramos sesión
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset(); // Limpia variables de sesión
    session_destroy(); // Elimina la sesión completamente
    header('Location: ../public/login.php'); // Redirige al login
    exit;
}

// Si el usuario no está autenticado, lo mandamos al login
if (!isset($_SESSION['nip'])) {
    header('Location: ../public/login.php');
    exit;
}
?>

