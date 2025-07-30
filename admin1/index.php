<!-- PANEL DE ADMINISTRADOR -->
 <?php
  require '../includes/funciones.php';
  $auth = estaAutenticado(); // Verifica si el usuario ha iniciado sesión

  if(!$auth) { // Si el usuario no ha iniciado sesión, lo redirige a /
    header('Location: /');
  }
  
  $db = conectarDB(); // Devuelve la conexión a la base de datos MySQL ($db)
  
  // Consulta para traer todas las entradas de la tabla entradas
  $query = "SELECT * FROM posts";

  // Ejecuta la consulta anterior con mysqli_query
  $resultadoConsulta = mysqli_query($db, $query);

  // Captura el parámetro resultado de la URL. Este parámetro se usa para mostrar mensajes tipo "Entrada eliminada correctamente"
  $resultado = $_GET['resultado'] ?? null;

  // Detecta si el formulario fue enviado por POST
  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Toma el id de la entrada a eliminar desde el formulario
    $id = $_POST['id'];
    // Lo valida como entero para asegurarse de que sea seguro (evita inyecciones SQL)
    $id = filter_var($id, FILTER_VALIDATE_INT);

    // PROCESO PARA ELIMINAR UNA ENTRADA
    if($id) {
      // BORRAR IMAGEN
      // Si el ID es válido, se busca la entrada en la base de datos y se obtiene el nombre del archivo de imagen
      $query = "SELECT image FROM posts WHERE id = {$id}";
      $resultado = mysqli_query($db, $query);
      $post = mysqli_fetch_assoc($resultado);
      // Borra el archivo físico del servidor
      unlink('../imagenes/' . $post['image']);

      // ELIMINAR LA ENTRADA
      // Eliminar la entrada de la base de datos
      $query = "DELETE FROM posts WHERE id = {$id}";
      // Ejecuta el query anterior con mysqli_query
      $resultado = mysqli_query($db, $query);

      // Si la eliminación fue exitosa, refirige al panel de administrador con un parámetro en la URL (?resultado=3) para mostrar el mensaje "Entrada Eliminada Correctamente"
      if($resultado) {
        header('location: /admin1?resultado=3');
      }
    }
  }

  includeTemplate('headerAdmin');
?>

<main class="contenedor seccion">
  <h1>Administrador</h1>

  <?php if (intval($resultado) === 3): ?>
    <p class="alerta exito">Entrada eliminada correctamente</p>
  <?php endif; ?>

  <a href="/admin1/propiedades/crear.php" class="btn btn-primary">+ Agregar entrada</a>

  <table class="tabla">
    <thead>
      <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Imagen</th>
        <th>Acciones</th>
      </tr>
    </thead>

    <tbody>
      <?php while ($post = mysqli_fetch_assoc($resultadoConsulta)) : ?>
        <tr>
          <td><?php echo $post['id']; ?></td>
          <td><?php echo htmlspecialchars($post['title']); ?></td>
          <td>
            <img src="/imagenes/<?php echo htmlspecialchars($post['image']); ?>" class="imagen-tabla" alt="Imagen post">
          </td>
          <td>
            <!-- Formulario para eliminar -->
            <form method="POST" class="w-100">
              <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
              <input type="submit" class="boton-rojo-block" value="Eliminar">
            </form>

            <!-- Botón para actualizar (puedes hacer la ruta a tu página de actualización) -->
            <a href="/admin1/propiedades/actualizar.php?id=<?php echo $post['id']; ?>" class="boton-amarillo-block">Actualizar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>

<?php
  // Finaliza la conexión a la base de datos
  mysqli_close($db);
?>
