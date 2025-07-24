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
    $enfermeria = $pdo->query("SELECT nip, nombre FROM usuarios WHERE rol = 'enfermeria'")->fetchAll(PDO::FETCH_ASSOC);
    $kinesica = $pdo->query("SELECT nip, nombre FROM usuarios WHERE rol = 'kinesica'")->fetchAll(PDO::FETCH_ASSOC);
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
    
    // Datos básicos
    $id_paciente = $_POST['id_paciente'];
    $id_medico = $_POST['id_medico'];
    $fecha = $_POST['fecha'];
    
    // Campos textuales
    $sueno = $_POST['sueno'] ?? '';
    $dieta = $_POST['dieta'] ?? '';
    $esfera = $_POST['esfera'] ?? '';
    $memoria = $_POST['memoria'] ?? '';
    $micciones = $_POST['micciones'] ?? '';
    $evacuaciones = $_POST['evacuaciones'] ?? '';
    $eventualidades = $_POST['eventualidades'] ?? '';
    $analisis = $_POST['analisis'] ?? '';
    $plan = $_POST['plan'] ?? '';
    
    // Signos vitales estructurados
    $ta_sistolica = !empty($_POST['ta_sistolica']) ? (int)$_POST['ta_sistolica'] : null;
    $ta_diastolica = !empty($_POST['ta_diastolica']) ? (int)$_POST['ta_diastolica'] : null;
    $fc = !empty($_POST['fc']) ? (int)$_POST['fc'] : null;
    $fr = !empty($_POST['fr']) ? (int)$_POST['fr'] : null;
    $sat_o2 = !empty($_POST['sat_o2']) ? (int)$_POST['sat_o2'] : null;
    $temp = !empty($_POST['temp']) ? (float)$_POST['temp'] : null;
    $peso = !empty($_POST['peso']) ? (float)$_POST['peso'] : null;
    $talla = !empty($_POST['talla']) ? (int)$_POST['talla'] : null;
    $glucemia = !empty($_POST['glucemia']) ? (int)$_POST['glucemia'] : null;
    $otros_signos = $_POST['otros_signos'] ?? '';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO reportes_medicos (
            id_paciente, id_medico, fecha, 
            sueno, dieta, esfera_emocional, memoria, 
            micciones, evacuaciones, eventualidades,
            ta_sistolica, ta_diastolica, fc, fr, sat_o2, temp,
            peso, talla, glucemia, otros_signos,
            analisis, plan
        ) VALUES (
            ?, ?, ?, 
            ?, ?, ?, ?, 
            ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?
        )");
        
        $stmt->execute([
            $id_paciente, $id_medico, $fecha,
            $sueno, $dieta, $esfera, $memoria,
            $micciones, $evacuaciones, $eventualidades,
            $ta_sistolica, $ta_diastolica, $fc, $fr, $sat_o2, $temp,
            $peso, $talla, $glucemia, $otros_signos,
            $analisis, $plan
        ]);
        
        $success_msg = "Reporte médico guardado con éxito.";
    } catch (PDOException $e) {
        $error_msg = "Error al guardar el reporte médico: " . $e->getMessage();
    }
    break;

            case 'enfermeria':
                if (empty($_POST['id_paciente']) || empty($_POST['id_enfermero']) || empty($_POST['fecha'])) {
                    $error_msg = "Faltan datos obligatorios para el reporte de enfermería";
                    break;
                }
                
                $id_paciente = $_POST['id_paciente'];
                $id_enfermero = $_POST['id_enfermero'];
                $fecha = $_POST['fecha'];
                $signos_vitales = $_POST['signos_vitales'] ?? '';
                $medicamentos = $_POST['medicamentos'] ?? '';
                $procedimientos = $_POST['procedimientos'] ?? '';
                $observaciones = $_POST['observaciones'] ?? '';

                $stmt = $pdo->prepare("INSERT INTO reportes_enfermeria (id_paciente, id_enfermero, fecha, signos_vitales, medicamentos, procedimientos, observaciones)
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$id_paciente, $id_enfermero, $fecha, $signos_vitales, $medicamentos, $procedimientos, $observaciones]);

                $success_msg = "Reporte de enfermería guardado correctamente.";
                break;

            case 'kinesica':
                if (empty($_POST['id_paciente']) || empty($_POST['id_kinesiologo']) || empty($_POST['fecha'])) {
                    $error_msg = "Faltan datos obligatorios para el reporte kinesiológico";
                    break;
                }
                
                $id_paciente = $_POST['id_paciente'];
                $id_kinesiologo = $_POST['id_kinesiologo'];
                $fecha = $_POST['fecha'];
                $ejercicios = $_POST['ejercicios'] ?? '';
                $duracion = $_POST['duracion'] ?? '';
                $observaciones = $_POST['observaciones'] ?? '';
                $progreso = $_POST['progreso'] ?? '';

                $stmt = $pdo->prepare("INSERT INTO reportes_kinesica (id_paciente, id_kinesiologo, fecha, ejercicios, duracion, observaciones, progreso)
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$id_paciente, $id_kinesiologo, $fecha, $ejercicios, $duracion, $observaciones, $progreso]);

                $success_msg = "Reporte kinesiológico guardado correctamente.";
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
  <option value="enfermeria">Enfermería</option>
  <option value="kinesica">Kinesiología</option>
