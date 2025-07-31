<?php
  require '../../includes/funciones.php';
  $auth = estaAutenticado();

  if(!$auth) {
    header('Location: /');
  }

  $db = conectarDB();

  $errores = [];

  $title = '';
  $content = '';
  date_default_timezone_set('America/Mexico_City');
  $published_at = date('Y-m-d H:i:s');

  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string( $db, $_POST['title'] );
    $content = mysqli_real_escape_string( $db, $_POST['content'] );

    $image = $_FILES['image'];

    if(!$title) {
      $errores[] = "El Titulo es Obligatorio";
    }
    if(!$content) {
      $errores[] = "El Contenido es Obligatorio";
    }
    if(!$image['name'] || $image['error']) {
      $errores[] = "La Imagen es Obligatoria";
    }

    if(empty($errores)) {
      $carpetaImagenes = '../../imagenes';
      if(!is_dir($carpetaImagenes)) {
        mkdir($carpetaImagenes);
      }

      $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

      move_uploaded_file($image['tmp_name'], $carpetaImagenes . '/' . $nombreImagen);

      $query = "INSERT INTO posts (title, content, image, published_at) VALUES ('$title', '$content', '$nombreImagen', '$published_at')";

      $resultado = mysqli_query($db, $query);

      if($resultado) {
        header('Location: /admin1?resultado=!');
      }
    }
  }

  includeTemplate('headerAdmin');
?>

<main class="admin-create-post">
  <h1 class="admin-create-title">Crear Post</h1>

  <?php if(!empty($errores)): ?>
    <ul class="admin-create-errors">
      <?php foreach($errores as $error): ?>
        <li><?php echo $error; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form class="admin-create-form" method="POST" enctype="multipart/form-data" novalidate>
    <div class="admin-create-group">
      <label for="title" class="admin-create-label">Título:</label>
      <input type="text" id="title" name="title" class="admin-create-input" value="<?php echo htmlspecialchars($title); ?>">
    </div>

    <div class="admin-create-group">
      <label for="content" class="admin-create-label">Contenido:</label>
      <textarea id="content" name="content" class="admin-create-textarea"><?php echo htmlspecialchars($content); ?></textarea>
    </div>

    <div class="admin-create-group">
      <label for="image" class="admin-create-label">Imagen:</label>
      <input type="file" id="image" name="image" class="admin-create-file" accept="image/jpeg, image/png">
    </div>

    <input type="submit" value="Crear Post" class="admin-create-submit">
  </form>
</main>
