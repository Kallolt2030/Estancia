<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "estancia","3306");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (isset($_POST['registrar_usuario'])) {
    $nip = $_POST['nip'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nip, nombre, correo, telefono, rol) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nip, $nombre, $correo, $telefono, $rol);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ Usuario registrado correctamente.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

if (isset($_POST['registrar_paciente'])) {
    $nip = $_POST['paciente_nip'];
    $nombre = $_POST['paciente_nombre'];
    $correo = $_POST['paciente_correo'];
    $telefono = $_POST['paciente_telefono'];
    $diagnostico = $_POST['paciente_diagnostico'];

    $foto_nombre = $_FILES['paciente_foto']['name'];
    $foto_temp = $_FILES['paciente_foto']['tmp_name'];
    $foto_ruta = "fotos/" . $foto_nombre;

    if (!file_exists("fotos")) {
        mkdir("fotos", 0777, true);
    }

    if (move_uploaded_file($foto_temp, $foto_ruta)) {
        $sql = "INSERT INTO pacientes (nip, nombre, correo, telefono) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nip, $nombre, $correo, $telefono);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>✅ Paciente registrado correctamente.</p>";
            echo "<p><strong>📄 Diagnóstico:</strong> $diagnostico</p>";
            echo "<p><strong>🖼️ Foto:</strong><br><img src='$foto_ruta' width='120'></p>";
        } else {
            echo "<p style='color:red;'>❌ Error al registrar paciente: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red;'>❌ Error al subir la foto.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro UTA</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
        form { background: #fff; padding: 20px; margin-bottom: 40px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select, textarea, button { width: 100%; margin-bottom: 15px; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
        input[type="submit"], button { background: #007bff; color: white; font-weight: bold; cursor: pointer; }
        h2 { margin-top: 0; }
    </style>
    <script>
        function generarNip() {
            fetch('generar_nip.php')
                .then(response => response.text())
                .then(nip => {
                    document.getElementById('nip').value = nip;
                })
                .catch(error => alert('Error al generar NIP: ' + error));
        }
    </script>
</head>
<body>

<h2>Formulario de Registro de Usuario</h2>
<form method="POST">
    <input type="text" id="nip" name="nip" placeholder="NIP (clic en Generar)" readonly required>
    <button type="button" onclick="generarNip()">Generar NIP</button>
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="correo" placeholder="Correo electrónico" required>
    <input type="text" name="telefono" placeholder="Teléfono">
    <select name="rol">
        <option value="familiar">Familiar</option>
        <option value="admin">Admin</option>
    </select>
    <input type="submit" name="registrar_usuario" value="Registrar Usuario">
</form>

<h2>Formulario de Registro de Paciente</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="paciente_nip" placeholder="NIP del familiar (usuario existente)" required>
    <input type="text" name="paciente_nombre" placeholder="Nombre del paciente" required>
    <input type="email" name="paciente_correo" placeholder="Correo del paciente" required>
    <input type="text" name="paciente_telefono" placeholder="Teléfono del paciente">
    <textarea name="paciente_diagnostico" placeholder="Diagnóstico del paciente" required></textarea>
    <input type="file" name="paciente_foto" accept="image/*" required>
    <input type="submit" name="registrar_paciente" value="Registrar Paciente">
</form>

</body>
</html>