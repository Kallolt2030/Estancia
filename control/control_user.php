<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión si aún no está iniciada
}

// Redirige si no ha iniciado sesión
if (!isset($_SESSION['nip'])) {
    header("Location: ../public/login.php");
    exit(); // Detener la ejecución del script después de la redirección
}

// Bloquea si no es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    // Redirige a la página de login si no es administrador
    header("Location: ../public/login.php");
    exit();
}
?>
