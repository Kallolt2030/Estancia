<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/contacto.css">

<!-- Fondo difuminado -->
<div class="background"></div>

<!-- Contenedor principal -->
<div class="container">
  <!-- Formulario -->
  <div class="formulario">
    <h2>¡Contáctanos!</h2>
    <form method="POST" action="contacto.php">
      <input type="text" name="nombre" placeholder="Tu nombre" required>
      <input type="email" name="email" placeholder="Tu correo electrónico" required>
      <input type="text" name="telefono" placeholder="Teléfono de contacto">
      <textarea name="mensaje" placeholder="¿Cómo podemos ayudarte?" rows="5" required></textarea>
      <button type="submit">Enviar consulta</button>
    </form>
  </div>

  <!-- Información de contacto -->
  <div class="info">
    <div class="direccion">
      <h3>¿Dónde estamos?</h3>
      <p>Estancia de Vida "Amanecer Tranquilo"</p>
      <p>Calle Ejemplo #123, Colonia Paz, Ciudad Esperanza</p>
      <p>Horario: Lunes a Viernes de 9:00 a 17:00 hrs</p>
      <p>Correo: contacto@proyectouta.com<br>Tel: (449) 123 4567</p>
    </div>

    <!-- Redes sociales -->
    <div class="redes-sociales">
      <h3>Redes Sociales</h3>
      <div class="iconos">
        <a href="#"><img src="../assets/iconos/facebooklog.svg" alt="Facebook"></a>
        <a href="#"><img src="../assets/iconos/instagramlog.svg" alt="Instagram"></a>
        <a href="#"><img src="../assets/iconos/whatsapp-svgrepo-com.svg" alt="WhatsApp"></a>
      </div>
    </div>
  </div>
</div>

<!-- Lógica para mostrar alerta -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = htmlspecialchars($_POST['nombre']);
  $email = htmlspecialchars($_POST['email']);
  $telefono = htmlspecialchars($_POST['telefono']);
  $mensaje = htmlspecialchars($_POST['mensaje']);

  // Aquí podrías enviar correo o guardar en base de datos
  echo "<script>alert('Gracias, $nombre. Hemos recibido tu mensaje.');</script>";
}
?>

<?php include '../includes/footer.php'; ?>
