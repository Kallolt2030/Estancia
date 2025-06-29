<?php
session_start();

// Conexión a la base de datos
include '../control/bd.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'];

    try {
        // Consulta el nip usando PDO
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nip = :nip");
        $stmt->bindParam(':nip', $nip, PDO::PARAM_STR);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $_SESSION['nip'] = $resultado['nip'];
            $_SESSION['nombre'] = $resultado['nombre'];
            $_SESSION['rol'] = $resultado['rol'];

            if ($resultado['rol'] === 'admin') {
                header('Location: ../admin/registro.php');
            } else {
                header('Location: ../dashboard/index.php');
            }
            exit;
        } else {
            $error = "NIP incorrecto o no registrado.";
        }

        $stmt->closeCursor();
    } catch (PDOException $e) {
        // Captura cualquier error de la base de datos
        die("Error al ejecutar la consulta: " . $e->getMessage());
    }
}

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
