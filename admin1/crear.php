<?php
include '../control/control_user.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/headerAD.php';

include '../control/bd.php';

$mensaje_exito = '';
$mensaje_error = '';
$title = '';
$content = '';
date_default_timezone_set('America/Mexico_City');
$published_at = date('Y-m-d H:i:s');

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $image = $_FILES['image'] ?? null;

    if (empty($title)) {
        $mensaje_error = "El título es obligatorio.";
    } elseif (empty($content)) {
        $mensaje_error = "El contenido es obligatorio.";
    } elseif (!$image || $image['error'] != 0) {
        $mensaje_error = "La imagen es obligatoria.";
    } else {
        try {
            // Crear carpeta si no existe
            $carpetaImagenes = '../imagenes';
            if (!is_dir($carpetaImagenes)) {
                mkdir($carpetaImagenes, 0755, true);
            }

            // Generar nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';
            move_uploaded_file($image['tmp_name'], $carpetaImagenes . '/' . $nombreImagen);

            // Insertar en la base de datos
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, image, published_at) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $nombreImagen, $published_at]);

            $mensaje_exito = "Post creado correctamente.";
            // Limpiar campos
            $title = '';
            $content = '';
        } catch (PDOException $e) {
            $mensaje_error = "Error al insertar el post: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Post</title>
    <link rel="stylesheet" href="../assets/css/indexAD.css">
</head>
<body>

<div class="form-section">
    <h3>Crear Nueva Entrada</h3>

    <?php if ($mensaje_exito): ?>
        <p class="mensaje exito"><?php echo $mensaje_exito; ?></p>
    <?php endif; ?>

    <?php if ($mensaje_error): ?>
        <p class="mensaje error"><?php echo $mensaje_error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="title">Título:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>" required>

        <label for="content">Contenido:</label>
        <textarea name="content" id="content" rows="6" required><?php echo htmlspecialchars($content); ?></textarea>

        <label for="image">Imagen:</label>
        <input type="file" name="image" id="image" accept="image/jpeg,image/png" required>

        <button type="submit" class="btn-ingrediente">Crear Post</button>
    </form>
</div>

</body>
</html>
