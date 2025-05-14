<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'];
    if ($nip === '1234') { // Reemplazar con verificación real en DB
        $_SESSION['nip'] = $nip;
        header('Location: /dashboard/index.php');
        exit;
    } else {
        $error = "NIP incorrecto.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/login.css">

<h2>Ingreso</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<!-- Formulario de ingreso -->
<form method="post" class="login-form">
    <label for="nip">NIP:</label><br>
    <input type="password" name="nip" required class="input-nip"><br>
    
    <!-- Botón de puerta verde -->
    <button type="submit" class="enter-button">
        Entrar
    </button>
</form>

<?php include '../includes/footer.php'; ?>
