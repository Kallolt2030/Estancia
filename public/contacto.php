<?php include '../includes/header.php'; ?>

<link rel="stylesheet" href="../assets/css/contacto.css">

<!-- Fondo difuminado -->
<div class="background"></div>

<!-- Contenedor principal -->
<div class="container">
  <!-- Formulario --> 
  <div class="formulario">
    <h2>¡Contáctanos!</h2>
    <form id="contactForm" onsubmit="enviarCorreo(); return false;">
      <input type="text" id="nombre" placeholder="Tu nombre" required>
      <input type="email" id="email" placeholder="Tu correo electrónico" required>
      <input type="text" id="telefono" placeholder="Teléfono de contacto">
      <textarea id="mensaje" placeholder="¿Cómo podemos ayudarte?" rows="5" required></textarea>
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
      </div>

      <!-- Botón de WhatsApp -->
      <div style="margin-top: 20px;">
        <a href="https://wa.me/524951071509?text=Hola%20,%20me%20gustaría%20recibir%20más%20información%20sobre%20los%20servicios%20de%20Estancia%20de%20Vida%20de%20Nuestra%20Señora%20de%20Guadalupe%20." class="whatsapp-link">
          <button type="button" class="whatsapp-button">
            Contáctanos por WhatsApp
            <img src="../assets/iconos/whatsapp-svgrepo-com.svg" alt="WhatsApp">
          </button>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Script para enviar el correo con mailto -->
<script>
function enviarCorreo() {
  const nombre = document.getElementById("nombre").value;
  const email = document.getElementById("email").value;
  const telefono = document.getElementById("telefono").value;
  const mensaje = document.getElementById("mensaje").value;

  const destino = "ktbernalgallegos@gmail.com";
  const asunto = encodeURIComponent("Consulta desde el sitio web");
  const cuerpo = encodeURIComponent(
    `Nombre: ${nombre}\nCorreo: ${email}\nTeléfono: ${telefono}\n\nMensaje:\n${mensaje}`
  );

  window.location.href = `mailto:${destino}?subject=${asunto}&body=${cuerpo}`;
}
</script>

<?php include '../includes/footer.php'; ?>
