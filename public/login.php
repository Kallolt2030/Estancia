<?php
session_start();

// Redirigir si no ha iniciado sesión
if (!isset($_SESSION['nip'])) {
    header("Location: /login.php");
    exit();
}

// Verificar si tiene rol de admin
if ($_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado. Esta área es solo para administradores.";
    exit();
}
// Conexión a la base de datos
$host = 'localhost';
$user = 'root'; // Cambia si es diferente
$pass = '';     // Cambia si tienes contraseña
$dbname = 'estancia'; // <--- CAMBIA ESTO POR EL NOMBRE DE TU BD

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'];

    // Consulta el nip
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nip = ?");
    $stmt->bind_param("s", $nip);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION['nip'] = $usuario['nip'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        if ($usuario['rol'] === 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../dashboard/index.php');
        }
        exit;
    } else {
        $error = "NIP incorrecto o no registrado.";
    }

    $stmt->close();
}
$conn->close();

include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Proyecto UTA</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="login-page">
    <div class="login-animation-wrapper">
        <div class="door-container" id="doorContainer">
            <div class="door door-left"></div>
            <div class="door door-right"></div>
            
            <div class="login-form-container">
                <h1 class="login-title">Ingreso Proyecto UTA</h1>
                <?php if (isset($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="post" class="login-form">
                    <div class="input-group">
                        <label for="nip">NIP:</label>
                        <input type="password" id="nip" name="nip" required>
                    </div>
                    <button type="submit" class="submit-btn">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/login.js"></script>
</body>
</html>
<?php include '../includes/footer.php'; ?>
