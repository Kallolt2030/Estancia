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
<h2>Ingreso</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    <label for="nip">NIP:</label>
    <input type="password" name="nip" required>
    <button type="submit">Entrar</button>
</form>
<?php include '../includes/footer.php'; ?>
