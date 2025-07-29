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
// Función para mostrar reportes médicos con filtrado en formato tabular
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
        echo '<table class="tabla-expediente">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Fecha</th>';
        echo '<th>Médico</th>';
        echo '<th>Detalles</th>';
        echo '<th>Signos Vitales</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($reportes as $row) {
            echo '<tr>';
            echo '<td class="fecha">' . $row['fecha'] . '</td>';
            echo '<td class="profesional">' . htmlspecialchars($row['nombre_medico']) . '</td>';
            
            // Detalles principales
            echo '<td>';
            echo '<strong>Sueño:</strong> ' . nl2br(htmlspecialchars($row['sueno'] ?? 'No registrado')) . '<br>';
            echo '<strong>Dieta:</strong> ' . nl2br(htmlspecialchars($row['dieta'] ?? 'No registrado')) . '<br>';
            echo '<strong>Estado emocional:</strong> ' . nl2br(htmlspecialchars($row['esfera_emocional'] ?? 'No registrado')) . '<br>';
            echo '<strong>Memoria:</strong> ' . nl2br(htmlspecialchars($row['memoria'] ?? 'No registrado')) . '<br>';
            echo '<strong>Micciones:</strong> ' . nl2br(htmlspecialchars($row['micciones'] ?? 'No registrado')) . '<br>';
            echo '<strong>Evacuaciones:</strong> ' . nl2br(htmlspecialchars($row['evacuaciones'] ?? 'No registrado')) . '<br>';
            echo '<strong>Análisis:</strong> ' . nl2br(htmlspecialchars($row['analisis'] ?? 'No registrado')) . '<br>';
            echo '<strong>Plan:</strong> ' . nl2br(htmlspecialchars($row['plan'] ?? 'No registrado'));
            echo '</td>';
            
            // Signos vitales
            echo '<td>';
            echo '<strong>TA:</strong> ' . $row['ta_sistolica'] . '/' . $row['ta_diastolica'] . ' mmHg<br>';
            echo '<strong>FC:</strong> ' . $row['fc'] . ' lpm<br>';
            echo '<strong>FR:</strong> ' . $row['fr'] . ' rpm<br>';
            echo '<strong>Temp:</strong> ' . $row['temp'] . '°C<br>';
            echo '<strong>SatO2:</strong> ' . $row['sat_o2'] . '%<br>';
            echo '<strong>Peso/Talla/IMC:</strong> ' . $row['peso'] . ' kg / ' . $row['talla'] . ' cm / ' . $row['imc'] . '<br>';
            echo '<strong>Glucemia:</strong> ' . $row['glucemia'] . ' mg/dL';
            echo '</td>';
            
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
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