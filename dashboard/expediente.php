<?php include '../control/auth.php'; ?>
<?php include '../includes/headerDash.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Expedientes</title>
    <link rel="stylesheet" href="../assets/css/expediente.css">
</head>
<body>

<div class="contenedor">

    <!-- Panel lateral izquierdo -->
    <aside class="menu-lateral">
        <button onclick="mostrarContenido('expediente')">Expediente médico</button>
        <button onclick="mostrarContenido('nota_medica')">Nota médica</button>
        <button onclick="mostrarContenido('nota_enfermeria')">Nota de enfermería</button>
        <button onclick="mostrarContenido('nota_kinesica')">Nota kinesiológica</button>
        <button onclick="mostrarContenido('nota_cuidadoras')">Nota de cuidadoras</button>
        <button onclick="mostrarContenido('signos_vitales')">Signos vitales</button>
    </aside>

    <!-- Contenedor central donde se carga el contenido -->
    <section class="contenido-central" id="contenidoCentral">
        <p>Seleccione una opción del menú para visualizar el contenido.</p>
    </section>
        

</div>


<script>
function mostrarContenido(seccion) {
    const contenido = {
        expediente: "<h3>Expediente Médico</h3><p>Contenido del expediente médico aquí...</p>",
        nota_medica: "<h3>Nota Médica</h3><p>Contenido de la nota médica aquí...</p>",
        nota_enfermeria: "<h3>Nota de Enfermería</h3><p>Contenido de enfermería aquí...</p>",
        nota_kinesica: "<h3>Nota Kinesiológica</h3><p>Contenido de la nota kinesica aquí...</p>",
        nota_cuidadoras: "<h3>Nota de Cuidadoras</h3><p>Contenido de la nota de cuidadoras aquí...</p>",
        signos_vitales: "<h3>Signos Vitales</h3><p>Registro de signos vitales aquí...</p>",
    };

    document.getElementById("contenidoCentral").innerHTML = contenido[seccion] || "<p>Sección no disponible.</p>";
}
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>