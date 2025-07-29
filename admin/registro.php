<?php
include '../control/auth.php';
include '../control/control_user.php';
include '../includes/headerAD.php';
include '../control/bd.php';

require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Api\Upload\UploadApi;

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
    // Validar campos obligatorios
    $camposRequeridos = ['nip', 'usuario_nombre', 'usuario_correo', 'rol'];
    $errorCampos = [];
    
    foreach ($camposRequeridos as $campo) {
        if (empty($_POST[$campo])) {
            $errorCampos[] = ucfirst(str_replace('_', ' ', $campo));
        }
    }
    
    if (!empty($errorCampos)) {
        $mensajeError = '⚠️ Los siguientes campos son obligatorios: ' . implode(', ', $errorCampos);
        echo "<script>
            alert('".addslashes($mensajeError)."');
            window.history.back();
        </script>";
        exit();
    }

    $nip = $_POST['nip'];
    $nombre = $_POST['usuario_nombre'];
    $correo = $_POST['usuario_correo'];
    $telefono = $_POST['usuario_telefono'] ?? null;
    $rol = $_POST['rol'];

    // Validar formato del NIP
    if (!preg_match('/^[0-9]{6,10}$/', $nip)) {
        echo "<script>
            alert('⚠️ El NIP debe contener solo números y tener entre 6 y 10 dígitos');
            window.history.back();
        </script>";
        exit();
    }

    // Validar formato del correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('⚠️ Por favor ingresa un correo electrónico válido');
            window.history.back();
        </script>";
        exit();
    }

    // Validar rol seleccionado
    $rolesPermitidos = ['familiar', 'admin', 'cocina', 'cuidador', 'medico', 'enfermeria', 'kinesica'];
    if (!in_array($rol, $rolesPermitidos)) {
        echo "<script>
            alert('⚠️ Por favor selecciona un rol válido');
            window.history.back();
        </script>";
        exit();
    }

    // Validar si el NIP o el correo ya existen
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nip = ? OR correo = ?");
    $stmt->execute([$nip, $correo]);
    $usuarioExistente = $stmt->fetch();

    if ($usuarioExistente) {
        echo "<script>
            alert('⚠️ El NIP o el correo ya están registrados. Por favor verifica los datos.');
            window.location.href = 'registro.php';
        </script>";
        exit();
    }

    try {
        // Insertar el usuario
        $sql = "INSERT INTO usuarios (nip, nombre, correo, telefono, rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nip, $nombre, $correo, $telefono, $rol]);

        echo "<script>
            alert('✅ Usuario registrado correctamente');
            window.location.href = 'registro.php'; // Recarga la página para resetear el formulario
        </script>";
    } catch (PDOException $e) {
        echo "<script>
            alert('⚠️ Error al registrar el usuario: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script>";
    }
    exit();
}

