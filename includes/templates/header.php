<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  if(!isset($db)) {
  require_once 'includes/funciones.php';
  $db = conectarDB();
}

  $queryUltima = "SELECT * FROM posts ORDER BY published_at DESC LIMIT 1";
  $resultadoUltima = mysqli_query($db, $queryUltima);
  $ultima = mysqli_fetch_assoc($resultadoUltima);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estancia</title>
  <link rel="icon" href="/build/img/logo.svg">
  <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>

  <!-- Encabezado del Sitio Web -->
  <header class="header <?php echo ($hero || $hero_image) ? 'header__hero' : '' ?>">
    <?php if($hero): ?>
      <video muted loop autoplay class="header__video">
        <source src="../../src/video/home-video.mp4" type="video/mp4">
      </video>
      
      <div class="overlay">
        <div class="overlay-text">
          <h1 class="highlighted-white-text">Centro de Día y Residencia para el <span>Adulto Mayor</span></h1>
        </div>

        <!-- WIDGETS -->
        <div class="overlay-widgets">
          <!-- Widget Activar/Desactivar Música -->
          <div class="widget-music">
            <button class="music-button" id="musicButton" aria-label="Música">
              <img src="/build/img/volume.svg" alt="Icono de Volumen">

              <!-- Audio -->
              <audio  id="backgroundMusic" src="/src/audio/song.mp3" loop></audio>
            </button>
          </div>
          <!-- Widget última Noticia -->
           <a href="/entrada.php?id=<?php echo $ultima['id']; ?>">
          <div class="widget-last-new">
            <div class="widget-last-new-alert">
              <p><span class="red-point"></span>Última Entrada</p>
            </div>
            <?php if($ultima): ?>
                <img src="/imagenes/<?php echo htmlspecialchars($ultima['image']); ?>" alt="">
                <div class="widget-last-new-text">
                  <h3 class="text-left no-margin highlighted-white-text"><?php echo htmlspecialchars($ultima['title']); ?></h3>
                  <p class="text-left"><?php echo substr(htmlspecialchars($ultima['content']), 0, 80) . '...'; ?></p>
                </div>
              <?php else: ?>
                <p>No hay entradas disponibles</p>
              <?php endif; ?>
          </div>
          </a>
        </div>
      </div>
      <?php elseif($hero_image): ?>
        <img class="header__image" src="/build/img/<?php echo $hero_image_name ?? ''; ?>" alt="">
        <div class="overlay">
          <!-- <div class="overlay-text">
            <h1 class="highlighted-white-text">Más de 20 años trabajando en beneficio del<span>Adulto Mayor</span></h1>
          </div> -->
        </div>
      <?php endif; ?>
      
      <!-- Contenido del header: Logotipo, barra de navegación y botón de plataforma -->
      <div class="header-content">

        <!-- Logo -->
        <a class="header-logo" href="/">
          <img src="/build/img/logo.svg" alt="Logo">
        </a>
        
        <!-- Barra de navegación -->
        <nav class="navigation">
          <a href="estancia.php">Estancia</a>
          <a href="noticias.php">Noticias</a>
          <a href="servicios.php">Servicios</a>
          <a href="actividades.php">Actividades</a>
          <a href="nosotros.php">Nosotros</a>
          <a href="contacto.php">Contacto</a>
        </nav>

        <!-- Botón para ingresar a plataforma -->
        <a class="platform-button" href="login.php">Plataforma</a>

        <!-- Menú para Teléfono y Tableta -->
        <div class="menu" id="menu" aria-label="Abrir Menú">
          <img src="/build/img/bars.svg" alt="Icono de Menú Responsivo">
        </div>

        <!-- Overlay del Menú para Teléfono y Tableta -->
        <div class="menu-overlay" id="menu-overlay">
          <div class="menu-overlay-header">
            <a href="/" class="menu-overlay-logo">
              <img src="/build/img/logo.svg" alt="Logo">
              <h2>Estancia de Vida <span>de Nuestra Señora de Guadalupe</span></h2>
            </a>
            <!-- Botón para Cerrar el Overlay -->
            <button class="close-button" id="closeBtn" aria-label="Cerrar Menú">
              <img src="/build/img/close.svg" alt="Cerrar Menú">
            </button>
          </div>

          <nav class="menu-overlay-navigation">
            <ul>
              <li><a href="estancia.php" class="large-text">Estancia</a></li>
              <li><a href="noticias.php" class="large-text">Noticias</a></li>
              <li><a href="servicios.php" class="large-text">Servicios</a></li>
              <li><a href="actividades.php" class="large-text">Actividades</a></li>
              <li><a href="nosotros.php" class="large-text">Nosotros</a></li>
              <li><a href="contacto.php" class="large-text">Contacto</a></li>
            </ul>
          </nav>
        </div>
      </div>
  </header>

  <!-- JavaScript -->
  <script src="/build/js/bundle.min.js"></script>
