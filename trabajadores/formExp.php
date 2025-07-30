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
    $ingredientes = $pdo->query("SELECT id, nombre, unidad FROM ingredientes")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_msg = "Error al cargar datos: " . $e->getMessage();
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo_reporte'] ?? '';
    
    try {
        switch ($tipo) {

    case 'cuidador-enfermero':
    if (empty($_POST['id_paciente']) || empty($_POST['nip_usuario']) || empty($_POST['fecha'])) {
        $error_msg = "Faltan datos obligatorios para el reporte del cuidador";
        break;
    }

    $id_paciente = $_POST['id_paciente'];
    $nip_usuario = $_POST['nip_usuario'];
    $fecha = $_POST['fecha'];
    $observaciones = $_POST['observaciones'] ?? '';

    // Datos del cuidador
    $comio = $_POST['comio'] ?? 0;
    $agua = $_POST['agua'] ?? 0;
    $colacion = $_POST['colacion'] ?? 0;

    // Datos de enfermería
    $medicamentos = $_POST['medicamentos'] ?? null;
    $via = $_POST['via'] ?? null;
    $horario = $_POST['horario'] ?? null;
    $procedimientos = $_POST['procedimientos'] ?? null;
    $evacuaciones = $_POST['evacuaciones'] ?? null;
    $orina = $_POST['orina'] ?? null;
    $vomito = $_POST['vomito'] ?? null;

    // Datos para cocina
    $tipo_comida = $_POST['tipo_comida'] ?? null;
    $platillo = $_POST['platillo'] ?? null;
    $hora_comida = $_POST['hora_comida'] ?? null;

    try {
        $pdo->beginTransaction();

        // Insertar en reportes_generales
        $stmt = $pdo->prepare("INSERT INTO reportes_generales 
    (id_paciente, nip_usuario, fecha, medicamentos, via, horario, procedimientos, evacuaciones, orina, vomito, agua, colacion, comio, observaciones)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        
        $stmt->execute([
    $id_paciente, $nip_usuario, $fecha,
    $medicamentos, $via, $horario, $procedimientos,
    $evacuaciones, $orina, $vomito,
    $agua, $colacion, $comio,
    $observaciones
]);

        // Guardar comida si comió
        if ($comio == 1 && $tipo_comida && $platillo && $hora_comida) {
            if (in_array($tipo_comida, ['desayuno', 'comida', 'cena'])) {

                // Guardar en consumo_comidas
                $stmt2 = $pdo->prepare("INSERT INTO consumo_comidas 
                    (id_paciente, fecha, hora, tipo_comida, platillo_consumido)
                    VALUES (?, ?, ?, ?, ?)");
                $stmt2->execute([$id_paciente, $fecha, $hora_comida, $tipo_comida, $platillo]);

                // Verificar si ya existe un registro en cocina
                $stmt3 = $pdo->prepare("SELECT id FROM cocina WHERE fecha = ?");
                $stmt3->execute([$fecha]);
                $existeRegistro = $stmt3->fetch();

                if ($existeRegistro) {
                    $query = "UPDATE cocina SET $tipo_comida = ? WHERE fecha = ?";
                    $stmt4 = $pdo->prepare($query);
                    $stmt4->execute([$platillo, $fecha]);
                } else {
                    $query = "INSERT INTO cocina (fecha, $tipo_comida) VALUES (?, ?)";
                    $stmt5 = $pdo->prepare($query);
                    $stmt5->execute([$fecha, $platillo]);
                }
            }
        }

        $pdo->commit();
        $success_msg = "Reporte del cuidador-enfermero guardado correctamente.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_msg = "Error al guardar el reporte: " . $e->getMessage();
    }
    break;

 case 'signos_vitales':
    if (empty($_POST['id_paciente']) || empty($_POST['fecha'])) {
        $error_msg = "Faltan datos obligatorios para el reporte de signos vitales";
        break;
    }

    $id_paciente = $_POST['id_paciente'];
    $fecha = $_POST['fecha'];
    $ta_sistolica = $_POST['ta_sistolica'] ?? null;
    $ta_diastolica = $_POST['ta_diastolica'] ?? null;
    $fc = $_POST['fc'] ?? null;
    $fr = $_POST['fr'] ?? null;
    $sat_o2 = $_POST['sat_o2'] ?? null;
    $glucemia = $_POST['glucemia'] ?? null;
    $temp = $_POST['temp'] ?? null;

   try {
    $pdo->beginTransaction();

    // Preparar la consulta SQL
    $stmt = $pdo->prepare("INSERT INTO signos_vitales 
        (id_paciente, fecha, ta_sistolica, ta_diastolica, fc, fr, sat_o2, glucemia, temp)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Ejecutar la consulta
    $stmt->execute([
        $id_paciente, $fecha,
        $ta_sistolica, $ta_diastolica, $fc, $fr, $sat_o2, $glucemia, $temp
    ]);

    // Confirmar la transacción
    $pdo->commit();
    $success_msg = "Reporte de signos vitales guardado correctamente.";
} catch (PDOException $e) {
    // Revertir en caso de error
    $pdo->rollBack();
    $error_msg = "Error al guardar el reporte: " . $e->getMessage();

    // Para depuración: muestra el error completo
    echo "<pre>" . $e->getMessage() . "</pre>";
}

    break;




case 'cocina':
    // Validar que la fecha no esté vacía
    if (empty($_POST['fecha'])) {
        $error_msg = "La fecha es obligatoria para el reporte de cocina";
        break;
    }

    // Obtener los datos del formulario
    $fecha = $_POST['fecha'];
    $ingredientes = $_POST['ingredientes'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];
    $notas = $_POST['notas'] ?? '';

    try {
        // Iniciar la transacción
        $pdo->beginTransaction();

        // Guardar el reporte en la base de datos
        $stmt = $pdo->prepare("INSERT INTO reportes_cocina (fecha, notas) VALUES (?, ?)");
        $stmt->execute([$fecha, $notas]);
        $reporte_id = $pdo->lastInsertId(); // Obtener el ID del reporte recién insertado

        // Verificar que los ingredientes no estén vacíos
        if (!empty($ingredientes) && !empty($cantidades)) {
            foreach ($ingredientes as $index => $ingrediente_id) {
                $cantidad_utilizada = (float) $cantidades[$index];

                // Insertar cada ingrediente en la tabla ingredientes_usados
                $stmt_ingrediente = $pdo->prepare("INSERT INTO ingredientes_usados (id_reporte, id_ingrediente, cantidad_utilizada) 
                                                  VALUES (?, ?, ?)");
                $stmt_ingrediente->execute([$reporte_id, $ingrediente_id, $cantidad_utilizada]);

                // Verificar si el ingrediente existe en el inventario
                $stmt_check = $pdo->prepare("SELECT cantidad_disponible FROM ingredientes WHERE id = ?");
                $stmt_check->execute([$ingrediente_id]);
                $cantidad_disponible = $stmt_check->fetchColumn();

                // Si hay suficiente cantidad, descontamos, sino mostramos un error
                if ($cantidad_disponible >= $cantidad_utilizada) {
                    $stmt_update = $pdo->prepare("UPDATE ingredientes SET cantidad_disponible = cantidad_disponible - ? WHERE id = ?");
                    $stmt_update->execute([$cantidad_utilizada, $ingrediente_id]);
                } else {
                    // Si no hay suficiente cantidad, mostrar un error y cancelar la transacción
                    $error_msg = "No hay suficiente cantidad del ingrediente con ID $ingrediente_id.";
                    throw new Exception($error_msg);
                }
            }
        }

        // Si todo ha ido bien, hacer commit de la transacción
        $pdo->commit();
        $success_msg = "Reporte de cocina guardado con éxito.";

    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        $pdo->rollBack();
        $error_msg = "Error al guardar el reporte de cocina: " . $e->getMessage();
    }
    break;


case 'medico':
    if (empty($_POST['id_paciente']) || empty($_POST['id_medico']) || empty($_POST['fecha'])) {
        $mensaje_error = "Faltan datos obligatorios para el reporte médico";
        break;
    }
    
    // Datos básicos
    $id_paciente = $_POST['id_paciente'];
    $id_medico = $_POST['id_medico'];
    $fecha = $_POST['fecha'];
    
    // Campos textuales
    $sueno = $_POST['sueno'] ?? null;
    $dieta = $_POST['dieta'] ?? null;
    $esfera_emocional = $_POST['esfera'] ?? null;
    $memoria = $_POST['memoria'] ?? null;
    $micciones = $_POST['micciones'] ?? null;
    $otros_signos = $_POST['otros_signos'] ?? null;
    $analisis = $_POST['analisis'] ?? null;
    $plan = $_POST['plan'] ?? null;
    
    // Asegurarse de que peso y talla estén definidos antes de calcular el IMC
    $peso = $_POST['peso'] ?? null;
    $talla = $_POST['talla'] ?? null;
    
    // Calcular IMC si hay peso y talla
    $imc = null;
    if ($peso && $talla && $talla > 0) {
        $talla_metros = $talla / 100;  // Convertir talla a metros
        $imc = round($peso / ($talla_metros * $talla_metros), 1);
    }
    
    try {
        $pdo->beginTransaction();
        
        // Ajustar la consulta SQL según los campos existentes en la base de datos
        $stmt = $pdo->prepare("INSERT INTO reportes_medicos (
            id_paciente, id_medico, fecha, 
            sueno, dieta, esfera_emocional, memoria, 
            micciones, 
            peso, talla, imc, 
            analisis, plan
        ) VALUES (
            ?, ?, ?, 
            ?, ?, ?, ?, 
            ?, 
            ?, ?, ?, 
            ?, ?
        )");

        // Ejecutar la consulta con los valores recibidos
        $stmt->execute([
            $id_paciente, $id_medico, $fecha,
            $sueno, $dieta, $esfera_emocional, $memoria,
            $micciones,
            $peso, $talla, $imc, 
            $analisis, $plan
        ]);

        $pdo->commit();
        $success_msg = "Reporte médico guardado con éxito.";
        
        // Limpiar campos después de guardar exitosamente
        $_POST = [];
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_msg = "Error al guardar el reporte médico: " . $e->getMessage();
    }
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

                $stmt = $pdo->prepare("INSERT INTO reporte_kinesico (id_paciente, id_kinesiologo, fecha, ejercicios, duracion, observaciones, progreso)
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
  <option value="cuidador-enfermero">Cuidador-Enfermero</option>
  <option value="cocina">Cocina</option>
  <option value="kinesica">Kinesico</option>
  <option value="signos_vitales">Signos vitales</option>
</select>

<!-- 🔽 FORMULARIO MÉDICO COMPLETO ACTUALIZADO -->
<div id="form-medico" class="form-section">
  <h3>Formulario Médico</h3>
  <?php if (!empty($success_msg)): ?>
    <div class="mensaje-exito"><?= htmlspecialchars($success_msg) ?></div>
<?php endif; ?>

<?php if (!empty($mensaje_error)): ?>
    <div class="mensaje-error"><?= htmlspecialchars($mensaje_error) ?></div>
<?php elseif (!empty($error_msg)): ?>
    <div class="mensaje-error"><?= htmlspecialchars($error_msg) ?></div>
<?php endif; ?>

        
        <form method="POST" onsubmit="return validarFormMedico()">
            <input type="hidden" name="tipo_reporte" value="medico">
            
            <!-- Sección de datos básicos -->
            <div class="form-basico">
                <div>
                    <label class="required">Paciente:</label>
                    <select name="id_paciente" required>
                        <option value="">-- Seleccione un paciente --</option>
                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?= $p['id_paciente'] ?>" <?= isset($_POST['id_paciente']) && $_POST['id_paciente'] == $p['id_paciente'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="required">Médico:</label>
                    <select name="id_medico" required>
                        <option value="">-- Seleccione un médico --</option>
                        <?php foreach ($medicos as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= isset($_POST['id_medico']) && $_POST['id_medico'] == $m['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="required">Fecha:</label>
                    <input type="date" name="fecha" required value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : date('Y-m-d') ?>">
                </div>
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

            <label>Peso (kg):</label>
<input type="number" name="peso" step="0.1" min="0">

<label>Talla (cm):</label>
<input type="number" name="talla" step="0.1" min="0">

            
            <label>Análisis:</label>
            <textarea name="analisis" rows="4" placeholder="Interpretación de los hallazgos"><?= $_POST['analisis'] ?? '' ?></textarea>
            
            <label>Plan:</label>
            <textarea name="plan" rows="4" placeholder="Plan de tratamiento, recomendaciones"><?= $_POST['plan'] ?? '' ?></textarea>
            
            <button type="submit">Guardar Reporte Médico</button>
        </form>
</div>

<!-- 🔽 FORMULARIO CUIDADOR -->
<div id="form-cuidador-enfermero" class="form-section">
  <h3>Formulario Cuidador-Enfermero</h3>
  <form method="POST" onsubmit="return validarFormCuidador()">
    <input type="hidden" name="tipo_reporte" value="cuidador-enfermero">

    <!-- Paciente -->
    <label class="required">Paciente:</label>
    <select name="id_paciente" required>
      <option value="">Seleccione un paciente...</option>
      <?php foreach ($pacientes as $paciente): ?>
          <option value="<?= htmlspecialchars($paciente['id_paciente']) ?>">
              <?= htmlspecialchars($paciente['nombre']) ?>
          </option>
      <?php endforeach; ?>
    </select>

<!-- Cuidador o Enfermero -->
<label class="required">Persona que realiza el reporte:</label>
<select name="nip_usuario" required>
  <option value="">Seleccione una persona...</option>
  <?php foreach ($cuidadores as $c): ?>
      <option value="<?= htmlspecialchars($c['nip']) ?>"><?= htmlspecialchars($c['nombre']) ?> (Cuidador)</option>
  <?php endforeach; ?>
  <?php foreach ($enfermeria as $n): ?>
      <option value="<?= htmlspecialchars($n['nip']) ?>"><?= htmlspecialchars($n['nombre']) ?> (Enfermería)</option>
  <?php endforeach; ?>
</select>


    <!-- Fecha -->
    <label class="required">Fecha:</label>
    <input type="date" name="fecha" required>

    <!-- ✅ CAMPOS DE CUIDADOR -->
    <h4>Registro del Cuidador</h4>

    <label>¿Comió?</label>
    <select name="comio" id="comio-select" onchange="toggleDetallesComida(this.value)">
      <option value="1">Sí</option>
      <option value="0" selected>No</option>
    </select>

    <label>¿Tomó agua?</label>
    <select name="agua">
      <option value="1">Sí</option>
      <option value="0" selected>No</option>
    </select>

    <label>¿Tomó colación?</label>
    <select name="colacion">
      <option value="1">Sí</option>
      <option value="0" selected>No</option>
    </select>

    <!-- Detalles comida -->
    <div id="detalle-comida" style="display: none;">
      <label>Tipo de comida:</label>
      <select name="tipo_comida">
        <option value="">Seleccione...</option>
        <option value="desayuno">Desayuno</option>
        <option value="comida">Comida</option>
        <option value="cena">Cena</option>
      </select>

      <label>Platillo consumido:</label>
      <input type="text" name="platillo" placeholder="Ej. Sopa, arroz, pescado...">

      <label>Hora de comida:</label>
      <input type="time" name="hora_comida">
    </div>

    <!-- ✅ CAMPOS DE ENFERMERÍA -->
    <h4>Registro de Enfermería</h4>

    <label>Medicamentos administrados:</label>
    <textarea name="medicamentos"></textarea>

    <label>Vía de administración:</label>
    <input type="text" name="via" placeholder="Ej. oral, intravenosa, etc.">

    <label>Horario de administración:</label>
    <input type="text" name="horario" placeholder="Ej. 08:00, 14:00...">

    <label>Procedimientos realizados:</label>
    <textarea name="procedimientos"></textarea>

    <label>Evacuaciones:</label>
    <textarea name="evacuaciones"></textarea>

    <label>Orina:</label>
    <textarea name="orina"></textarea>

    <label>Vómito:</label>
    <textarea name="vomito"></textarea>

    <!-- Observaciones generales -->
    <label>Observaciones:</label>
    <textarea name="observaciones"></textarea>

    <button type="submit">Guardar</button>
  </form>
</div>

<script>
  function toggleDetallesComida(valor) {
    document.getElementById('detalle-comida').style.display = valor == '1' ? 'block' : 'none';
  }

  function validarFormCuidador() {
    const comio = document.getElementById('comio-select').value;
    if (comio == '1') {
      const tipoComida = document.querySelector('[name="tipo_comida"]').value;
      const platillo = document.querySelector('[name="platillo"]').value;
      const hora = document.querySelector('[name="hora_comida"]').value;
      
      if (!tipoComida || !platillo || !hora) {
        alert('Por favor complete todos los detalles de la comida');
        return false;
      }
    }
    return true;
  }
</script>

<div id="form-signos-vitales" class="form-section">
  <h3>Formulario Signos Vitales</h3>
  <form method="POST" onsubmit="return validarFormSignos()">
    <input type="hidden" name="tipo_reporte" value="signos_vitales">

    <label class="required">Paciente:</label>
    <select name="id_paciente" required>
      <option value="">-- Seleccione un paciente --</option>
      <?php foreach ($pacientes as $p): ?>
        <option value="<?= $p['id_paciente'] ?>" <?= isset($_POST['id_paciente']) && $_POST['id_paciente'] == $p['id_paciente'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($p['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label class="required">Fecha:</label>
    <input type="date" name="fecha" required value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : '' ?>">

    <label>T.A. Sistólica:</label>
    <input type="number" name="ta_sistolica" min="70" max="250" placeholder="120" value="<?= $_POST['ta_sistolica'] ?? '' ?>">
    <span class="unidad">mmHg</span>

    <label>T.A. Diastólica:</label>
    <input type="number" name="ta_diastolica" min="40" max="150" placeholder="80" value="<?= $_POST['ta_diastolica'] ?? '' ?>">
    <span class="unidad">mmHg</span>

    <label>Frec. Cardíaca:</label>
    <input type="number" name="fc" min="30" max="200" placeholder="72" value="<?= $_POST['fc'] ?? '' ?>">
    <span class="unidad">lpm</span>

    <label>Frec. Respiratoria:</label>
    <input type="number" name="fr" min="6" max="60" placeholder="16" value="<?= $_POST['fr'] ?? '' ?>">
    <span class="unidad">rpm</span>

    <label>Sat. O<sub>2</sub>:</label>
    <input type="number" name="sat_o2" min="60" max="100" placeholder="98" value="<?= $_POST['sat_o2'] ?? '' ?>">
    <span class="unidad">%</span>

    <label>Glucemia:</label>
    <input type="number" name="glucemia" min="30" max="500" placeholder="90" value="<?= $_POST['glucemia'] ?? '' ?>">
    <span class="unidad">mg/dL</span>
    <label>Temperatura:</label>
    <input type="number" name="temp" step="0.1" min="35" max="42" placeholder="36.5" value="<?= $_POST['temp'] ?? '' ?>">
    <span class="unidad">°C</span>

    <button type="submit">Guardar</button>
  </form>

</div>


<!-- 🔽 FORMULARIO COCINA -->
<div id="form-cocina" class="form-section">
  <h3>Formulario Cocina</h3>
  <form method="POST" onsubmit="return validarFormCocina()">
    <input type="hidden" name="tipo_reporte" value="cocina">

    <label class="required">Fecha:</label>
    <input type="date" name="fecha" required>

    <!-- Contenedor de ingredientes -->
    <div id="ingredientes-container">
        <div class="ingrediente-item">
            <!-- Ingrediente del inventario -->
            <label>Ingrediente:</label>
            <select name="ingredientes[]" class="ingrediente-select" required onchange="actualizarUnidad(this)">
              <option value="" disabled selected>Selecciona un ingrediente</option>
              <?php foreach ($ingredientes as $ingrediente): ?>
                  <option value="<?php echo htmlspecialchars($ingrediente['id']); ?>" 
                          data-unidad="<?php echo htmlspecialchars($ingrediente['unidad']); ?>">
                      <?php echo htmlspecialchars($ingrediente['nombre']); ?>
                  </option>
              <?php endforeach; ?>
            </select>

            <!-- Cantidad usada -->
            <label>Cantidad usada:</label>
            <input type="number" name="cantidad[]" placeholder="Cantidad" step="0.01" min="0.01" required>

            <!-- Unidad (predefinida y bloqueada) -->
            <label>Unidad:</label>
            <input type="text" name="unidad[]" class="unidad-input" readonly required>
        </div>
    </div>

    <!-- Botón para agregar más ingredientes -->
    <button type="button" onclick="agregarIngrediente()">Agregar otro ingrediente</button>

    <label>Notas:</label>
    <textarea name="notas"></textarea>

    <button type="submit">Guardar</button>
  </form>
</div>

<script>
function actualizarUnidad(select) {
    const unidad = select.selectedOptions[0].getAttribute('data-unidad');
    const unidadInput = select.closest('.ingrediente-item').querySelector('.unidad-input');
    unidadInput.value = unidad || '';
}

// Clona el bloque de ingredientes
function agregarIngrediente() {
    const container = document.getElementById('ingredientes-container');
    const item = container.querySelector('.ingrediente-item');
    const clone = item.cloneNode(true);

    // Limpiar valores en el clon
    clone.querySelector('select').selectedIndex = 0;
    clone.querySelector('input[name="cantidad[]"]').value = '';
    clone.querySelector('input[name="unidad[]"]').value = '';

    container.appendChild(clone);
}
</script>

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
  } else if (area === 'cuidador-enfermero') {
    document.getElementById('form-cuidador-enfermero').style.display = 'block';
  } else if (area === 'cocina') {
    document.getElementById('form-cocina').style.display = 'block';
  } else if (area === 'signos_vitales') {
    document.getElementById('form-signos-vitales').style.display = 'block';
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

function validarFormSignos() {
  const paciente = document.querySelector('#form-signos-vitales select[name="id_paciente"]').value;
  const fecha = document.querySelector('#form-signos-vitales input[name="fecha"]').value;
  
  if (!paciente || !fecha) {
    alert('Por favor complete todos los campos obligatorios');
    return false;
  }
  
  // Validación de signos vitales
  const taSistolica = document.querySelector('#form-signos-vitales input[name="ta_sistolica"]').value;
  const taDiastolica = document.querySelector('#form-signos-vitales input[name="ta_diastolica"]').value;
  
  if (taSistolica && taDiastolica && parseInt(taSistolica) <= parseInt(taDiastolica)) {
    alert('La TA sistólica debe ser mayor que la diastólica');
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