</select>

<!-- 🔽 FORMULARIO MÉDICO COMPLETO -->
<!-- 🔽 FORMULARIO MÉDICO COMPLETO ACTUALIZADO -->
<div id="form-medico" class="form-section">
  <h3>Formulario Médico</h3>
  <form method="POST" onsubmit="return validarFormMedico()">
    <input type="hidden" name="tipo_reporte" value="medico">

    <!-- Sección de datos básicos -->
    <div class="form-basico">
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
    </div>

    <!-- Sección de signos vitales estructurados -->
    <div class="signos-section">
      <h4>Signos Vitales</h4>
      <div class="signos-grid">
        <div>
          <label>T.A. Sistólica:</label>
          <input type="number" name="ta_sistolica" min="70" max="250" 
                 placeholder="120" value="<?= $_POST['ta_sistolica'] ?? '' ?>">
          <span class="unidad">mmHg</span>
        </div>
        
        <div>
          <label>T.A. Diastólica:</label>
          <input type="number" name="ta_diastolica" min="40" max="150" 
                 placeholder="80" value="<?= $_POST['ta_diastolica'] ?? '' ?>">
          <span class="unidad">mmHg</span>
        </div>
        
        <div>
          <label>Frec. Cardíaca:</label>
          <input type="number" name="fc" min="30" max="200" 
                 placeholder="72" value="<?= $_POST['fc'] ?? '' ?>">
          <span class="unidad">lpm</span>
        </div>
        
        <div>
          <label>Frec. Respiratoria:</label>
          <input type="number" name="fr" min="6" max="60" 
                 placeholder="16" value="<?= $_POST['fr'] ?? '' ?>">
          <span class="unidad">rpm</span>
        </div>
        
        <div>
          <label>Sat. O<sub>2</sub>:</label>
          <input type="number" name="sat_o2" min="60" max="100" 
                 placeholder="98" value="<?= $_POST['sat_o2'] ?? '' ?>">
          <span class="unidad">%</span>
        </div>
        
        <div>
          <label>Temperatura:</label>
          <input type="number" name="temp" step="0.1" min="35" max="42" 
                 placeholder="36.5" value="<?= $_POST['temp'] ?? '' ?>">
          <span class="unidad">°C</span>
        </div>
        
        <div>
          <label>Peso:</label>
          <input type="number" name="peso" step="0.1" min="20" max="250" 
                 placeholder="70.5" value="<?= $_POST['peso'] ?? '' ?>">
          <span class="unidad">kg</span>
        </div>
        
        <div>
          <label>Talla:</label>
          <input type="number" name="talla" min="100" max="250" 
                 placeholder="170" value="<?= $_POST['talla'] ?? '' ?>">
          <span class="unidad">cm</span>
        </div>
        
        <div>
          <label>Glucemia:</label>
          <input type="number" name="glucemia" min="30" max="500" 
                 placeholder="90" value="<?= $_POST['glucemia'] ?? '' ?>">
          <span class="unidad">mg/dL</span>
        </div>
      </div>
      
      <label>Otros signos:</label>
      <textarea name="otros_signos" rows="2" placeholder="Otros signos relevantes"><?= $_POST['otros_signos'] ?? '' ?></textarea>
    </div>

    <!-- Sección de evaluación médica -->
    <div class="form-columns">
      <div class="column">
        <label>Sueño:</label>
        <textarea name="sueno" rows="3" placeholder="Calidad, duración, trastornos"><?= $_POST['sueno'] ?? '' ?></textarea>

        <label>Dieta:</label>
        <textarea name="dieta" rows="3" placeholder="Hábitos alimenticios, restricciones"><?= $_POST['dieta'] ?? '' ?></textarea>

        <label>Esfera Emocional:</label>
        <textarea name="esfera" rows="3" placeholder="Estado anímico, comportamiento"><?= $_POST['esfera'] ?? '' ?></textarea>
      </div>

      <div class="column">
        <label>Memoria:</label>
        <textarea name="memoria" rows="3" placeholder="Evaluación cognitiva"><?= $_POST['memoria'] ?? '' ?></textarea>

        <label>Micciones:</label>
        <textarea name="micciones" rows="3" placeholder="Frecuencia, características"><?= $_POST['micciones'] ?? '' ?></textarea>

        <label>Evacuaciones:</label>
        <textarea name="evacuaciones" rows="3" placeholder="Frecuencia, características"><?= $_POST['evacuaciones'] ?? '' ?></textarea>
      </div>
    </div>

    <label>Eventualidades:</label>
    <textarea name="eventualidades" rows="3" placeholder="Eventos relevantes ocurridos"><?= $_POST['eventualidades'] ?? '' ?></textarea>

    <label>Análisis:</label>
    <textarea name="analisis" rows="4" placeholder="Interpretación de los hallazgos"><?= $_POST['analisis'] ?? '' ?></textarea>

    <label>Plan:</label>
    <textarea name="plan" rows="4" placeholder="Plan de tratamiento, recomendaciones"><?= $_POST['plan'] ?? '' ?></textarea>

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

