<?php
  require 'includes/funciones.php';
  $db = conectarDB();
  includeTemplate('header');

  $queryLast = "SELECT * FROM posts ORDER BY published_at DESC LIMIT 1";
  $resultadoLast = mysqli_query($db, $queryLast);
  $lastPost = mysqli_fetch_assoc($resultadoLast);

  $queryOthers = "SELECT * FROM posts ORDER BY published_at DESC LIMIT 1, 10";
  $resultadoOthers = mysqli_query($db, $queryOthers);
?>

<main>
  <!-- NOTICIA PRINCIPAL -->
  <?php if($lastPost): ?>
    <section class="news-last">
      <img src="/imagenes/<?php echo htmlspecialchars($lastPost['image']); ?>" alt="Imagen de la noticia principal">
      <div class="news-last-content">
        <a href="entrada.php?id=<?php echo $lastPost['id']; ?>">
          <h2 class="large-text margin-title text-left highlighted-white-text"><?php echo htmlspecialchars($lastPost['title']); ?></h2>
          <p><?php echo substr(htmlspecialchars($lastPost['content']), 0, 200) . '...'; ?></p>
        </a>
      </div>
    </section>
  <?php endif; ?>


  <!-- NOTICIAS SECUNDARIAS -->
  <?php while($post = mysqli_fetch_assoc($resultadoOthers)): ?>
    <section class="news-secondary container section">
      <img src="/imagenes/<?php echo htmlspecialchars($post['image']); ?>" alt="Imagen de la noticia">
      <div class="news-secondary-content">
        <a href="entrada.php?id=<?php echo $post['id']; ?>">
          <h2 class="large-text margin-title text-left highlighted-black-text"><?php echo htmlspecialchars($post['title']); ?></h2>
          <p class="mb-1"><?php echo date('d M Y', strtotime($post['published_at'])); ?></p>
          <p><?php echo substr(htmlspecialchars($post['content']), 0, 150) . '...'; ?></p>
        </a>
      </div>
    </section>
  <?php endwhile; ?>
</main>

<?php
  includeTemplate('footer');
  mysqli_close($db);
?>