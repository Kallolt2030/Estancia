<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirige si no ha iniciado sesión
if (!isset($_SESSION['nip'])) {
    header("Location: ../public/login.php");
    exit();
}

// Bloquea si no es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado. Esta área es solo para administradores.";
    exit();
}
?>
