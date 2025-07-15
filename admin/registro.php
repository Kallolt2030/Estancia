<?php
include '../control/auth.php';
include '../control/control_user.php';
include '../includes/headerAD.php';
include '../control/bd.php';

require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Api\Upload\UploadApi; // ✅ AQUÍ SÍ es correcto

\Cloudinary\Configuration\Configuration::instance([
    'cloud' => [
        'cloud_name' => 'duss3etn9',
        'api_key'    => '397775464978671',
        'api_secret' => 'xCdCJYPqRApY7LYWaAILyQUsT-g',
    ],
    'url' => [
        'secure' => true
    ]
]);


// Registro de usuario
if (isset($_POST['registrar_usuario'])) {
    $nip = $_POST['nip'];
    $nombre = $_POST['usuario_nombre'];
    $correo = $_POST['usuario_correo'];
    $telefono = $_POST['usuario_telefono'];
    $rol = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nip, nombre, correo, telefono, rol) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nip, $nombre, $correo, $telefono, $rol]);
    echo "<script>
        alert('✅ Usuario registrado correctamente');
        window.location.href = window.location.href;
    </script>";
    $stmt->close();
}

// Registro de paciente
if (isset($_POST['registrar_paciente'])) {
    $nip = $_POST['paciente_nip'];
    $nombre = $_POST['paciente_nombre'];
    $telefono = $_POST['paciente_telefono'];
    $condiciones = isset($_POST['condiciones']) ? $_POST['condiciones'] : [];

    // ❌ QUITAMOS ESTA VALIDACIÓN para permitir más de un paciente por NIP
    // $stmt = $pdo->prepare("SELECT COUNT(*) FROM pacientes WHERE nip = ?");
    // $stmt->execute([$nip]);
    // $existe = $stmt->fetchColumn();
    // if ($existe > 0) {
    //     echo "<script>
    //     alert('✅ Usuario registrado correctamente');
    //     window.location.href = window.location.href;
    // </script>";
    //     return;
    // }

   // ✔️ Si se selecciona "Otra", incluir lo que se escribe
// Si "Otra" está seleccionada, incluir el texto que el usuario escribe en el campo de texto
if (in_array('Otra', $condiciones) && !empty($_POST['otra_condicion_texto'])) {
    // Eliminar "Otra" si ya está en el array de condiciones
    $condiciones = array_diff($condiciones, ['Otra']);
    // Añadir el texto proporcionado por el usuario
    $condiciones[] = $_POST['otra_condicion_texto'];
}

// Si "Otra" está seleccionada pero no se ha escrito texto, eliminar "Otra"
if (in_array('Otra', $condiciones) && empty($_POST['otra_condicion_texto'])) {
    $condiciones = array_diff($condiciones, ['Otra']);
}

// Convertir el array de condiciones a una cadena separada por comas
$condiciones = implode(", ", $condiciones); 


    $foto_url = "";
    if (!empty($_FILES['paciente_foto']['tmp_name'])) {
        try {
            $resultado = (new UploadApi())->upload($_FILES['paciente_foto']['tmp_name'], [
                'folder' => 'pacientes_fotos',
                'public_id' => uniqid()
            ]);
            $foto_url = $resultado['secure_url'];
        } catch (Exception $e) {
            echo "<script>alert('⚠️ Error al subir la imagen a Cloudinary: " . $e->getMessage() . "');</script>";
            $foto_url = "";
        }
    }

    // ✔️ Insertar sin problemas múltiples pacientes con mismo NIP
    $sql = "INSERT INTO pacientes (nip, nombre, telefono, condiciones, foto) 
            VALUES (:nip, :nombre, :telefono, :condiciones, :foto)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nip' => $nip,
        ':nombre' => $nombre,
        ':telefono' => $telefono,
        ':condiciones' => $condiciones,
        ':foto' => $foto_url
    ]);
    echo "<script>
        alert('✅ Paciente registrado correctamente');
        window.location.href = window.location.href;
    </script>";
}


// Obtener usuarios
$usuarios_stmt = $pdo->query("SELECT nip, nombre FROM usuarios WHERE rol = 'familiar'");
$usuarios = $usuarios_stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro UTA | Sistema Moderno</title>
    <link rel="stylesheet" href="../assets/css/registroAD.css">
    <script>
        function generarNip() {
            fetch('../control/generar_nip.php')
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

        document.addEventListener('DOMContentLoaded', function () {
            const chkOtra = document.getElementById('chkOtraCondicion');
            const inputOtra = document.getElementById('otra_condicion_input');

            chkOtra.addEventListener('change', function () {
                inputOtra.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) {
                    inputOtra.value = '';
                }
            });
        });
    </script>
