<?php
include '../control/control_user.php';
include '../includes/headerAD.php';
include '../control/bd.php';

$mensaje_exito = '';
$mensaje_error = '';
$row = []; // Inicializamos $row como array vacío

// Cargar datos del ingrediente si estamos en modo edición
if (isset($_GET['id']) && !isset($_POST['nombre'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM ingredientes WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensaje_error = "Error al cargar el ingrediente: " . $e->getMessage();
    }
}

// Insertar ingrediente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['id'])) {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad_disponible'];
    $unidad = $_POST['unidad'];

    try {
        $stmt = $pdo->prepare("INSERT INTO ingredientes (nombre, cantidad_disponible, unidad) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $cantidad, $unidad]);
        $mensaje_exito = "Ingrediente registrado correctamente.";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $mensaje_error = "Ese ingrediente ya existe.";
        } else {
            $mensaje_error = "Error: " . $e->getMessage();
        }
    }
}

// Editar ingrediente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad_disponible'];
    $unidad = $_POST['unidad'];

    try {
        $stmt = $pdo->prepare("UPDATE ingredientes SET nombre = ?, cantidad_disponible = ?, unidad = ? WHERE id = ?");
        $stmt->execute([$nombre, $cantidad, $unidad, $id]);
        $mensaje_exito = "Ingrediente actualizado correctamente.";
    } catch (PDOException $e) {
        $mensaje_error = "Error: " . $e->getMessage();
    }
}

// Eliminar ingrediente
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM ingredientes WHERE id = ?");
        $stmt->execute([$id]);
        $mensaje_exito = "Ingrediente eliminado correctamente.";
    } catch (PDOException $e) {
        $mensaje_error = "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Ingredientes</title>
  <link rel="stylesheet" href="../assets/css/indexAD.css">
</head>
<body>

<div class="form-section">
  <h3><?php echo isset($_GET['id']) ? "Editar Ingrediente" : "Registrar Ingrediente"; ?></h3>

  <?php if ($mensaje_exito): ?>
    <p class="mensaje exito"><?php echo $mensaje_exito; ?></p>
  <?php endif; ?>

  <?php if ($mensaje_error): ?>
    <p class="mensaje error"><?php echo $mensaje_error; ?></p>
  <?php endif; ?>
<div class="ingredientes-module">
  <form method="POST">
    <label for="nombre">Nombre del ingrediente:</label>
    <input type="text" name="nombre" value="<?php echo isset($row['nombre']) ? $row['nombre'] : ''; ?>" required>

    <label for="cantidad_disponible">Cantidad disponible:</label>
    <input type="number" step="0.01" name="cantidad_disponible" value="<?php echo isset($row['cantidad_disponible']) ? $row['cantidad_disponible'] : ''; ?>" required>

    <label for="unidad">Unidad:</label>
    <select name="unidad" id="unidad" required>
        <option value="gramos" <?php echo (isset($row['unidad']) && $row['unidad'] == 'gramos') ? 'selected' : ''; ?>>Gramos</option>
        <option value="kilogramos" <?php echo (isset($row['unidad']) && $row['unidad'] == 'kilogramos') ? 'selected' : ''; ?>>Kilogramos</option>
        <option value="miligramos" <?php echo (isset($row['unidad']) && $row['unidad'] == 'miligramos') ? 'selected' : ''; ?>>Miligramos</option>
        <option value="litros" <?php echo (isset($row['unidad']) && $row['unidad'] == 'litros') ? 'selected' : ''; ?>>Litros</option>
        <option value="mililitros" <?php echo (isset($row['unidad']) && $row['unidad'] == 'mililitros') ? 'selected' : ''; ?>>Mililitros</option>
        <option value="centilitros" <?php echo (isset($row['unidad']) && $row['unidad'] == 'centilitros') ? 'selected' : ''; ?>>Centilitros</option>
        <option value="unidad" <?php echo (isset($row['unidad']) && $row['unidad'] == 'unidad') ? 'selected' : ''; ?>>Unidad</option>
        <option value="piezas" <?php echo (isset($row['unidad']) && $row['unidad'] == 'piezas') ? 'selected' : ''; ?>>Piezas</option>
        <option value="docena" <?php echo (isset($row['unidad']) && $row['unidad'] == 'docena') ? 'selected' : ''; ?>>Docena</option>
        <option value="paquete" <?php echo (isset($row['unidad']) && $row['unidad'] == 'paquete') ? 'selected' : ''; ?>>Paquete</option>
        <option value="botella" <?php echo (isset($row['unidad']) && $row['unidad'] == 'botella') ? 'selected' : ''; ?>>Botella</option>
        <option value="lata" <?php echo (isset($row['unidad']) && $row['unidad'] == 'lata') ? 'selected' : ''; ?>>Lata</option>
        <option value="galones" <?php echo (isset($row['unidad']) && $row['unidad'] == 'galones') ? 'selected' : ''; ?>>Galones</option>
        <option value="libras" <?php echo (isset($row['unidad']) && $row['unidad'] == 'libras') ? 'selected' : ''; ?>>Libras</option>
        <option value="onzas" <?php echo (isset($row['unidad']) && $row['unidad'] == 'onzas') ? 'selected' : ''; ?>>Onzas</option>
    </select>

    <button type="submit" class="btn-ingrediente"><?php echo isset($_GET['id']) ? "Actualizar" : "Guardar"; ?></button>
    </div>
</form>
</div>

<hr>

<h3>Ingredientes Registrados</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Cantidad Disponible</th>
        <th>Unidad</th>
        <th>Acciones</th>
    </tr>

    <?php
    $stmt = $pdo->query("SELECT * FROM ingredientes ORDER BY nombre ASC");
    while ($row = $stmt->fetch()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['cantidad_disponible']}</td>
                <td>{$row['unidad']}</td>
                <td>
                    <a href='?id={$row['id']}'>Editar</a> |
                    <a href='?delete={$row['id']}' onclick='return confirm(\"¿Estás seguro de eliminar este ingrediente?\")'>Eliminar</a>
                </td>
              </tr>";
    }
    ?>
</table>

</body>
</html>
