<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administrador | Estancia de Vida</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>      
  <section class="hero-video">
    <header class="header">
      <div class="barra">
        <div class="logo-container">
          <button class="menu-toggle" aria-label="Abrir Menú">
            <span class="linea"></span>
            <span class="linea"></span>
            <span class="linea"></span>
          </button>

          <a class="logo" href="../admin/index.php">
            <img src="../assets/iconos/logo.svg" alt="Logo EDVDNSDG" height="50">
            <h1>Administrador <span>Estancia de Vida</span></h1>
          </a>
        </div>

        <!-- Sección de acceso para cerrar sesión -->
        <div class="nav-salida">
          <a href="../control/auth.php?logout=true" class="salida-link">
            <img src="../assets/iconos/exit-exit-svgrepo-com.svg" alt="Cerrar sesión" class="icono-salida">
          </a>
        </div>

        


        <div class="menu-overlay" id="menu">
          <button class="cerrar-menu" aria-label="Cerrar Menú">×</button>
          <nav class="menu-navegacion">
            <a href="../admin/index.php">Inicio</a>
            <a href="../admin/registro.php">Registro</a>
            <a href="../admin/medicos.php">Médicos</a>
            <a href="../admin/expedientes.php">Expedientes</a>
            <a href="../admin/editar.php">Editar usuarios o pacientes</a>
          </nav>
        </div>
      </div>
    </header>
  </section>

  <script src="../assets/js/header.js"></script>
</body>
</html>


  