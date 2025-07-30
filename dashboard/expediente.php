<?php
session_start();
include '../includes/headerDash.php';
include '../control/bd.php';

// Verificar autenticación y rol
if (!isset($_SESSION['nip']) || $_SESSION['rol'] !== 'familiar') {
    header('Location: ../index.php');
    exit;
}

$nip = $_SESSION['nip'];
$nombre = $_SESSION['nombre'];

// Obtener pacientes relacionados con el familiar
$stmt = $pdo->prepare("SELECT id_paciente, nombre, TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad FROM pacientes WHERE nip = ?");
$stmt->execute([$nip]);
$pacientes = $stmt->fetchAll();

if (count($pacientes) === 0) {
    echo "<p>No hay pacientes registrados para este familiar.</p>";
    exit;
}

// Determinar qué paciente mostrar (por defecto el primero)
$id_paciente = $_GET['paciente'] ?? $pacientes[0]['id_paciente'];
$nombre_paciente = '';
$edad_paciente = '';

// Validar que el paciente seleccionado pertenezca al familiar
$paciente_valido = false;
foreach ($pacientes as $p) {
    if ($p['id_paciente'] == $id_paciente) {
        $nombre_paciente = $p['nombre'];
        $edad_paciente = $p['edad'];
        $paciente_valido = true;
        break;
    }
}

if (!$paciente_valido) {
    $id_paciente = $pacientes[0]['id_paciente'];
    $nombre_paciente = $pacientes[0]['nombre'];
    $edad_paciente = $pacientes[0]['edad'];
}

// Determinar qué sección mostrar (por defecto expediente completo)
$seccion = $_GET['seccion'] ?? 'expediente';

// Obtener fechas para filtrado
$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Expedientes - <?= htmlspecialchars($nombre_paciente) ?></title>
    <link rel="stylesheet" href="../assets/css/expediente.css">
</head>
<body>

<div class="contenedor">
    <!-- Panel lateral izquierdo -->
    <aside class="menu-lateral">
        <!-- Selector de pacientes (solo si hay más de uno) -->
        <?php if (count($pacientes) > 1): ?>
        <div class="selector-paciente">
            <label for="paciente-select">Seleccionar paciente:</label>
            <select id="paciente-select" onchange="cambiarPaciente(this.value)">
                <?php foreach ($pacientes as $p): ?>
                <option value="<?= $p['id_paciente'] ?>" <?= $p['id_paciente'] == $id_paciente ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nombre']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        
        <div class="menu-secciones">
            <a href="?paciente=<?= $id_paciente ?>&seccion=expediente"><button>Expediente médico</button></a>
            <a href="?paciente=<?= $id_paciente ?>&seccion=nota_medica"><button>Nota médica</button></a>
            <a href="?paciente=<?= $id_paciente ?>&seccion=nota_enfermeria"><button>Nota de enfermería</button></a>
            <a href="?paciente=<?= $id_paciente ?>&seccion=nota_kinesica"><button>Nota kinesiológica</button></a>
            <a href="?paciente=<?= $id_paciente ?>&seccion=nota_cuidadoras"><button>Nota de cuidadoras</button></a>
        </div>
    </aside>

  <!-- Contenedor central -->
