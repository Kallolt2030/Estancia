<?php
require_once __DIR__ . '/control_user.php'; // Asegura la sesión y permisos
require_once __DIR__ . '/../includes/auth.php'; // Manejo seguro de sesión
require_once __DIR__ . '/../includes/header.php';
?>


<h2>Panel Administrativo</h2>
<nav>
    <a href="usuarios.php">Usuarios</a> |
    <a href="medicos.php">Médicos</a> |
    <a href="expedientes.php">Expedientes</a>
</nav>

<?php include '../includes/footer.php'; ?>
