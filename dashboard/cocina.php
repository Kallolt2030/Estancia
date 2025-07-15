<?php
session_start();
include '../includes/headerDash.php';
include '../control/bd.php'; // Asegúrate de que se incluye correctamente

// Asegúrate de que el usuario está logueado
if (!isset($_SESSION['nip']) || $_SESSION['rol'] !== 'familiar') {
    header('Location: ../index.php');
    exit;
}

$nip = $_SESSION['nip'];
$nombre = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consulta de Comidas</title>
  <link rel="stylesheet" href="../assets/css/cocina.css">
</head>
<body>
  <div class="panel-card">
    <h1 class="card-title">Consulta de Menú por Fecha</h1>
    <p class="card-text">Hola <strong><?= htmlspecialchars($nombre) ?></strong>, elige una fecha para consultar tu menú.</p>

    <!-- Formulario para elegir fecha -->
    <form method="POST">
      <label for="fecha">Selecciona una fecha:</label>
      <input type="date" name="fecha" required>
      <button class="card-button" type="submit" name="consultar">Consultar Menú</button>
    </form>

    <?php
    if (isset($_POST["consultar"]) && !empty($_POST["fecha"])) {
      $fecha = $_POST["fecha"];

      // Verificamos si el usuario comió ese día
      $stmt = $pdo->prepare("SELECT * FROM consumo_comidas WHERE nip = ? AND fecha = ?");
      $stmt->execute([$nip, $fecha]);
      $resConsumo = $stmt->fetchAll();

      if (count($resConsumo) > 0) {
        // Obtener menú del día
        $stmt2 = $pdo->prepare("SELECT desayuno, comida, cena FROM cocina WHERE fecha = ?");
        $stmt2->execute([$fecha]);
        $resMenu = $stmt2->fetch();

        if ($resMenu) {
          echo "<h3 class='card-text'>Menú del día: " . htmlspecialchars($fecha) . "</h3>";
          echo "<table class='card-text' style='margin: auto; text-align: left;'>
                  <tr><th>Desayuno</th><th>Comida</th><th>Cena</th></tr>
                  <tr>
                    <td>{$resMenu['desayuno']}</td>
                    <td>{$resMenu['comida']}</td>
                    <td>{$resMenu['cena']}</td>
                  </tr>
                </table>";
        } else {
          echo "<p class='card-text'>No hay menú registrado para esa fecha.</p>";
        }
      } else {
        echo "<p class='card-text'>No hay registro de consumo para ese día.</p>";
      }
    }
    ?>

    <a class="card-button" href="../dashboard/index.php">Volver</a>
  </div>
</body>
</html>
