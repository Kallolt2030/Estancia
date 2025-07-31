<?php
require '../../includes/funciones.php';
$auth = estaAutenticado();

if(!$auth) {
  header('Location: /');
  exit;
}

$db = conectarDB();

// Obtener el ID del post a actualizar
$id = $_GET['id'] ?? null;
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id) {
  header('Location: /admin1');
  exit;
}

// Consultar los datos del post actual
$query = "SELECT * FROM posts WHERE id = $id";
$resultado = mysqli_query($db, $query);
$post = mysqli_fetch_assoc($resultado);

if(!$post) {
  header('Location: /admin1');
  exit;
}

$errores = [];

$title = $post['title'];
$content = $post['content'];
$imageActual = $post['image'];

// Procesar el formulario al enviar
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = mysqli_real_escape_string($db, $_POST['title']);
  $content = mysqli_real_escape_string($db, $_POST['content']);

  $image = $_FILES['image'];

  if(!$title) {
    $errores[] = "El Título es obligatorio";
  }
  if(!$content) {
    $errores[] = "El Contenido es obligatorio";
  }

  if(empty($errores)) {
    $nombreImagen = $imageActual;

    if($image['name']) {
      // Carpeta de imágenes
      $carpetaImagenes = '../../imagenes/';
      if(!is_dir($carpetaImagenes)) {
        mkdir($carpetaImagenes);
      }

      // Eliminar imagen previa
      if(file_exists($carpetaImagenes . $imageActual)) {
        unlink($carpetaImagenes . $imageActual);
      }

      // Subir la nueva imagen
      $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
      move_uploaded_file($image['tmp_name'], $carpetaImagenes . $nombreImagen);
    }

    // Actualizar en la base de datos
    $query = "UPDATE posts SET title = '$title', content = '$content', image = '$nombreImagen' WHERE id = $id";
    $resultado = mysqli_query($db, $query);

    if($resultado) {
      header('Location: /admin1?resultado=2'); // resultado=2 para "actualizado correctamente"
      exit;
    }
  }
}

includeTemplate('headerAdmin');
?>

<main class="admin-update-post">
  <h1 class="admin-update-title">Actualizar Post</h1>

  <?php if(!empty($errores)): ?>
    <ul class="admin-update-errors">
      <?php foreach($errores as $error): ?>
        <li><?php echo $error; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form class="admin-update-form" method="POST" enctype="multipart/form-data" novalidate>
    <div class="admin-update-group">
      <label for="title" class="admin-update-label">Título:</label>
      <input type="text" id="title" name="title" class="admin-update-input" value="<?php echo htmlspecialchars($title); ?>">
    </div>

    <div class="admin-update-group">
      <label for="content" class="admin-update-label">Contenido:</label>
      <textarea id="content" name="content" class="admin-update-textarea"><?php echo htmlspecialchars($content); ?></textarea>
    </div>

    <div class="admin-update-group">
      <label for="image" class="admin-update-label">Imagen Actual:</label>
      <img src="/imagenes/<?php echo htmlspecialchars($imageActual); ?>" alt="Imagen actual" class="admin-update-preview">
    </div>

    <div class="admin-update-group">
      <label for="image" class="admin-update-label">Subir Nueva Imagen:</label>
      <input type="file" id="image" name="image" class="admin-update-file" accept="image/jpeg, image/png">
    </div>

    <input type="submit" value="Actualizar Post" class="admin-update-submit">
  </form>
</main>

<?php
mysqli_close($db);
?>
