<?php include '../includes/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Estancia de Vida</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Lato:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="overlay"></div>
        <div class="contenido-hero">
            <h1>Bienvenidos a Estancia de Vida</h1>
            <p>Un lugar donde la tranquilidad y el cuidado se encuentran</p>
            <a href="contacto.php" class="boton-contacto">Contáctenos</a>
        </div>
    </section>

<!-- CARRUSEL -->
<section class="carrusel">
  <div class="contenedor">
    <div class="carrusel-contenedor">
      <div class="carrusel-imagenes">
        <div class="carrusel-imagen">
          <img src="https://lh3.googleusercontent.com/proxy/3r7_3siw6I6iIusO4KichB0XtUQPW1I7EUOpwkrLgN2sBz-zkxQBj9VxmSaRQ4iTmUdfyxnlv5cBvr73uCnRSNSzvZ_SGBOXPeFeihA94Eg3EYW7Xvz1KD-bkzm7WM38_Xuj4Irin6bmHRDkPpTYnCqGf_1uBXSeuXubtw=s1360-w1360-h1020-rw" alt="Instalaciones">
        </div>
        <div class="carrusel-imagen">
          <img src="https://lh3.googleusercontent.com/p/AF1QipO_9_s5c3W8Azn6MpGpsQdS9asY6aggvtvCsvTZ=s1360-w1360-h1020-rw" alt="Actividades">
        </div>
        <div class="carrusel-imagen">
          <img src="https://lh3.googleusercontent.com/gps-cs-s/AC9h4npP9393xQimxNVyHYnvBXaMs31s4C56Se6MCobSobf5dqGprC1WUcpppCPVEiRu5zE1I0rT3AV-jY7icQL_qV5vMa3geHCQDDCzF7vxCGlqeyLNPFK-os2PMv4yI52UMsrrIVCPHQ=s1360-w1360-h1020-rw" alt="Equipo">
        </div>
      </div>
    </div>
  </div>
</section>

    <!-- INFORMACIÓN -->
    <section class="informacion">
        <div class="contenedor">
            <h2>Acerca de Nosotros</h2>
            <p>Somos un centro especializado en el cuidado de adultos mayores, ofreciendo un ambiente familiar y profesional para garantizar su bienestar.</p>
            <a href="nosotros.php" class="boton-secundario">Conoce más</a>
        </div>
    </section>

    <!-- TRABAJADORES -->
    <section class="trabajadores">
        <div class="contenedor">
            <h2>Nuestro Equipo Profesional</h2>
            <div class="trabajadores-lista">
                <div class="trabajador">
                    <img src="https://via.placeholder.com/200/87db57/FFFFFF?text=Especialista" alt="Geriatra">
                    <h3>Dr. Juan Pérez</h3>
                    <p>Geriatra</p>
                </div>
                <div class="trabajador">
                    <img src="https://via.placeholder.com/200/5fc742/FFFFFF?text=Enfermera" alt="Enfermera">
                    <h3>Lic. Ana Gómez</h3>
                    <p>Enfermera Jefa</p>
                </div>
                <div class="trabajador">
                    <img src="https://via.placeholder.com/200/248000/FFFFFF?text=Psicologa" alt="Psicóloga">
                    <h3>Psic. Carlos Ruiz</h3>
                    <p>Psicólogo</p>
                </div>
            </div>
        </div>
    </section>

    <script src="../assets/js/index.js"></script>
</body>
</html>
<?php include '../includes/footer.php'; ?>