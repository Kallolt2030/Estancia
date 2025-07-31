<?php
include '../control/control_user.php';
include '../includes/headerAD.php';
include '../control/bd.php';

$mensaje_exito = '';
$mensaje_error = '';
$row = [];

// Eliminar post
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        // Obtener nombre de imagen
        $stmt = $pdo->prepare("SELECT image FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post && !empty($post['image'])) {
            unlink('../imagenes/' . $post['image']);
        }

        // Eliminar post
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $mensaje_exito = "Entrada eliminada correctamente.";
    } catch (PDOException $e) {
        $mensaje_error = "Error al eliminar la entrada: " . $e->getMessage();
    }
}

// Obtener todos los posts
try {
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje_error = "Error al obtener las entradas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="../assets/css/indexAD.css">
</head>
<body>

<div class="form-section">
    <h3>Panel de Administrador</h3>

    <?php if ($mensaje_exito): ?>
        <p class="mensaje exito"><?php echo $mensaje_exito; ?></p>
    <?php endif; ?>

    <?php if ($mensaje_error): ?>
        <p class="mensaje error"><?php echo $mensaje_error; ?></p>
    <?php endif; ?>

    <a href="/admin1/crear.php" class="btn btn-primary">+ Agregar entrada</a>

    <table border="1" cellpadding="8" class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo $post['id']; ?></td>
                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                    <td>
                        <img src="/imagenes/<?php echo htmlspecialchars($post['image']); ?>" class="imagen-tabla" width="100">
                    </td>
                    <td>
                        <a href="/admin1/actualizar.php?id=<?php echo $post['id']; ?>">Editar</a> |
                        <a href="?delete=<?php echo $post['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta entrada?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
