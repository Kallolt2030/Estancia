<?php
session_start();
include '../includes/headerDash.php';
include '../control/bd.php';

if (!isset($_SESSION['nip']) || $_SESSION['rol'] !== 'familiar') {
    header('Location: ../index.php');
    exit;
}

$nip = $_SESSION['nip'];
$nombre = $_SESSION['nombre'];

// Obtener pacientes relacionados con el familiar
$stmt = $pdo->prepare("SELECT id_paciente, nombre FROM pacientes WHERE nip = ?");
$stmt->execute([$nip]);
$pacientes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú del Residente</title>
    <link rel="stylesheet" href="../assets/css/cocina.css">
</head>
<body>
<div class="contenedor">

    <!-- Panel lateral izquierdo -->
    <aside class="menu-lateral">
        <button onclick="alert('Función no disponible aún')"> Menú Semana Anterior</button>
        <button onclick="alert('Función no disponible aún')">Menú Día Anterior</button>
        <button onclick="alert('Función no disponible aún')">Ver por tipo de comida</button>
    </aside>

    <!-- Panel central -->
    <section class="contenido-central">
        <div class="menu-dia">
            <h2>Consulta de Menú por Fecha</h2>
            <p>Hola <strong><?= htmlspecialchars($nombre) ?></strong>, elige una fecha y un paciente para consultar el menú.</p>

            <!-- Formulario -->
            <form method="POST">
                <label for="paciente_id">Paciente:</label>
                <select name="paciente_id" required>
                    <option value="">Seleccione un paciente</option>
                    <?php foreach ($pacientes as $p): ?>
                        <option value="<?= $p['id_paciente'] ?>">
                            <?= htmlspecialchars($p['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" required>
                <button type="submit" name="consultar">Consultar Menú</button>
            </form>

            <?php
            if (isset($_POST["consultar"]) && !empty($_POST["fecha"]) && !empty($_POST["paciente_id"])) {
                $fecha = $_POST["fecha"];
                $paciente_id = $_POST["paciente_id"];

                // Obtener consumo del paciente para la fecha
                $stmt = $pdo->prepare("SELECT tipo_comida, hora FROM consumo_comidas WHERE id_paciente = ? AND fecha = ?");
                $stmt->execute([$paciente_id, $fecha]);
                $consumos = $stmt->fetchAll();

                if ($consumos) {
                    // Obtener el menú del día
                    $stmt2 = $pdo->prepare("SELECT desayuno, comida, cena FROM cocina WHERE fecha = ?");
                    $stmt2->execute([$fecha]);
                    $menu = $stmt2->fetch();

                    echo "<h3>Menú del día: " . htmlspecialchars($fecha) . "</h3>";
                    echo "<table>
                            <tr><th>Tipo</th><th>Hora</th><th>Comida Registrada</th></tr>";

                    foreach ($consumos as $c) {
                        $tipo = $c['tipo_comida'];
                        $hora = $c['hora'];
                        $detalle = isset($menu[$tipo]) ? htmlspecialchars($menu[$tipo]) : 'No registrado';
                        echo "<tr>
                                <td>" . ucfirst($tipo) . "</td>
                                <td>$hora</td>
                                <td>$detalle</td>
                              </tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>No hay registro de consumo para ese día.</p>";
                }
            }
            ?>

            <a class="card-button" href="../dashboard/index.php">← Volver al panel</a>
        </div>
    </section>

    <!-- Panel derecho con horarios -->
    <aside class="horarios-comida">
        <h3> Horarios de comida</h3>
        <ul>
            <li><strong>Desayuno:</strong> 08:00 - 09:00</li>
            <li><strong>Colación:</strong> 11:00 - 11:30</li>
            <li><strong>Comida:</strong> 13:00 - 14:00</li>
            <li><strong>Cena:</strong> 18:00 - 19:00</li>
        </ul>
    </aside>

</div>
</body>
</html>
