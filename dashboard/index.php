<?php include '../includes/auth.php'; ?>
<?php include '../includes/headerAD.php'; ?>
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
  <!-- Font Awesome (para los íconos) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="../assets/css/indexAD.css">
</head>
<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800 font-[Inter]">

  <main class="flex-grow flex flex-col items-center p-6">
    <h1 class="text-3xl font-bold mb-6">Panel del Familiar</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-7xl">

      <!-- Notificaciones -->
      <div class="panel-card">
        <div>
          <i class="fa-solid fa-bell text-blue-500 text-3xl mb-3"></i>
          <h2 class="card-title">Novedades</h2>
          <p class="card-text">En este apartado podras consultar las novedades que ocurren dentro de la estancia.</p>
        </div>
        <a href="notificaciones.php" class="card-button">Ir</a>
      </div>

      <!-- Expediente -->
      <div class="panel-card">
        <div>
          <i class="fa-solid fa-folder-open text-green-500 text-3xl mb-3"></i>
          <h2 class="card-title">Expediente</h2>
          <p class="card-text">Revisa el expediente clínico de tu familiar.</p>
        </div>
        <a href="expediente.php" class="card-button">Ir</a>
      </div>

      <!-- Cuidadores -->
      <div class="panel-card">
        <div>
          <i class="fa-solid fa-user-nurse text-purple-500 text-3xl mb-3"></i>
          <h2 class="card-title">Cuidadores</h2>
          <p class="card-text">Accede a la información del personal asignado.</p>
        </div>
        <a href="cuidadores.php" class="card-button">Ir</a>
      </div>

      <!-- Cocina -->
      <div class="panel-card">
        <div>
          <i class="fa-solid fa-utensils text-orange-500 text-3xl mb-3"></i>
          <h2 class="card-title">Cocina</h2>
          <p class="card-text">Consulta el menú diario o semanal.</p>
        </div>
        <a href="cocina.php" class="card-button">Ir</a>
      </div>

    </div>
  </main>

  <?php include '../includes/footer.php'; ?>
</body>
</html>
<link rel="stylesheet" href="../assets/css/indexAD.css">
<h2>Panel Principal</h2>
<p>Bienvenido al panel del familiar.</p>
<nav>
    <a href="notificaciones.php">Notificaciones</a> |
    <a href="expediente.php">Expediente</a> |
    <a href="cuidadores.php">Cuidadores</a> ||
    <a href="cocina.php">Cocina</a> |
    <a href="logout.php">Cerrar sesión</a>
</nav>
<?php include '../includes/footer.php'; ?>
