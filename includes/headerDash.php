<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Usuario | Estancia de Vida</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <section class="hero-video">
    <header class="header">
      <div class="barra">
        <!-- Contenedor de logo y menú -->
        <div class="logo-container">
          <!-- Botón para abrir el menú en dispositivos móviles -->
          <button class="menu-toggle" aria-label="Abrir Menú">
            <span class="linea"></span>
            <span class="linea"></span>
            <span class="linea"></span>
          </button>

          <!-- Logo de la página -->
          <a class="logo" href="index.php">
            <img src="../assets/iconos/logo.svg" alt="Logo Estancia de Vida" height="50">
            <h1>Panel de Usuario <span>Estancia de Vida</span></h1>
          </a>
        </div>

        <!-- Sección de acceso para cerrar sesión -->
        <div class="nav-acceso">
          <a href="../control/auth.php?logout=true" class="acceso-link">
            <img src="../assets/iconos/user-circle-svgrepo-com (1).svg" alt="Cerrar sesión" class="icono-acceso">
            <span>Salir</span>
          </a>
        </div>

        <!-- Menú desplegable para dispositivos móviles -->
        <div class="menu-overlay" id="menu">
          <button class="cerrar-menu" aria-label="Cerrar Menú">×</button>
          <nav class="menu-navegacion">
            <a href="../dashboard/index.php">Inicio</a>
            <a href="../dashboard/cocina.php">Cocina</a>
            <a href="../dashboard/cuidadores.php">Cuidadores</a>
            <a href="../dashboard/expediente.php">Expedientes</a>
            <a href="../dashboard/notificaciones.php">Notificaciones</a>
          </nav>
        </div>
      </div>
    </header>
  </section>

  <script src="../assets/js/header.js"></script>
</body>
</html>