</head>
<body id="registro-uta-body">
    <div id="registro-uta-container" class="registro-container">
        <h1 class="registro-glow-text">Sistema de Registro</h1>
        
        <div class="registro-card">
            <h2 class="registro-subtitulo">Registro de Usuario</h2>
            <!-- Registro de Usuario -->
<form method="POST" class="registro-form">
    <div class="registro-form-group">
        <label for="nip">NIP</label>
        <input type="text" id="nip" name="nip" readonly required>
    </div>

    <button type="button" onclick="generarNip()" class="btn-generar-nip">
        <i class="fas fa-key"></i> Generar NIP Automático
    </button>

    <div class="registro-form-group">
        <label for="usuario_nombre">Nombre completo</label>
        <input type="text" id="usuario_nombre" name="usuario_nombre" required>
    </div>

    <div class="registro-form-group">
        <label for="usuario_correo">Correo electrónico</label>
        <input type="email" id="usuario_correo" name="usuario_correo" required>
    </div>

    <div class="registro-form-group">
        <label for="usuario_telefono">Teléfono</label>
        <input type="text" id="usuario_telefono" name="usuario_telefono">
    </div>

    <div class="registro-form-group">
        <label for="rol">Rol del usuario</label>
        <select id="rol" name="rol">
            <option value="familiar">Familiar</option>
            <option value="admin">Administrador</option>
        </select>
    </div>

    <input type="submit" name="registrar_usuario" value="Registrar Usuario" class="btn-submit-usuario">
</form>

        </div>

        <div class="registro-card">
            <h2 class="registro-subtitulo">Registro de Paciente</h2>
            <form method="POST" enctype="multipart/form-data" class="registro-form">
                <div class="registro-form-group">
                    <label for="paciente_nip">Familiar asociado</label>
                    <select name="paciente_nip" id="paciente_nip" required>
                        <option value="">-- Selecciona un familiar --</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= htmlspecialchars($usuario['nip']) ?>">
                                <?= htmlspecialchars($usuario['nombre']) ?> (<?= htmlspecialchars($usuario['nip']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="registro-form-group">
                    <label for="paciente_nombre">Nombre del paciente</label>
                    <input type="text" id="paciente_nombre" name="paciente_nombre" placeholder="Ej: María González Sánchez" required>
                </div>

                <div class="registro-form-group">
                    <label for="paciente_telefono">Teléfono del paciente</label>
                    <input type="text" id="paciente_telefono" name="paciente_telefono" placeholder="Ej: 5512345678">
                </div>

                <div class="registro-form-group">
                    <label>Condiciones médicas generales</label>
                    <div class="registro-checkbox-group">
                        <label><input type="checkbox" name="condiciones[]" value="Hipertensión"> Hipertensión</label>
                        <label><input type="checkbox" name="condiciones[]" value="Diabetes"> Diabetes</label>
                        <label><input type="checkbox" name="condiciones[]" value="Problemas del corazón"> Problemas del corazón</label>
                        <label><input type="checkbox" name="condiciones[]" value="Problemas de movilidad"> Problemas de movilidad</label>
                        <label><input type="checkbox" name="condiciones[]" value="Problemas respiratorios"> Problemas respiratorios</label>
                        <label><input type="checkbox" name="condiciones[]" value="Pérdida de memoria"> Pérdida de memoria</label>
                        <label>
                            <input type="checkbox" name="condiciones[]" value="Otra" id="chkOtraCondicion"> Otra condición
                        </label>
                        <input type="text" id="otra_condicion_input" name="otra_condicion_texto" placeholder="Especifica otra condición..." style="display:none; margin-top: 10px;">
                    </div>
                </div>

                <div class="registro-form-group">
    <label>Fotografía del paciente</label>
    <div class="registro-file-wrapper">
        <label class="registro-file-label" for="paciente_foto">
            <i class="fas fa-camera"></i>
            <span>Seleccionar archivo...</span>
        </label>
        <input type="file" id="paciente_foto" name="paciente_foto" accept="image/*" >
    </div>
    <!-- Imagen previa -->
    <img id="preview" src="" style="
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
    margin-top: 10px;
    display: none;
    border: 2px solid #ccc;
">
</div>


                <input type="submit" name="registrar_paciente" value="Registrar Paciente" class="btn-submit-paciente">
            </form>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const fotoInput = document.getElementById('paciente_foto');
    const preview = document.getElementById('preview');

    fotoInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    });
});
</script>

</body>
</html>
