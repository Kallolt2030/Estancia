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
            <a href="?paciente=<?= $id_paciente ?>&seccion=nota_kinesica"><button>Nota kinesiológica</button></a>
            <a href="?paciente=<?= $id_paciente ?>&seccion=reportes_generales"><button>Reportes generales</button></a>
            <a href="?paciente=<?= $id_paciente ?>&seccion=signos_vitales"><button>Signos vitales</button></a>
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
                mostrarReportesGenerales($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                mostrarReportesKinesicos($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                mostrarSignosVitales($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;
                
            case 'nota_medica':
                mostrarReportesMedicos($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;

                case 'reportes_generales':
                mostrarReportesGenerales($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;
                
            case 'nota_kinesica':
                mostrarReportesKinesicos($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;

            case 'signos_vitales':
                mostrarSignosVitales($pdo, $id_paciente, $fecha_inicio, $fecha_fin);
                break;
                
            default:
                echo "<p>Seleccione una opción del menú para visualizar el contenido.</p>";
        }
        ?>
        
        <!-- Pie de página -->
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
            
            echo '<div class="encabezado-nota">';
            echo '<span class="fecha-nota">Fecha: ' . $row['fecha'] . '</span>';
            echo '<span class="medico-nota">Médico: ' . htmlspecialchars($row['nombre_medico']) . '</span>';
            echo '</div>';
            
            echo '<div class="contenido-nota" style="display: flex; gap: 30px;">';

            // Columna izquierda: reporte médico
            echo '<div class="detalles-paciente">';
            echo '<div class="campo-nota"><strong>SUEÑO:</strong> ' . nl2br(htmlspecialchars($row['sueno'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>DIETA:</strong> ' . nl2br(htmlspecialchars($row['dieta'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>ESTADO EMOCIONAL:</strong> ' . nl2br(htmlspecialchars($row['esfera_emocional'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>MEMORIA:</strong> ' . nl2br(htmlspecialchars($row['memoria'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>MICCIONES:</strong> ' . nl2br(htmlspecialchars($row['micciones'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>ANÁLISIS:</strong> ' . nl2br(htmlspecialchars($row['analisis'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>PLAN:</strong> ' . nl2br(htmlspecialchars($row['plan'] ?? 'No registrado')) . '</div>';
            echo '<div class="campo-nota"><strong>PESO:</strong> ' . ($row['peso'] ?? 'No registrado') . ' Kg</div>';
            echo '<div class="campo-nota"><strong>TALLA:</strong> ' . ($row['talla'] ? ($row['talla'] / 100) . ' m' : 'No registrado') . '</div>';
            echo '<div class="campo-nota"><strong>IMC:</strong> ' . ($row['imc'] ?? 'No registrado') . ' kg/m²</div>';
            echo '</div>';

            // Columna derecha: signos vitales
            $stmt_sv = $pdo->prepare("SELECT * FROM signos_vitales WHERE id_paciente = ? AND fecha = ?");
            $stmt_sv->execute([$id_paciente, $row['fecha']]);
            $sv = $stmt_sv->fetch();

            echo '<div class="signos-vitales">';
            echo '<h4>SIGNOS VITALES</h4>';
            if ($sv) {
                echo '<table class="tabla-signos-vertical" border="1" cellpadding="4">';
                echo '<tr><td><strong>Presión arterial</strong></td><td>' . ($sv['ta_sistolica'] && $sv['ta_diastolica'] ? $sv['ta_sistolica'] . '/' . $sv['ta_diastolica'] . ' mmHg' : 'No registrado') . '</td></tr>';
                echo '<tr><td><strong>FC</strong></td><td>' . ($sv['fc'] ?? 'No registrado') . ' lpm</td></tr>';
                echo '<tr><td><strong>FR</strong></td><td>' . ($sv['fr'] ?? 'No registrado') . ' rpm</td></tr>';
                echo '<tr><td><strong>SpO₂</strong></td><td>' . ($sv['sat_o2'] ?? 'No registrado') . ' %</td></tr>';
                echo '<tr><td><strong>Glucemia</strong></td><td>' . ($sv['glucemia'] ?? 'No registrado') . ' mg/dL</td></tr>';
                echo '<tr><td><strong>Temperatura</strong></td><td>' . ($sv['temp'] ?? 'No registrado') . ' °C</td></tr>';
                echo '</table>';
            } else {
                echo '<p>No hay signos vitales registrados para esta fecha.</p>';
            }
            echo '</div>'; // cierre de signos-vitales

            echo '</div>'; // cierre de contenido-nota
            echo '</div>'; // cierre de nota-medica
            echo '<hr class="separador-notas">';
        }
    } else {
        echo "<p>No hay notas médicas registradas" . ($fecha_inicio || $fecha_fin ? " en el rango de fechas seleccionado" : "") . ".</p>";
    }

    echo '</div>';
}


function mostrarSignosVitales($pdo, $id_paciente, $fecha_inicio = null, $fecha_fin = null) {
    $sql = "SELECT * FROM signos_vitales WHERE id_paciente = ?";
    $params = [$id_paciente];

    if ($fecha_inicio && $fecha_fin) {
        $sql .= " AND fecha BETWEEN ? AND ?";
        array_push($params, $fecha_inicio, $fecha_fin);
    } elseif ($fecha_inicio) {
        $sql .= " AND fecha >= ?";
        array_push($params, $fecha_inicio);
    } elseif ($fecha_fin) {
        $sql .= " AND fecha <= ?";
        array_push($params, $fecha_fin);
    }

    $sql .= " ORDER BY fecha DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo '<div class="reporte">';
    echo '<h3>Signos Vitales</h3>';

    if ($stmt->rowCount() > 0) {
        echo '<table class="tabla-expediente">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Fecha</th>';
        echo '<th>PA (mmHg)</th>';
        echo '<th>FC (lpm)</th>';
        echo '<th>FR (rpm)</th>';
        echo '<th>SpO₂ (%)</th>';
        echo '<th>Glucemia (mg/dL)</th>';
        echo '<th>Temp (°C)</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($stmt as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['fecha']) . '</td>';
            echo '<td>' . (($row['ta_sistolica'] && $row['ta_diastolica']) ? $row['ta_sistolica'] . '/' . $row['ta_diastolica'] : '--') . '</td>';
            echo '<td>' . ($row['fc'] ?? '--') . '</td>';
            echo '<td>' . ($row['fr'] ?? '--') . '</td>';
            echo '<td>' . ($row['sat_o2'] ?? '--') . '</td>';
            echo '<td>' . ($row['glucemia'] ?? '--') . '</td>';
            echo '<td>' . ($row['temp'] ?? '--') . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo "<p>No hay registros de signos vitales" . ($fecha_inicio || $fecha_fin ? " en el rango de fechas seleccionado" : "") . ".</p>";
    }

    echo '</div>';
}


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
            echo '<td>' . $row['fecha'] . '</td>';
            echo '<td>' . htmlspecialchars($row['nombre_kinesiologo']) . '</td>';
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

function mostrarReportesGenerales($pdo, $id_paciente, $fecha_inicio = null, $fecha_fin = null) {
    $sql = "SELECT rg.*, u.nombre AS nombre_usuario, u.rol AS rol_usuario
            FROM reportes_generales rg
            JOIN usuarios u ON rg.nip_usuario = u.nip
            WHERE rg.id_paciente = ?";

    $params = [$id_paciente];

    if ($fecha_inicio && $fecha_fin) {
        $sql .= " AND rg.fecha BETWEEN ? AND ?";
        array_push($params, $fecha_inicio, $fecha_fin);
    } elseif ($fecha_inicio) {
        $sql .= " AND rg.fecha >= ?";
        array_push($params, $fecha_inicio);
    } elseif ($fecha_fin) {
        $sql .= " AND rg.fecha <= ?";
        array_push($params, $fecha_fin);
    }

    $sql .= " ORDER BY rg.fecha DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $reportes = $stmt->fetchAll();

    echo '<div class="reporte">';
    echo '<h3>Notas Generales (Enfermería / Cuidadores)</h3>';

    if (count($reportes) > 0) {
        echo '<table class="tabla-expediente">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Fecha</th>';
        echo '<th>Personal</th>';
        echo '<th>Medicamentos</th>';
        echo '<th>Vía</th>';
        echo '<th>Horario</th>';
        echo '<th>Procedimientos</th>';
        echo '<th>Evacuaciones</th>';
        echo '<th>Orina</th>';
        echo '<th>Vómito</th>';
        echo '<th>¿Comió?</th>';
        echo '<th>¿Agua?</th>';
        echo '<th>¿Colación?</th>';
        echo '<th>Observaciones</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($reportes as $row) {
            // Determinar rol legible
            $rol_legible = 'Usuario';
            if ($row['rol_usuario'] === 'cuidador') {
                $rol_legible = 'Cuidador(a)';
            } elseif ($row['rol_usuario'] === 'enfermeria') {
                $rol_legible = 'Enfermero(a)';
            }

            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['fecha']) . '</td>';
            echo '<td>' . $rol_legible . ': ' . htmlspecialchars($row['nombre_usuario']) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['medicamentos'] ?? '')) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['via'] ?? '')) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['horario'] ?? '')) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['procedimientos'] ?? '')) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['evacuaciones'] ?? '')) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['orina'] ?? '')) . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['vomito'] ?? '')) . '</td>';
            echo '<td>' . ($row['comio'] ? 'Sí' : 'No') . '</td>';
            echo '<td>' . ($row['agua'] ? 'Sí' : 'No') . '</td>';
            echo '<td>' . ($row['colacion'] ? 'Sí' : 'No') . '</td>';
            echo '<td>' . nl2br(htmlspecialchars($row['observaciones'] ?? '')) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo "<p>No hay notas generales registradas" . ($fecha_inicio || $fecha_fin ? " en el rango de fechas seleccionado" : "") . ".</p>";
    }

    echo '</div>';
}
?>
</body>
</html>