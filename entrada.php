<?php
  require 'includes/funciones.php';

  $db = conectarDB();

  $id = $_GET['id'];
  $id = filter_var($id, FILTER_VALIDATE_INT);

  if(!$id) {
    header('Location: /');
    exit;
  }

  $query = "SELECT * FROM posts WHERE id = $id";
  $resultado = mysqli_query($db, $query);

  if(mysqli_num_rows($resultado) === 0) {
    header('Location: /');
    exit;
  }

  $post = mysqli_fetch_assoc($resultado);

  includeTemplate('header');
?>

<main>
  <div class="post-section container section">
    <h1 class="text-left highlighted-black-text"><?php echo htmlspecialchars($post['title']); ?></h1>

    <div class="post-details">
      <p><?php echo date('d M, Y', strtotime($post['published_at'])); ?></p>
    </div>

    <img src="/imagenes/<?php echo htmlspecialchars($post['image']); ?>" alt="Imágen del post">

    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
  </div>

  <div class="post-more container section">
    <h2 class="text-left highlighted-black-text">Te Puede Interesar</h2>
  </div>
</main>

<?php
  includeTemplate('footer');
?>