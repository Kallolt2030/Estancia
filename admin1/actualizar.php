<?php
include '../control/control_user.php';
include '../control/bd.php';

$mensaje_exito = '';
$mensaje_error = '';

// Validar ID del post
$id = $_GET['id'] ?? null;
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /index.php');
    exit;
}

// Obtener datos actuales del post
try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        header('Location: /index.php');
        exit;
    }

    $title = $post['title'];
    $content = $post['content'];
    $imageActual = $post['image'];

} catch (PDOException $e) {
    $mensaje_error = "Error al cargar el post: " . $e->getMessage();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $image = $_FILES['image'] ?? null;

    if (empty($title)) {
        $mensaje_error = "El título es obligatorio.";
    } elseif (empty($content)) {
        $mensaje_error = "El contenido es obligatorio.";
    } else {
        try {
            $nombreImagen = $imageActual;
            $carpetaImagenes = '../imagenes';

            // Reemplazar imagen si se sube una nueva
            if ($image && $image['name']) {
                if (!is_dir($carpetaImagenes)) {
                    mkdir($carpetaImagenes, 0755, true);
                }

                // Eliminar imagen anterior
                if (file_exists($carpetaImagenes . '/' . $imageActual)) {
                    unlink($carpetaImagenes . '/' . $imageActual);
                }

                // Subir nueva imagen
                $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';
                move_uploaded_file($image['tmp_name'], $carpetaImagenes . '/' . $nombreImagen);
            }

            // Actualizar en BD
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
            $stmt->execute([$title, $content, $nombreImagen, $id]);

            // ✅ REDIRECCIÓN ANTES DE CUALQUIER SALIDA HTML
            header('Location: /admin1?resultado=2');
            exit;

        } catch (PDOException $e) {
            $mensaje_error = "Error al actualizar el post: " . $e->getMessage();
        }
    }
}

// Solo ahora incluimos HTML
include $_SERVER['DOCUMENT_ROOT'] . '/includes/headerAD.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Post</title>
    <link rel="stylesheet" href="../assets/css/indexAD.css">
</head>
<body>

<div class="form-section">
    <h3>Actualizar Entrada</h3>

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

        <label>Imagen Actual:</label><br>
        <img src="/imagenes/<?php echo htmlspecialchars($imageActual); ?>" width="200"><br><br>

        <label for="image">Nueva Imagen (opcional):</label>
        <input type="file" name="image" id="image" accept="image/jpeg,image/png">

        <button type="submit" class="btn-ingrediente">Actualizar Post</button>
    </form>
</div>

</body>
</html>