<section class="contenido-central">
    <!-- Encabezado institucional -->
    <div class="encabezado-institucional">
        <div class="institucion">
            <div class="logo-titulo">
                <img src="../assets/iconos/logo.svg" alt="Logo" class="logo-institucion">
            </div>
            <h1>NOTA MÉDICA MENSUAL DE ESTANCIA DE VIDA NUESTRA SEÑORA DE GUADALUPE A.C.</h1>
            <div class="direccion">
                <p>Estancia de Vida: SOLEDAD 207, EL LLANITO (ESTANCIA DE LUNES A VIERNES)</p>
                <p>GERIATRIA - POR LA ATENCIÓN INTEGRAL DEL ADULTO MAYOR</p>
            </div>
        </div>
    </div>
    
    <!-- Cabecera del expediente -->
    <div class="cabecera-expediente">
        <h2>EXPEDIENTE MÉDICO</h2>
        <div class="info-paciente">
            <span><strong>Paciente:</strong> <?= htmlspecialchars($nombre_paciente) ?></span>
            <span><strong>Edad:</strong> <?= $edad_paciente ?> años</span>
        </div>
    </div>
    
    <!-- Formulario de filtrado por fechas -->
    <div class="filtro-fechas">
        <form method="GET" action="">
            <input type="hidden" name="paciente" value="<?= $id_paciente ?>">
            <input type="hidden" name="seccion" value="<?= $seccion ?>">
            
            <label for="fecha_inicio">Desde:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" 
                   value="<?= htmlspecialchars($fecha_inicio ?? '') ?>">
            
            <label for="fecha_fin">Hasta:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" 
                   value="<?= htmlspecialchars($fecha_fin ?? '') ?>">
            
            <button type="submit">Filtrar</button>
            <a href="?paciente=<?= $id_paciente ?>&seccion=<?= $seccion ?>" 
               class="btn-limpiar">Limpiar filtros</a>
        </form>
    </div>
        
        <?php
        switch ($seccion) {
            case 'expediente':
                mostrarReportesMedicos($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                mostrarReportesEnfermeria($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                mostrarReportesKinesicos($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                mostrarReportesCuidadores($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;
                
            case 'nota_medica':
                mostrarReportesMedicos($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;
                
            case 'nota_enfermeria':
                mostrarReportesEnfermeria($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;
                
            case 'nota_kinesica':
                mostrarReportesKinesicos($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;
                
            case 'nota_cuidadoras':
                mostrarReportesCuidadores($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;
                
            default:
                echo "<p>Seleccione una opción del menú para visualizar el contenido.</p>";
        }
        ?>
        <!-- Al final de tu contenido, antes de cerrar el section -->
<div class="pie-medico">
    <div class="info-medico">
        <p>Especial de Vida Nuestra Señora de Guadalupe A.C</p>
        <p>Clave de R.F.C ENV</p>
        <p>Calle Soledad 207</p>
        <p>Colonia El Llanito C.P. 20210</p>
        <p>Telefonos: 917 9871 Cel: (449) 8943201</p>
        <p>estanciadevida2010@gmail.com</p>
        
    </div>
</div>
    </section>
</div>

<script>
function cambiarPaciente(idPaciente) {
    // Redirigir manteniendo la sección actual y filtros
    const urlParams = new URLSearchParams(window.location.search);
    const seccion = urlParams.get('seccion') || 'expediente';
    const fechaInicio = urlParams.get('fecha_inicio') || '';
    const fechaFin = urlParams.get('fecha_fin') || '';
    
    let url = `?paciente=${idPaciente}&seccion=${seccion}`;
    if (fechaInicio) url += `&fecha_inicio=${fechaInicio}`;
    if (fechaFin) url += `&fecha_fin=${fechaFin}`;
    
    window.location.href = url;
}
</script>

<?php
function mostrarReportesMedicos($pdo, $id_paciente, $fecha_inicio = null, $fecha_fin = null) {
    $sql = "SELECT rm.*, m.nombre AS nombre_medico 
            FROM reportes_medicos rm
            JOIN medicos m ON rm.id_medico = m.id
            WHERE rm.id_paciente = ?";
    
    $params = [$id_paciente];
    
    // Añadir condiciones de fecha si existen
    if ($fecha_inicio && $fecha_fin) {
        $sql .= " AND rm.fecha BETWEEN ? AND ?";
        array_push($params, $fecha_inicio, $fecha_fin);
    } elseif ($fecha_inicio) {
        $sql .= " AND rm.fecha >= ?";
        array_push($params, $fecha_inicio);
    } elseif ($fecha_fin) {
        $sql .= " AND rm.fecha <= ?";
        array_push($params, $fecha_fin);
    }
    
    $sql .= " ORDER BY rm.fecha DESC";
    
    $reportes = $pdo->prepare($sql);
    $reportes->execute($params);
    
    echo '<div class="reporte">';
    echo '<h3>Notas Médicas</h3>';
    
    if ($reportes->rowCount() > 0) {
        foreach ($reportes as $row) {
            echo '<div class="nota-medica">';
            
            // Encabezado con fecha y médico
            echo '<div class="encabezado-nota">';
            echo '<span class="fecha-nota">Fecha: ' . $row['fecha'] . '</span>';
            echo '<span class="medico-nota">Médico: ' . htmlspecialchars($row['nombre_medico']) . '</span>';
            echo '</div>';
            
            // Contenido en dos columnas
            echo '<div class="contenido-nota">';
            
            // Columna izquierda - Detalles del paciente
            echo '<div class="detalles-paciente">';
            echo '<div class="campo-nota"><strong>SUEÑO:</strong> ' . nl2br(htmlspecialchars($row['sueno'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>DIETA:</strong> ' . nl2br(htmlspecialchars($row['dieta'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>ESTADO EMOCIONAL:</strong> ' . nl2br(htmlspecialchars($row['esfera_emocional'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>MEMORIA:</strong> ' . nl2br(htmlspecialchars($row['memoria'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>MICCIONES:</strong> ' . nl2br(htmlspecialchars($row['micciones'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>EVACUACIONES:</strong> ' . nl2br(htmlspecialchars($row['evacuaciones'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>ANÁLISIS:</strong> ' . nl2br(htmlspecialchars($row['analisis'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>PLAN:</strong> ' . nl2br(htmlspecialchars($row['plan'] ?? 'No registrado')) . '</div>';
            echo '</div>';
            
            // Columna derecha - Signos vitales en formato vertical
            echo '<div class="signos-vitales-vertical">';
            echo '<h4>SIGNOS VITALES</h4>';
            
            echo '<table class="tabla-signos-vertical">';
            echo '<tr><th colspan="2">Parámetro</th><th>Valor</th><th>Unidad</th></tr>';
            
            // Peso
            echo '<tr>';
            echo '<td colspan="2">Peso</td>';
            echo '<td>' . ($row['peso'] ?? '--') . '</td>';
            echo '<td>Kg</td>';
            echo '</tr>';
            
            // Talla/Estatura
            echo '<tr>';
            echo '<td colspan="2">Estatura</td>';
            echo '<td>' . ($row['talla'] ? ($row['talla']/100) : '--') . '</td>';
            echo '<td>Mt</td>';
            echo '</tr>';

              // IMC (calculado si tenemos peso y talla)
            if ($row['peso'] && $row['talla']) {
                $imc = $row['peso'] / (($row['talla']/100) * ($row['talla']/100));
                echo '<tr>';
                echo '<td colspan="2">IMC</td>';
                echo '<td>' . number_format($imc, 1) . '</td>';
                echo '<td>kg/m²</td>';
                echo '</tr>';
            }
            
            // Frecuencia Cardíaca
            echo '<tr>';
            echo '<td rowspan="2">FC</td>';
            echo '<td rowspan="2">(latidos por minuto)</td>';
            echo '<td>' . ($row['fc'] ?? '--') . '</td>';
            echo '<td>Lpm</td>';
            echo '</tr>';
            
            // Frecuencia Respiratoria
            echo '<tr>';
            echo '<td>' . ($row['fr'] ?? '--') . '</td>';
            echo '<td>rpm</td>';
            echo '</tr>';
            
            // Presión Arterial
            echo '<tr>';
            echo '<td rowspan="2">PA</td>';
            echo '<td rowspan="2">(milímetros de mercurio)</td>';
            echo '<td>' . ($row['ta_sistolica'] && $row['ta_diastolica'] ? $row['ta_sistolica'].'/'.$row['ta_diastolica'] : '--') . '</td>';
            echo '<td>mmHg</td>';
            echo '</tr>';
            
            // Temperatura
            echo '<tr>';
            echo '<td>' . ($row['temp'] ?? '--') . '</td>';
            echo '<td>°C</td>';
            echo '</tr>';
            
            // Saturación de Oxígeno
            echo '<tr>';
            echo '<td rowspan="2">SpO2</td>';
            echo '<td rowspan="2">(porcentaje de oxígeno en sangre)</td>';
            echo '<td>' . ($row['sat_o2'] ?? '--') . '</td>';
            echo '<td>%</td>';
            echo '</tr>';
            echo '</table>';
            
            echo '</div>'; // cierre de signos-vitales-vertical
            echo '</div>'; // cierre de contenido-nota
            echo '</div>'; // cierre de nota-medica
            
            echo '<hr class="separador-notas">';
        }
    } else {
        echo "<p>No hay notas médicas registradas".($fecha_inicio || $fecha_fin ? " en el rango de fechas seleccionado" : "").".</p>";
    }
    
    echo '</div>';
}
// Función para mostrar reportes de enfermería con filtrado en formato tabular
function mostrarReportesEnfermeria($pdo, $id_paciente, $fecha_inicio = null, $fecha_fin = null) {
    $sql = "SELECT re.*, u.nombre AS nombre_enfermero 
            FROM reportes_enfermeria re
            JOIN usuarios u ON re.id_enfermero = u.nip
            WHERE re.id_paciente = ?";
    
    $params = [$id_paciente];
    
    if ($fecha_inicio && $fecha_fin) {
        $sql .= " AND re.fecha BETWEEN ? AND ?";
        array_push($params, $fecha_inicio, $fecha_fin);
    } elseif ($fecha_inicio) {
        $sql .= " AND re.fecha >= ?";
        array_push($params, $fecha_inicio);
    } elseif ($fecha_fin) {
        $sql .= " AND re.fecha <= ?";
        array_push($params, $fecha_fin);
    }
    
    $sql .= " ORDER BY re.fecha DESC";
    
    $reportes = $pdo->prepare($sql);
    $reportes->execute($params);
    
    echo '<div class="reporte">';
    echo '<h3>Notas de Enfermería</h3>';
    
    if ($reportes->rowCount() > 0) {
        echo '<table class="tabla-expediente">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Fecha</th>';
        echo '<th>Enfermero</th>';
        echo '<th>Signos Vitales</th>';
        echo '<th>Medicamentos</th>';
        echo '<th>Observaciones</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($reportes as $row) {
            echo '<tr>';
            echo '<td class="fecha">' . $row['fecha'] . '</td>';
            echo '<td class="profesional">' . htmlspecialchars($row['nombre_enfermero']) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['signos_vitales'] ?? 'No registrado')) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['medicamentos'] ?? 'No registrado')) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['observaciones'] ?? 'No registrado')) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "<p>No hay notas de enfermería registradas".($fecha_inicio || $fecha_fin ? " en el rango de fechas seleccionado" : "").".</p>";
    }
    
    echo '</div>';
}

// Función para mostrar reportes kinesiológicos con filtrado en formato tabular
function mostrarReportesKinesicos($pdo, $id_paciente, $fecha_inicio = null, $fecha_fin = null) {
    $sql = "SELECT rk.*, u.nombre AS nombre_kinesiologo 
            FROM reporte_kinesico rk
            JOIN usuarios u ON rk.id_kinesiologo = u.nip
            WHERE rk.id_paciente = ?";
    
    $params = [$id_paciente];
    
    if ($fecha_inicio && $fecha_fin) {
        $sql .= " AND rk.fecha BETWEEN ? AND ?";
        array_push($params, $fecha_inicio, $fecha_fin);
    } elseif ($fecha_inicio) {
        $sql .= " AND rk.fecha >= ?";
        array_push($params, $fecha_inicio);
    } elseif ($fecha_fin) {
        $sql .= " AND rk.fecha <= ?";
        array_push($params, $fecha_fin);
    }
    
    $sql .= " ORDER BY rk.fecha DESC";
    
    $reportes = $pdo->prepare($sql);
    $reportes->execute($params);
    
    echo '<div class="reporte">';
    echo '<h3>Notas Kinesiológicas</h3>';
    
    if ($reportes->rowCount() > 0) {
        echo '<table class="tabla-expediente">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Fecha</th>';
        echo '<th>Kinesiólogo</th>';
        echo '<th>Ejercicios</th>';
        echo '<th>Duración</th>';
        echo '<th>Progreso</th>';
        echo '<th>Observaciones</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($reportes as $row) {
            echo '<tr>';
            echo '<td class="fecha">' . $row['fecha'] . '</td>';
            echo '<td class="profesional">' . htmlspecialchars($row['nombre_kinesiologo']) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['ejercicios'] ?? 'No registrado')) . '</td>';
            echo '<td>' . $row['duracion'] . ' minutos</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['progreso'] ?? 'No registrado')) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['observaciones'] ?? 'No registrado')) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "<p>No hay notas kinesiológicas registradas".($fecha_inicio || $fecha_fin ? " en el rango de fechas seleccionado" : "").".</p>";
    }
    
    echo '</div>';
}

// Función para mostrar reportes de cuidadores con filtrado en formato tabular
function mostrarReportesCuidadores($pdo, $id_paciente, $fecha_inicio = null, $fecha_fin = null) {
    $sql = "SELECT 
               rc.fecha,
               MAX(rc.comio) AS comio,
               GROUP_CONCAT(DISTINCT u.nombre SEPARATOR ', ') AS cuidadores,
               GROUP_CONCAT(rc.observaciones SEPARATOR '\n\n') AS observaciones
            FROM reportes_cuidadores rc
            JOIN usuarios u ON rc.id_cuidador = u.nip
            WHERE rc.id_paciente = ?";
    
    $params = [$id_paciente];
    
    if ($fecha_inicio && $fecha_fin) {
        $sql .= " AND rc.fecha BETWEEN ? AND ?";
        array_push($params, $fecha_inicio, $fecha_fin);
    } elseif ($fecha_inicio) {
        $sql .= " AND rc.fecha >= ?";
        array_push($params, $fecha_inicio);
    } elseif ($fecha_fin) {
        $sql .= " AND rc.fecha <= ?";
        array_push($params, $fecha_fin);
    }
    
    $sql .= " GROUP BY rc.fecha ORDER BY rc.fecha DESC";
    
    $reportes = $pdo->prepare($sql);
    $reportes->execute($params);
    
    echo '<div class="reporte">';
    echo '<h3>Notas de Cuidadores</h3>';
    
    if ($reportes->rowCount() > 0) {
        echo '<table class="tabla-expediente">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Fecha</th>';
        echo '<th>Comió</th>';
        echo '<th>Cuidadores</th>';
        echo '<th>Observaciones</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($reportes as $row) {
            echo '<tr>';
            echo '<td class="fecha">' . $row['fecha'] . '</td>';
            echo '<td>' . ($row['comio'] ? 'Sí' : 'No') . '</td>';
            echo '<td>' . htmlspecialchars($row['cuidadores']) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['observaciones'])) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "<p>No hay notas de cuidadores registradas".($fecha_inicio || $fecha_fin ? " en el rango de fechas seleccionado" : "").".</p>";
    }
    
    echo '</div>';
}
?>

<?php include '../includes/footer.php'; ?>
</body>
</html>