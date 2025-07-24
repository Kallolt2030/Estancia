<?php
session_start();
include '../control/bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nip = :nip");
        $stmt->bindParam(':nip', $nip, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($usuario) {
            $_SESSION['nip'] = $usuario['nip'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            switch ($usuario['rol']) {
                case 'admin':
                    header('Location: ../admin/registro.php');
                    break;
                case 'familiar':
                    header('Location: ../dashboard/index.php');
                    break;
                case 'medico':
                case 'cuidador':
                case 'cocina':
                case 'enfermeria':
                case 'kinesica':    
                    header('Location: ../trabajadores/formExp.php');
                    break;
                default:
                    $error = "Rol desconocido.";
            }
            exit;
        } else {
            $error = "NIP incorrecto o no registrado.";
        }

    } catch (PDOException $e) {
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
