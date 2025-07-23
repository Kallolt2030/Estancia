<?php
include '../control/auth.php';
include '../includes/headerTrab.php';
include '../control/bd.php';

// Inicializar variables para mensajes
$success_msg = '';
$error_msg = '';

// Obtener listas para desplegables
try {
    $pacientes = $pdo->query("SELECT id_paciente, nombre FROM pacientes")->fetchAll(PDO::FETCH_ASSOC);
    $medicos = $pdo->query("SELECT id, nombre FROM medicos")->fetchAll(PDO::FETCH_ASSOC);
    $cuidadores = $pdo->query("SELECT nip, nombre FROM usuarios WHERE rol = 'cuidador'")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_msg = "Error al cargar datos: " . $e->getMessage();
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo_reporte'] ?? '';
    
    try {
        switch ($tipo) {
            case 'cuidador':
                if (empty($_POST['id_paciente']) || empty($_POST['id_cuidador']) || empty($_POST['fecha'])) {
                    $error_msg = "Faltan datos obligatorios para el reporte del cuidador";
                    break;
                }
                
                $id_paciente = $_POST['id_paciente'];
                $id_cuidador = $_POST['id_cuidador'];
                $fecha = $_POST['fecha'];
                $observaciones = $_POST['observaciones'] ?? '';
                $comio = $_POST['comio'] ?? 0;

                $stmt = $pdo->prepare("INSERT INTO reportes_cuidadores (id_paciente, id_cuidador, fecha, observaciones, comio)
                    VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$id_paciente, $id_cuidador, $fecha, $observaciones, $comio]);

                $success_msg = "Reporte del cuidador guardado correctamente.";
                break;

            case 'cocina':
                if (empty($_POST['fecha'])) {
                    $error_msg = "La fecha es obligatoria para el reporte de cocina";
                    break;
                }
                
                $fecha = $_POST['fecha'];
                $ingredientes = $_POST['ingredientes'] ?? '';
                $notas = $_POST['notas'] ?? '';

                $stmt = $pdo->prepare("INSERT INTO reportes_cocina (fecha, ingredientes_utilizados, notas)
                    VALUES (?, ?, ?)");
                $stmt->execute([$fecha, $ingredientes, $notas]);

                $success_msg = "Reporte de cocina guardado correctamente.";
                break;

            case 'medico':
                if (empty($_POST['id_paciente']) || empty($_POST['id_medico']) || empty($_POST['fecha'])) {
                    $error_msg = "Faltan datos obligatorios para el reporte médico";
                    break;
                }
                
                $id_paciente = $_POST['id_paciente'];
                $id_medico = $_POST['id_medico'];
                $fecha = $_POST['fecha'];
                $sueno = $_POST['sueno'] ?? '';
                $dieta = $_POST['dieta'] ?? '';
                $esfera = $_POST['esfera'] ?? '';
                $memoria = $_POST['memoria'] ?? '';
                $micciones = $_POST['micciones'] ?? '';
                $evacuaciones = $_POST['evacuaciones'] ?? '';
                $eventualidades = $_POST['eventualidades'] ?? '';
                $signos = $_POST['signos'] ?? '';
                $analisis = $_POST['analisis'] ?? '';
                $plan = $_POST['plan'] ?? '';

                $stmt = $pdo->prepare("INSERT INTO reportes_medicos (id_paciente, id_medico, fecha, sueno, dieta, esfera_emocional, memoria, micciones, evacuaciones, eventualidades, signos_vitales, analisis, plan)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$id_paciente, $id_medico, $fecha, $sueno, $dieta, $esfera, $memoria, $micciones, $evacuaciones, $eventualidades, $signos, $analisis, $plan]);

                $success_msg = "Reporte médico guardado con éxito.";
                break;

            default:
                $error_msg = "Tipo de reporte no válido.";
        }
    } catch (PDOException $e) {
        $error_msg = "Error al guardar el reporte: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario de Reportes</title>
  <link rel="stylesheet" href="../assets/css/formTrab.css">
  <style>
    .error { color: red; }
    .success { color: green; }
    .form-section { display: none; margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    .required:after { content: " *"; color: red; }
    select, input, textarea { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; }
    button { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
    button:hover { background-color: #45a049; }
    label { font-weight: bold; margin-top: 10px; display: block; }
  </style>
</head>
<body>

<h2>Registro de Reportes por Tipo de Trabajador</h2>

<?php if (!empty($error_msg)): ?>
    <div class="error"><?php echo htmlspecialchars($error_msg); ?></div>
<?php endif; ?>

<?php if (!empty($success_msg)): ?>
    <div class="success"><?php echo htmlspecialchars($success_msg); ?></div>
<?php endif; ?>

<label for="area">Seleccione su área:</label>
<select id="area" onchange="mostrarFormulario()">
  <option value="">-- Selecciona --</option>
  <option value="medico">Médico</option>
  <option value="cuidador">Cuidador</option>
  <option value="cocina">Cocina</option>
</select>

<!-- 🔽 FORMULARIO MÉDICO -->
<div id="form-medico" class="form-section">
  <h3>Formulario Médico</h3>
  <form method="POST" onsubmit="return validarFormMedico()">
    <input type="hidden" name="tipo_reporte" value="medico">

    <label class="required">Paciente:</label>
    <select name="id_paciente" required>
      <option value="">-- Seleccione un paciente --</option>
      <?php if (!empty($pacientes)): ?>
        <?php foreach ($pacientes as $p): ?>
          <option value="<?= $p['id_paciente'] ?>" <?= isset($_POST['id_paciente']) && $_POST['id_paciente'] == $p['id_paciente'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($p['nombre']) ?>
          </option>
        <?php endforeach; ?>
      <?php else: ?>
        <option value="">No hay pacientes registrados</option>
      <?php endif; ?>
    </select>

    <label class="required">Médico:</label>
    <select name="id_medico" required>
      <option value="">-- Seleccione un médico --</option>
      <?php if (!empty($medicos)): ?>
        <?php foreach ($medicos as $m): ?>
          <option value="<?= $m['id'] ?>" <?= isset($_POST['id_medico']) && $_POST['id_medico'] == $m['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($m['nombre']) ?>
          </option>
        <?php endforeach; ?>
      <?php else: ?>
        <option value="">No hay médicos registrados</option>
      <?php endif; ?>
    </select>

    <label class="required">Fecha:</label>
    <input type="date" name="fecha" required value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : '' ?>">

    <label>Sueño:</label>
    <textarea name="sueno"><?= isset($_POST['sueno']) ? htmlspecialchars($_POST['sueno']) : '' ?></textarea>

    <label>Dieta:</label>
    <textarea name="dieta"><?= isset($_POST['dieta']) ? htmlspecialchars($_POST['dieta']) : '' ?></textarea>

    <label>Signos Vitales (TA, FC, etc):</label>
    <input name="signos" value="<?= isset($_POST['signos']) ? htmlspecialchars($_POST['signos']) : '' ?>">

    <label>Análisis:</label>
    <textarea name="analisis"><?= isset($_POST['analisis']) ? htmlspecialchars($_POST['analisis']) : '' ?></textarea>

    <label>Plan:</label>
    <textarea name="plan"><?= isset($_POST['plan']) ? htmlspecialchars($_POST['plan']) : '' ?></textarea>

    <button type="submit">Guardar</button>
  </form>
</div>

<!-- 🔽 FORMULARIO CUIDADOR -->
<div id="form-cuidador" class="form-section">
  <h3>Formulario Cuidador</h3>
  <form method="POST" onsubmit="return validarFormCuidador()">
    <input type="hidden" name="tipo_reporte" value="cuidador">

    <label class="required">Paciente:</label>
    <select name="id_paciente" required>
      <option value="">-- Seleccione un paciente --</option>
      <?php if (!empty($pacientes)): ?>
        <?php foreach ($pacientes as $p): ?>
          <option value="<?= $p['id_paciente'] ?>" <?= isset($_POST['id_paciente']) && $_POST['id_paciente'] == $p['id_paciente'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($p['nombre']) ?>
          </option>
        <?php endforeach; ?>
      <?php else: ?>
        <option value="">No hay pacientes registrados</option>
      <?php endif; ?>
    </select>

    <label class="required">Cuidador:</label>
    <select name="id_cuidador" required>
      <option value="">-- Seleccione un cuidador --</option>
      <?php if (!empty($cuidadores)): ?>
        <?php foreach ($cuidadores as $c): ?>
          <option value="<?= $c['nip'] ?>" <?= isset($_POST['id_cuidador']) && $_POST['id_cuidador'] == $c['nip'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['nombre']) ?>
          </option>
        <?php endforeach; ?>
      <?php else: ?>
        <option value="">No hay cuidadores registrados</option>
      <?php endif; ?>
    </select>

    <label class="required">Fecha:</label>
    <input type="date" name="fecha" required value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : '' ?>">

    <label>Observaciones:</label>
    <textarea name="observaciones"><?= isset($_POST['observaciones']) ? htmlspecialchars($_POST['observaciones']) : '' ?></textarea>

    <label>¿Comió?</label>
    <select name="comio">
      <option value="1" <?= isset($_POST['comio']) && $_POST['comio'] == '1' ? 'selected' : '' ?>>Sí</option>
      <option value="0" <?= isset($_POST['comio']) && $_POST['comio'] == '0' ? 'selected' : '' ?>>No</option>
    </select>

    <button type="submit">Guardar</button>
  </form>
</div>

<!-- 🔽 FORMULARIO COCINA -->
<div id="form-cocina" class="form-section">
  <h3>Formulario Cocina</h3>
  <form method="POST" onsubmit="return validarFormCocina()">
    <input type="hidden" name="tipo_reporte" value="cocina">
    
    <label class="required">Fecha:</label>
    <input type="date" name="fecha" required value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : '' ?>">

    <label>Ingredientes utilizados:</label>
    <textarea name="ingredientes"><?= isset($_POST['ingredientes']) ? htmlspecialchars($_POST['ingredientes']) : '' ?></textarea>

    <label>Notas:</label>
    <textarea name="notas"><?= isset($_POST['notas']) ? htmlspecialchars($_POST['notas']) : '' ?></textarea>

    <button type="submit">Guardar</button>
  </form>
</div>

<script>
// Mostrar el formulario correspondiente
function mostrarFormulario() {
  const area = document.getElementById('area').value;
  document.querySelectorAll('.form-section').forEach(f => f.style.display = 'none');

  if (area === 'medico') {
    document.getElementById('form-medico').style.display = 'block';
  } else if (area === 'cuidador') {
    document.getElementById('form-cuidador').style.display = 'block';
  } else if (area === 'cocina') {
    document.getElementById('form-cocina').style.display = 'block';
  }
}

// Validación del formulario médico
function validarFormMedico() {
  const paciente = document.querySelector('#form-medico select[name="id_paciente"]').value;
  const medico = document.querySelector('#form-medico select[name="id_medico"]').value;
  const fecha = document.querySelector('#form-medico input[name="fecha"]').value;
  
  if (!paciente || !medico || !fecha) {
    alert('Por favor complete todos los campos obligatorios');
    return false;
  }
  return true;
}

// Validación del formulario cuidador
function validarFormCuidador() {
  const paciente = document.querySelector('#form-cuidador select[name="id_paciente"]').value;
  const cuidador = document.querySelector('#form-cuidador select[name="id_cuidador"]').value;
  const fecha = document.querySelector('#form-cuidador input[name="fecha"]').value;
  
  if (!paciente || !cuidador || !fecha) {
    alert('Por favor complete todos los campos obligatorios');
    return false;
  }
  return true;
}

// Validación del formulario cocina
function validarFormCocina() {
  const fecha = document.querySelector('#form-cocina input[name="fecha"]').value;
  
  if (!fecha) {
    alert('La fecha es obligatoria');
    return false;
  }
  return true;
}

// Mostrar el formulario correspondiente si hay un error y se envió un formulario
window.onload = function() {
  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    document.getElementById('area').value = '<?= htmlspecialchars($_POST["tipo_reporte"] ?? "") ?>';
    mostrarFormulario();
  <?php endif; ?>
};
</script>

</body>
</html>