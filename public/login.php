<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'];
    if ($nip === '1234') {
        $_SESSION['nip'] = $nip;
        header('Location: ../admin/registro.php');
        exit;
    } else {
        $error = "NIP incorrecto.";
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
            <!-- Puertas -->
            <div class="door door-left"></div>
            <div class="door door-right"></div>
            
            <!-- Formulario centrado -->
            <div class="login-form-container">
                <h1 class="login-title">Ingreso Proyecto UTA</h1>
                <?php if (isset($error)): ?>
                    <div class="error-message"><?= $error ?></div>
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