<!-- 🔽 FORMULARIO ENFERMERÍA -->
<div id="form-enfermeria" class="form-section">
  <h3>Formulario Enfermería</h3>
  <form method="POST" onsubmit="return validarFormEnfermeria()">
    <input type="hidden" name="tipo_reporte" value="enfermeria">

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

    <label class="required">Enfermero/a:</label>
    <select name="id_enfermero" required>
      <option value="">-- Seleccione un enfermero --</option>
      <?php if (!empty($enfermeria)): ?>
        <?php foreach ($enfermeria as $e): ?>
          <option value="<?= $e['nip'] ?>" <?= isset($_POST['id_enfermero']) && $_POST['id_enfermero'] == $e['nip'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($e['nombre']) ?>
          </option>
        <?php endforeach; ?>
      <?php else: ?>
        <option value="">No hay enfermeros registrados</option>
      <?php endif; ?>
    </select>

    <label class="required">Fecha:</label>
    <input type="date" name="fecha" required value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : '' ?>">

    <label>Signos vitales:</label>
    <textarea name="signos_vitales"><?= isset($_POST['signos_vitales']) ? htmlspecialchars($_POST['signos_vitales']) : '' ?></textarea>

    <label>Medicamentos administrados:</label>
    <textarea name="medicamentos"><?= isset($_POST['medicamentos']) ? htmlspecialchars($_POST['medicamentos']) : '' ?></textarea>

    <label>Procedimientos realizados:</label>
    <textarea name="procedimientos"><?= isset($_POST['procedimientos']) ? htmlspecialchars($_POST['procedimientos']) : '' ?></textarea>

    <label>Observaciones:</label>
    <textarea name="observaciones"><?= isset($_POST['observaciones']) ? htmlspecialchars($_POST['observaciones']) : '' ?></textarea>

    <button type="submit">Guardar</button>
  </form>
</div>

<!-- 🔽 FORMULARIO KINESIOLOGÍA -->
<div id="form-kinesica" class="form-section">
  <h3>Formulario Kinesiología</h3>
  <form method="POST" onsubmit="return validarFormKinesica()">
    <input type="hidden" name="tipo_reporte" value="kinesica">

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

    <label class="required">Kinesiólogo/a:</label>
    <select name="id_kinesiologo" required>
      <option value="">-- Seleccione un kinesiólogo --</option>
      <?php if (!empty($kinesica)): ?>
        <?php foreach ($kinesica as $k): ?>
          <option value="<?= $k['nip'] ?>" <?= isset($_POST['id_kinesiologo']) && $_POST['id_kinesiologo'] == $k['nip'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($k['nombre']) ?>
          </option>
        <?php endforeach; ?>
      <?php else: ?>
        <option value="">No hay kinesiólogos registrados</option>
      <?php endif; ?>
    </select>

    <label class="required">Fecha:</label>
    <input type="date" name="fecha" required value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : '' ?>">

    <label>Ejercicios realizados:</label>
    <textarea name="ejercicios"><?= isset($_POST['ejercicios']) ? htmlspecialchars($_POST['ejercicios']) : '' ?></textarea>

    <label>Duración (minutos):</label>
    <input type="number" name="duracion" value="<?= isset($_POST['duracion']) ? htmlspecialchars($_POST['duracion']) : '' ?>">

    <label>Observaciones:</label>
    <textarea name="observaciones"><?= isset($_POST['observaciones']) ? htmlspecialchars($_POST['observaciones']) : '' ?></textarea>

    <label>Progreso:</label>
    <textarea name="progreso"><?= isset($_POST['progreso']) ? htmlspecialchars($_POST['progreso']) : '' ?></textarea>

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
  } else if (area === 'enfermeria') {
    document.getElementById('form-enfermeria').style.display = 'block';
  } else if (area === 'kinesica') {
    document.getElementById('form-kinesica').style.display = 'block';
  }
}

