<?php
  require 'includes/funciones.php';
  $db = conectarDB();
  includeTemplate('header', $hero = true);

  $query = "SELECT * FROM posts ORDER BY published_at DESC LIMIT 4";
  $resultado = mysqli_query($db, $query);
  $posts = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
?>

<main>
  
  <!-- QUIÉNES SOMOS -->
  <section class="who-we-are-section section container">
    <div class="who-we-are-section--text">
      <p class="text-left large-text">
        <span>Somos una estancia que ofrece</span>
        <span>servicio de centro de día y residencias</span>
        <span>para el adulto mayor en el estado</span>
        <span>de Aguacalientes</span>
      </p>
    </div>
    <img src="/build/img/estancia-frontage.jpg" alt="">
  </section>

  <!-- SERVICIOS -->
  <section class="section container">
    <h2 class="margin-title text-left"><a href="servicios.php" class="highlighted-black-text">Nuestros Servicios</a></h2>
    <div class="services-section">
      <div class="services-list">
        <a href="servicios.php">
          <div class="services-item">
            <img src="/build/img/enfermeria.jpg" alt="">
            <div class="services-item-text">
              <h3 class="text-left highlighted-white-text">Enfermería</h3>
              <p>Cuidado profesional las 24 horas, centrado en la salud, seguridad y bienestar del adulto mayor, con atención personalizada y seguimiento continuo.</p>
            </div>
          </div>
        </a>
        <a href="servicios.php">
          <div class="services-item">
            <img src="/build/img/terapia-cognitiva.jpg" alt="">
            <div class="services-item-text">
              <h3 class="text-left highlighted-white-text">Terapia Cognitiva</h3>
              <p>Estimulación de las funciones cognitivas como la memoria, la atención y el lenguaje, promoviendo el envejecimiento activo y la calidad de vida del adulto mayor.</p>
            </div>
          </div>
        </a>
        <a href="servicios.php">
          <div class="services-item">
            <img src="/build/img/acondicionamiento-fisico.jpg" alt="">
            <div class="services-item-text">
              <h3 class="text-left highlighted-white-text">Acondicionamiento Físico</h3>
              <p>Ejercicio físico adaptado para mantener la fuerza, el equilibrio y la movilidad del adulto mayor, promoviendo una vida activa y saludable.</p>
            </div>
          </div>
        </a>
      </div>
    </div>

  </section>

  <!-- NOTICIAS -->
  <section class="container section">
    <h2 class="margin-title text-left"><a href="noticias.php" class="highlighted-black-text">Noticias</a></h2>
    
    <div class="news-section">
      <?php if (!empty($posts)): ?>
        <!-- POST DESTACADA -->
        <?php $destacado = $posts[0]; ?>
        <a href="entrada.php?id=<?php echo $destacado['id']; ?>" class="black-text">
          <div class="news-item--featured">
            <img src="/imagenes/<?php echo htmlspecialchars($destacado['image']); ?>" alt="Imágen Destacada">
            <div class="news-item-text">
              <div class="news-item__title">
                <h3 class="text-left highlighted-black-text"><?php echo htmlspecialchars($destacado['title']); ?></h3>
              </div>
              <div class="news-item__excerpt">
                <p><?php echo substr(htmlspecialchars($destacado['content']), 0, 120) . '...'; ?></p>
              </div>
            </div>
          </div>
        </a>
      
        <!-- POSTS SECUNDARIOS -->
        <div class="news-item--secondary">
          <?php foreach (array_slice($posts, 1) as $post): ?>
            <a href="entrada.php?id=<?php echo $post['id']; ?>">
              <div class="news-item--small">
                <img src="/imagenes/<?php echo htmlspecialchars($post['image']); ?>" alt="">
                <div class="news-item-text">
                  <div class="news-item__title">
                    <h3 class="text-left no-margin highlighted-black-text"><?php echo htmlspecialchars($post['title']); ?></h3>
                  </div>
                  <div class="news-item__excerpt">
                    <p><?php echo substr(htmlspecialchars($post['content']), 0, 80) . '...'; ?></p>
                  </div>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>No hay noticias disponibles por el momento.</p>
      <?php endif; ?>
    </div>
  </section>

  <div class="container section">
    <div class="ubication-section">
      <div class="ubication-section-desc">
        <h2 class="title-margin highlighted-black-text">Nuestra Ubicación</h2>
        <p>Estado: Aguascalientes</p>
        <p>Colonia: El Llanito</p>
        <p>Código Postal: 20240</p>
        <p>Calle: La Soledad #207</p>
      </div>
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3702.4909327868395!2d-102.2903035253468!3d21.877163258100694!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8429ee7734b2b4f5%3A0x525d90880241f87e!2sESTANCIA%20DE%20VIDA%20NUESTRA%20SE%C3%91ORA%20DE%20GUADALUPE%20A.C.!5e0!3m2!1ses!2smx!4v1753851636215!5m2!1ses!2smx" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</main>

<?php
  includeTemplate('footer');
?>