<?php
include '../control/auth.php'; 
include '../control/control_user.php'; 
include '../includes/headerAD.php'; 
include '../control/bd.php';

require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;

// Configurar Cloudinary
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'duss3etn9',
        'api_key'    => '397775464978671', 
        'api_secret' => 'xCdCJYPqRApY7LYWaAILyQUsT-g',
    ],
    'url' => [
        'secure' => true
    ]
]);

// Obtener usuarios y pacientes
$usuarios_stmt = $pdo->query("SELECT nip, nombre, correo, telefono, rol FROM usuarios");
$usuarios = $usuarios_stmt->fetchAll();

$pacientes_stmt = $pdo->query("
    SELECT p.id_paciente, p.nip, p.nombre AS nombre_paciente, p.telefono, p.condiciones, p.foto, u.nombre AS nombre_familiar 
    FROM pacientes p
    JOIN usuarios u ON p.nip = u.nip
");

$pacientes = $pacientes_stmt->fetchAll();

// Eliminar usuario
if (isset($_GET['eliminar_usuario'])) {
    $nip_usuario = $_GET['eliminar_usuario'];

    echo "<script>
        if(confirm('¿Estás seguro de eliminar este usuario y su paciente asociado?')) {
            window.location.href = 'confirmar_eliminar.php?tipo=usuario&nip=$nip_usuario';
        } else {
            window.location.href = 'editar.php';
        }
    </script>";
    exit();
}

// Eliminar paciente
if (isset($_GET['eliminar_paciente'])) {
    $id_paciente = $_GET['eliminar_paciente'];

    $stmt = $pdo->prepare("SELECT foto FROM pacientes WHERE id_paciente = ?");
    $stmt->execute([$id_paciente]);
    $paciente = $stmt->fetch();
    $foto_ruta = $paciente['foto'];

    if (!empty($foto_ruta)) {
        try {
            $url_parts = parse_url($foto_ruta);
            $path = $url_parts['path'];
            $segments = explode('/', $path);

            $upload_index = array_search('upload', $segments);
            $public_parts = array_slice($segments, $upload_index + 2);

            $last = array_pop($public_parts);
            $last = pathinfo($last, PATHINFO_FILENAME);
            $public_parts[] = $last;

            $public_id = implode('/', $public_parts);

            $cloudinary->uploadApi()->destroy($public_id, ['invalidate' => true]);
        } catch (Exception $e) {
            echo "<script>alert('Error al eliminar imagen: " . $e->getMessage() . "');</script>";
        }
    }

    echo "<script>
        if(confirm('¿Estás seguro de eliminar este paciente?')) {
            window.location.href = 'confirmar_eliminar.php?tipo=paciente&id=$id_paciente';
        } else {
            window.location.href = 'editar.php';
        }
    </script>";
    exit();
}

// Procesar edición de usuario
if (isset($_POST['actualizar_usuario'])) {
    $nip_usuario = $_POST['nip'];
    $nombre = $_POST['usuario_nombre'];
    $correo = $_POST['usuario_correo'];
    $telefono = $_POST['usuario_telefono'];
    $rol = $_POST['rol'];

    $sql = "UPDATE usuarios SET nombre = ?, correo = ?, telefono = ?, rol = ? WHERE nip = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $correo, $telefono, $rol, $nip_usuario]);

    echo "<script>alert('Usuario actualizado correctamente'); window.location.href = 'editar.php';</script>";
    exit();
}

// Procesar edición de paciente
if (isset($_POST['actualizar_paciente'])) {
    $id_paciente = $_POST['id_paciente'];
    $nombre = $_POST['paciente_nombre'];
    $telefono = $_POST['paciente_telefono'];
    $condiciones = [];

if (isset($_POST['condiciones'])) {
    $condiciones = $_POST['condiciones'];
}

if (!empty($_POST['otra_condicion'])) {
    $condiciones[] = trim($_POST['otra_condicion']);
}

$condiciones_str = implode(", ", $condiciones);

    $foto_ruta = $_POST['foto_actual'];

    if (!empty($_FILES['paciente_foto']['tmp_name'])) {
        if (!empty($foto_ruta)) {
            try {
                $url_parts = parse_url($foto_ruta);
                $path = $url_parts['path'];
                $segments = explode('/', $path);

                $upload_index = array_search('upload', $segments);
                $public_parts = array_slice($segments, $upload_index + 2);

                $last = array_pop($public_parts);
                $last = pathinfo($last, PATHINFO_FILENAME);
                $public_parts[] = $last;

                $public_id = implode('/', $public_parts);

                $cloudinary->uploadApi()->destroy($public_id, ['invalidate' => true]);
            } catch (Exception $e) {
                echo "<script>alert('Error al eliminar imagen anterior: " . $e->getMessage() . "');</script>";
            }
        }

        try {
            $uploadResult = $cloudinary->uploadApi()->upload(
                $_FILES['paciente_foto']['tmp_name'],
                [
                    'public_id' => 'pacientes/' . uniqid(),
                    'overwrite' => true
                ]
            );
            $foto_ruta = $uploadResult['secure_url'];
        } catch (Exception $e) {
            echo "<script>alert('Error al subir la nueva imagen: " . $e->getMessage() . "');</script>";
        }
    }

    $sql = "UPDATE pacientes SET nombre = ?, telefono = ?, condiciones = ?, foto = ? WHERE id_paciente = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $telefono, $condiciones_str, $foto_ruta, $id_paciente]);


    echo "<script>alert('Paciente actualizado correctamente'); window.location.href = 'editar.php';</script>";
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios y Pacientes</title>
    <link rel="stylesheet" href="../assets/css/registroAD.css">
</head>
<body id="registro-uta-body">
    <div id="registro-uta-container" class="registro-container">
        <h1 class="registro-glow-text">Gestión de Usuarios y Pacientes</h1>

        <?php if (isset($_GET['editar_usuario'])): 
            $nip_usuario = $_GET['editar_usuario'];
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nip = ?");
            $stmt->execute([$nip_usuario]);
            $usuario = $stmt->fetch();
        ?>
            <!-- Formulario de edición de usuario -->
            <div class="formulario-edicion">
                <h3>Editar Usuario</h3>
                <form method="POST">
                    <input type="hidden" name="nip" value="<?= $usuario['nip'] ?>">
                    
                    <div class="registro-form-group">
                        <label for="usuario_nombre">Nombre completo</label>
                        <input type="text" id="usuario_nombre" name="usuario_nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                    </div>

                    <div class="registro-form-group">
                        <label for="usuario_correo">Correo electrónico</label>
                        <input type="email" id="usuario_correo" name="usuario_correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                    </div>

                    <div class="registro-form-group">
                        <label for="usuario_telefono">Teléfono</label>
                        <input type="text" id="usuario_telefono" name="usuario_telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">
                    </div>

                    <div class="registro-form-group">
                        <label for="rol">Rol del usuario</label>
                        <select id="rol" name="rol">
                            <option value="familiar" <?= $usuario['rol'] == 'familiar' ? 'selected' : '' ?>>Familiar</option>
                            <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                        </select>
                    </div>

                    <button type="submit" name="actualizar_usuario" class="btn-submit-usuario">Actualizar Usuario</button>
                    <a href="editar.php" class="btn-cancelar">Cancelar</a>
                </form>
            </div>
        
        <?php elseif (isset($_GET['editar_paciente'])): 
            $id_paciente = $_GET['editar_paciente'];
            $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE id_paciente = ?");
            $stmt->execute([$id_paciente]);
            $paciente = $stmt->fetch();
            $condiciones_actuales = explode(", ", $paciente['condiciones']);
        ?>
            <!-- Formulario de edición de paciente -->
            <div class="formulario-edicion">
                <h3>Editar Paciente</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_paciente" value="<?= $paciente['id_paciente'] ?>">
                    <input type="hidden" name="nip" value="<?= $paciente['nip'] ?>">
                    <input type="hidden" name="foto_actual" value="<?= $paciente['foto'] ?>">
                    
                    <div class="registro-form-group">
                        <label for="paciente_nombre">Nombre del paciente</label>
                        <input type="text" id="paciente_nombre" name="paciente_nombre" value="<?= htmlspecialchars($paciente['nombre']) ?>" required>
                    </div>

                    <div class="registro-form-group">
                        <label for="paciente_telefono">Teléfono del paciente</label>
                        <input type="text" id="paciente_telefono" name="paciente_telefono" value="<?= htmlspecialchars($paciente['telefono']) ?>">
                    </div>

                    <div class="registro-form-group">
                        <label>Condiciones médicas generales</label>
                       <div class="registro-checkbox-group">
    <label><input type="checkbox" name="condiciones[]" value="Hipertensión" <?= in_array('Hipertensión', $condiciones_actuales) ? 'checked' : '' ?>> Hipertensión</label>
    <label><input type="checkbox" name="condiciones[]" value="Diabetes" <?= in_array('Diabetes', $condiciones_actuales) ? 'checked' : '' ?>> Diabetes</label>
    <label><input type="checkbox" name="condiciones[]" value="Problemas del corazón" <?= in_array('Problemas del corazón', $condiciones_actuales) ? 'checked' : '' ?>> Problemas del corazón</label>
    <label><input type="checkbox" name="condiciones[]" value="Problemas de movilidad" <?= in_array('Problemas de movilidad', $condiciones_actuales) ? 'checked' : '' ?>> Problemas de movilidad</label>
    <label><input type="checkbox" name="condiciones[]" value="Problemas respiratorios" <?= in_array('Problemas respiratorios', $condiciones_actuales) ? 'checked' : '' ?>> Problemas respiratorios</label>
    <label><input type="checkbox" name="condiciones[]" value="Pérdida de memoria" <?= in_array('Pérdida de memoria', $condiciones_actuales) ? 'checked' : '' ?>> Pérdida de memoria</label>
    <input type="text" name="otra_condicion" placeholder="Especifica otra condición" style="margin-top: 5px;">
</div>

                    </div>

                    <div class="registro-form-group">
                        <label>Fotografía actual</label>
                       <?php
                        $foto = (!empty($paciente['foto'])) ? $paciente['foto'] : '../assets/fotos/default-avatar.jpg';
                        ?>
                        <img src="<?= $foto ?>" alt="Foto actual" class="foto-actual">

                        <label>Nueva fotografía (opcional)</label>
                       <div class="registro-file-wrapper">
                            <label class="registro-file-label" for="paciente_foto">
                                <i class="fas fa-camera"></i>
                                <span>Seleccionar archivo...</span>
                            </label>
                            <input type="file" id="paciente_foto" name="paciente_foto" accept="image/*" >
                        </div>

                        <img id="preview" src="" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; margin-top: 10px; display: none; border: 2px solid #ccc;">
                    </div>

                    <button type="submit" name="actualizar_paciente" class="btn-submit-paciente">Actualizar Paciente</button>
                    <a href="editar.php" class="btn-cancelar">Cancelar</a>
                </form>
            </div>
        <?php else: ?>
            <!-- Mostrar listados si no estamos editando -->

            <!-- Usuarios -->
            <div class="registro-card">
                <h2 class="registro-subtitulo">Usuarios</h2>
                <table>
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['nip']) ?></td>
                                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                <td><?= htmlspecialchars($usuario['correo']) ?></td>
                                <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                                <td><?= htmlspecialchars($usuario['rol']) ?></td>
                                <td>
                                    <a href="?editar_usuario=<?= $usuario['nip'] ?>" class="btn-editar">Editar</a>
                                    <a href="?eliminar_usuario=<?= $usuario['nip'] ?>" class="btn-eliminar">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pacientes -->
            <div class="registro-card">
                <h2 class="registro-subtitulo">Pacientes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre Familiar</th>
                            <th>Nombre del paciente</th>
                            <th>Teléfono</th>
                            <th>Condiciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pacientes as $paciente): ?>
                            <?php
                                $foto = (!empty($paciente['foto'])) ? $paciente['foto'] : '../assets/fotos/default-avatar.jpg';
                            ?>
                            <tr>
                                <td><img src="<?= $foto ?>" alt="Foto" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"></td>
                                <td><?= htmlspecialchars($paciente['nombre_familiar']) ?></td>
                                <td><?= htmlspecialchars($paciente['nombre_paciente']) ?></td>
                                <td><?= htmlspecialchars($paciente['telefono']) ?></td>
                                <td><?= htmlspecialchars($paciente['condiciones']) ?></td>
                                <td>
                                    <a href="?editar_paciente=<?= $paciente['id_paciente'] ?>" class="btn-editar">Editar</a>
                                    <a href="?eliminar_paciente=<?= $paciente['id_paciente'] ?>" class="btn-eliminar">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const fotoInput = document.getElementById('paciente_foto');
        const preview = document.getElementById('preview');

        if (fotoInput) {
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
    </script>
</body>
</html>