// Registro de paciente
if (isset($_POST['registrar_paciente'])) {
    // Validar campos obligatorios
    if (empty($_POST['paciente_nip']) || empty($_POST['paciente_nombre'])) {
        echo "<script>
            alert('⚠️ El familiar asociado y el nombre del paciente son obligatorios');
            window.history.back();
        </script>";
        exit();
    }

    $nip = $_POST['paciente_nip'];
    $nombre = $_POST['paciente_nombre'];
    $telefono = $_POST['paciente_telefono'] ?? '';
    $condiciones = isset($_POST['condiciones']) ? $_POST['condiciones'] : [];

    // Procesar condición "Otra"
    if (in_array('Otra', $condiciones) && !empty($_POST['otra_condicion_texto'])) {
        $condiciones = array_diff($condiciones, ['Otra']);
        $condiciones[] = $_POST['otra_condicion_texto'];
    } elseif (in_array('Otra', $condiciones)) {
        $condiciones = array_diff($condiciones, ['Otra']);
    }

    $condiciones = implode(", ", $condiciones); 

    // Procesar foto
    $foto_url = "";
    if (!empty($_FILES['paciente_foto']['tmp_name'])) {
        try {
            $resultado = (new UploadApi())->upload($_FILES['paciente_foto']['tmp_name'], [
                'folder' => 'pacientes_fotos',
                'public_id' => uniqid()
            ]);
            $foto_url = $resultado['secure_url'];
        } catch (Exception $e) {
            echo "<script>alert('⚠️ Error al subir la imagen: " . addslashes($e->getMessage()) . "');</script>";
        }
    }

    try {
        // Insertar paciente
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
            window.location.href = 'registro.php'; // Recarga la página para resetear el formulario
        </script>";
    } catch (PDOException $e) {
        echo "<script>
            alert('⚠️ Error al registrar el paciente: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script>";
    }
    exit();
}

// Obtener usuarios familiares
$usuarios = [];
try {
    $usuarios_stmt = $pdo->query("SELECT nip, nombre FROM usuarios WHERE rol = 'familiar'");
    $usuarios = $usuarios_stmt->fetchAll();
} catch (PDOException $e) {
    $error_msg = "Error al cargar familiares: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro UTA | Sistema Moderno</title>
    <link rel="stylesheet" href="../assets/css/registroAD.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .required-field::after {
            content: " *";
            color: red;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body id="registro-uta-body">
    <div id="registro-uta-container" class="registro-container">
        <h1 class="registro-glow-text">Sistema de Registro</h1>
        
        <div class="registro-card">
            <h2 class="registro-subtitulo">Registro de Usuario</h2>
            <form method="POST" class="registro-form" id="formUsuario" onsubmit="return validarFormularioUsuario()">
                <div class="registro-form-group">
                    <label for="nip" class="required-field">NIP</label>
                    <input type="text" id="nip" name="nip" readonly required>
                    <button type="button" onclick="generarNip()" class="btn-generar-nip">
                        <i class="fas fa-key"></i> Generar NIP Automático
                    </button>
                </div>

                <div class="registro-form-group">
                    <label for="usuario_nombre" class="required-field">Nombre completo</label>
                    <input type="text" id="usuario_nombre" name="usuario_nombre" required>
                </div>

                <div class="registro-form-group">
                    <label for="usuario_correo" class="required-field">Correo electrónico</label>
                    <input type="email" id="usuario_correo" name="usuario_correo" required>
                </div>

                <div class="registro-form-group">
                    <label for="usuario_telefono">Teléfono</label>
                    <input type="text" id="usuario_telefono" name="usuario_telefono">
                </div>

                <div class="registro-form-group">
                    <label for="rol" class="required-field">Rol del usuario</label>
                    <select id="rol" name="rol" required>
                        <option value="">-- Seleccione un rol --</option>
                        <option value="familiar">Familiar</option>
                        <option value="admin">Administrador</option>
                        <option value="cuidador">Cuidador</option>
                        <option value="medico">Médico</option>
                        <option value="cocina">Cocina</option>
                        <option value="enfermeria">Enfermería</option>
                        <option value="kinesica">Kinesica</option>
                    </select>
                    <div id="rol-error" class="error-message"></div>
                </div>

                <input type="submit" name="registrar_usuario" value="Registrar Usuario" class="btn-submit-usuario">
            </form>
        </div>

        <div class="registro-card">
            <h2 class="registro-subtitulo">Registro de Paciente</h2>
            <form method="POST" enctype="multipart/form-data" class="registro-form" id="formPaciente">
                <div class="registro-form-group">
                    <label for="paciente_nip" class="required-field">Familiar asociado</label>
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
                    <label for="paciente_nombre" class="required-field">Nombre del paciente</label>
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
                        <input type="file" id="paciente_foto" name="paciente_foto" accept="image/*">
                    </div>
                    <img id="preview" src="" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; margin-top: 10px; display: none; border: 2px solid #ccc;">
                </div>

                <input type="submit" name="registrar_paciente" value="Registrar Paciente" class="btn-submit-paciente">
            </form>
        </div>
    </div>

    <script>
        // Generar NIP automático
        function generarNip() {
            fetch('../control/generar_nip.php')
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta del servidor');
                    return response.text();
                })
                .then(nip => {
                    document.getElementById('nip').value = nip;
                    const nipField = document.getElementById('nip');
                    nipField.style.boxShadow = '0 0 15px rgba(76, 201, 240, 0.7)';
                    setTimeout(() => nipField.style.boxShadow = 'none', 1000);
                })
                .catch(error => {
                    alert('Error al generar NIP: ' + error.message);
                });
        }

        // Mostrar/ocultar campo "Otra condición"
        document.addEventListener('DOMContentLoaded', function () {
            const chkOtra = document.getElementById('chkOtraCondicion');
            const inputOtra = document.getElementById('otra_condicion_input');

            if (chkOtra && inputOtra) {
                chkOtra.addEventListener('change', function () {
                    inputOtra.style.display = this.checked ? 'block' : 'none';
                    if (!this.checked) inputOtra.value = '';
                });
            }

            // Vista previa de la imagen
            const fotoInput = document.getElementById('paciente_foto');
            const preview = document.getElementById('preview');

            if (fotoInput && preview) {
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
            }
        });

        // Validación del formulario de usuario
        function validarFormularioUsuario() {
            const rolSelect = document.getElementById('rol');
            const rolError = document.getElementById('rol-error');
            
            if (rolSelect.value === "") {
                rolError.textContent = "Por favor selecciona un rol";
                rolSelect.focus();
                return false;
            }
            
            rolError.textContent = "";
            return true;
        }
    </script>
</body>
</html>