// Validación del formulario médico
function validarFormMedico() {
  // Validación básica de campos obligatorios
  const paciente = document.querySelector('select[name="id_paciente"]').value;
  const medico = document.querySelector('select[name="id_medico"]').value;
  const fecha = document.querySelector('input[name="fecha"]').value;
  
  if (!paciente || !medico || !fecha) {
    alert('Por favor complete todos los campos obligatorios');
    return false;
  }
  
  // Validación adicional de signos vitales (opcional)
  const taSistolica = document.querySelector('input[name="ta_sistolica"]').value;
  const taDiastolica = document.querySelector('input[name="ta_diastolica"]').value;
  
  if (taSistolica && taDiastolica && parseInt(taSistolica) <= parseInt(taDiastolica)) {
    alert('La TA sistólica debe ser mayor que la diastólica');
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

// Validación del formulario enfermería
function validarFormEnfermeria() {
  const paciente = document.querySelector('#form-enfermeria select[name="id_paciente"]').value;
  const enfermero = document.querySelector('#form-enfermeria select[name="id_enfermero"]').value;
  const fecha = document.querySelector('#form-enfermeria input[name="fecha"]').value;
  
  if (!paciente || !enfermero || !fecha) {
    alert('Por favor complete todos los campos obligatorios');
    return false;
  }
  return true;
}

// Validación del formulario kinesiología
function validarFormKinesica() {
  const paciente = document.querySelector('#form-kinesica select[name="id_paciente"]').value;
  const kinesiologo = document.querySelector('#form-kinesica select[name="id_kinesiologo"]').value;
  const fecha = document.querySelector('#form-kinesica input[name="fecha"]').value;
  
  if (!paciente || !kinesiologo || !fecha) {
    alert('Por favor complete todos los campos obligatorios');
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