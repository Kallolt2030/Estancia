<?php include '../includes/auth.php'; ?>
<?php include 'control_user.php'; ?>
<?php include '../includes/headerAD.php'; ?>
<?php
// Conexión a la base de datos (el código PHP se mantiene igual)
$conn = new mysqli("localhost", "root", "", "estancia", "3306");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Registro de usuario
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
        echo "<div class='alert success'><span class='icon'>✓</span> Usuario registrado correctamente.</div>";
    } else {
        echo "<div class='alert error'><span class='icon'>✗</span> Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Registro de paciente
if (isset($_POST['registrar_paciente'])) {
    $nip = $_POST['paciente_nip'];
    $nombre = $_POST['paciente_nombre'];
    $correo = $_POST['paciente_correo'];
    $telefono = $_POST['paciente_telefono'];
    $diagnostico = $_POST['paciente_diagnostico'];

    $foto_nombre = uniqid() . "_" . basename($_FILES['paciente_foto']['name']);
    $foto_temp = $_FILES['paciente_foto']['tmp_name'];
    $foto_ruta = "fotos/" . $foto_nombre;

    if (!file_exists("fotos")) {
        mkdir("fotos", 0777, true);
    }

    if (move_uploaded_file($foto_temp, $foto_ruta)) {
        $sql = "INSERT INTO pacientes (nip, nombre, correo, telefono, diagnostico, foto) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $nip, $nombre, $correo, $telefono, $diagnostico, $foto_ruta);

        if ($stmt->execute()) {
            echo "<div class='alert success'><span class='icon'>✓</span> Paciente registrado correctamente.</div>";
            echo "<div class='diagnostico-box'><strong>📄 Diagnóstico:</strong> $diagnostico</div>";
            echo "<div class='foto-box'><strong>🖼️ Foto:</strong><br><img src='$foto_ruta' class='foto-preview'></div>";
        } else {
            echo "<div class='alert error'><span class='icon'>✗</span> Error al registrar paciente: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert error'><span class='icon'>✗</span> Error al subir la foto.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro UTA | Sistema Moderno</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/registroAD.css">
    <script>
        function generarNip() {
            fetch('generar_nip.php')
                .then(response => response.text())
                .then(nip => {
                    document.getElementById('nip').value = nip;
                    const nipField = document.getElementById('nip');
                    nipField.style.boxShadow = '0 0 15px rgba(76, 201, 240, 0.7)';
                    setTimeout(() => {
                        nipField.style.boxShadow = 'none';
                    }, 1000);
                })
                .catch(error => {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert error';
                    alertDiv.innerHTML = `<span class="icon">✗</span> Error al generar NIP: ${error}`;
                    document.querySelector('.container').prepend(alertDiv);
                    setTimeout(() => alertDiv.remove(), 5000);
                });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name || 'Ningún archivo seleccionado';
                    const label = document.querySelector('.file-input-label span');
                    if (label) {
                        label.textContent = fileName;
                    }
                });
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="glow-text" style="color: white; margin-bottom: 30px; text-align: center;">Sistema de Registro UTA</h1>
        
        <div class="card">
            <h2>Registro de Usuario</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="nip">NIP (Identificación única)</label>
                    <input type="text" id="nip" name="nip" placeholder="Haz clic en 'Generar NIP'" readonly required>
                </div>
                <button type="button" onclick="generarNip()" class="btn-generar">
                    <i class="fas fa-key"></i> Generar NIP Automático
                </button>
                
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Juan Pérez López" required>
                </div>
                
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" placeholder="Ej: usuario@example.com" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Ej: 5512345678">
                </div>
                
                <div class="form-group">
                    <label for="rol">Rol del usuario</label>
                    <select id="rol" name="rol">
                        <option value="familiar">Familiar</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <input type="submit" name="registrar_usuario" value="Registrar Usuario">
            </form>
        </div>
        
        <div class="card">
            <h2>Registro de Paciente</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="paciente_nip">NIP del familiar asociado</label>
                    <input type="text" id="paciente_nip" name="paciente_nip" placeholder="NIP del usuario registrado" required>
                </div>
                
                <div class="form-group">
                    <label for="paciente_nombre">Nombre del paciente</label>
                    <input type="text" id="paciente_nombre" name="paciente_nombre" placeholder="Ej: María González Sánchez" required>
                </div>
                
                <div class="form-group">
                    <label for="paciente_correo">Correo del paciente</label>
                    <input type="email" id="paciente_correo" name="paciente_correo" placeholder="Ej: paciente@example.com" required>
                </div>
                
                <div class="form-group">
                    <label for="paciente_telefono">Teléfono del paciente</label>
                    <input type="text" id="paciente_telefono" name="paciente_telefono" placeholder="Ej: 5512345678">
                </div>
                
                <div class="form-group">
                    <label for="paciente_diagnostico">Diagnóstico médico</label>
                    <textarea id="paciente_diagnostico" name="paciente_diagnostico" rows="4" placeholder="Describa el diagnóstico del paciente..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Fotografía del paciente</label>
                    <div class="file-input-wrapper">
                        <label class="file-input-label">
                            <i class="fas fa-camera"></i>
                            <span>Seleccionar archivo...</span>
                        </label>
                        <input type="file" name="paciente_foto" accept="image/*" required>
                    </div>
                </div>
                
                <input type="submit" name="registrar_paciente" value="Registrar Paciente">
            </form>
        </div>
    </div>
</body>
</html>
