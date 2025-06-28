<?php include 'headerDash.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Familiar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="../assets/css/indexDash.css">
</head>

<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800 font-[Inter]">

  <div class="background"></div>

  <main class="flex-grow flex flex-col items-center p-6 pt-20 relative z-10">
    <h1 class="text-3xl font-bold mb-8">Panel del Familiar</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-7xl">

      <!-- Novedades -->
      <div class="panel-card">
        <img src="../assets/img/novedades.png" alt="Novedades">
        <img src="../assets/img/icono-novedades.png" alt="Novedades Hover">
        <div class="overlay">
          <a href="notificaciones.php">Ver Novedades</a>
        </div>
      </div>

      <!-- Expediente -->
      <div class="panel-card">
        <img src="../assets/img/expediente.png" alt="Expediente">
        <img src="../assets/img/expediente_hover.png" alt="Expediente Hover">
        <div class="overlay">
          <a href="expediente.php">Ver Expediente</a>
        </div>
      </div>

      <!-- Cuidadores -->
      <div class="panel-card">
        <img src="../assets/img/cuidadores.png" alt="Cuidadores">
        <img src="../assets/img/cuidadores_hover.png" alt="Cuidadores Hover">
        <div class="overlay">
          <a href="cuidadores.php">Ver Cuidadores</a>
        </div>
      </div>

      <!-- Cocina -->
      <div class="panel-card">
        <img src="../assets/img/cocina.png" alt="Cocina">
        <img src="../assets/img/cocina_hover.png" alt="Cocina Hover">
        <div class="overlay">
          <a href="cocina.php">Ver Menú</a>
        </div>
      </div>

    </div>
  </main>
</body>
</html>

<?php include '../includes/footer.php'; ?>