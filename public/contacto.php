<?php include '../includes/header.php'; ?>

<link rel="stylesheet" href="../assets/css/contacto.css">

<!-- Fondo difuminado -->
<div class="background"></div>

<!-- Contenedor principal -->
<div class="container">
  <!-- Formulario --> 
  <div class="formulario">
    <h2>¡Contáctanos!</h2>
    
    <!-- Mensaje de éxito (oculto inicialmente) -->
    <div id="mensajeExito" style="display: none; background: #4CAF50; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
      ¡Gracias por tu mensaje! Te atenderemos pronto.
    </div>
    
    <form id="contactForm" action="https://formsubmit.co/ktbernalgallegos@gmail.com" method="POST">
      <!-- Configuración de FormSubmit -->
      <input type="hidden" name="_next" value="http://localhost/gracias.html"> <!-- Puedes dejarlo o quitarlo -->
      <input type="hidden" name="_subject" value="Nuevo mensaje desde el sitio web">
      <input type="hidden" name="_template" value="box">
      
      <!-- Campos del formulario -->
      <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" required>
      <input type="email" name="email" id="email" placeholder="Tu correo electrónico" required>
      <input type="text" name="telefono" id="telefono" placeholder="Teléfono de contacto">
      <textarea name="mensaje" id="mensaje" placeholder="¿Cómo podemos ayudarte?" rows="5" required></textarea>
      <button type="submit" id="submitBtn">Enviar consulta</button>
    </form>
  </div>

  < <!-- Información de contacto -->
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

</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault(); // Evita el envío normal
  
  // Muestra mensaje de "Enviando..."
  const submitBtn = document.getElementById('submitBtn');
  submitBtn.disabled = true;
  submitBtn.textContent = 'Enviando...';
  
  // Realiza el envío mediante Fetch API
  fetch(this.action, {
    method: 'POST',
    body: new FormData(this),
    headers: {
      'Accept': 'application/json'
    }
  })
  .then(response => {
    if (response.ok) {
      // Muestra el mensaje de éxito
      document.getElementById('mensajeExito').style.display = 'block';
      document.getElementById('contactForm').reset();
      
      // Oculta el mensaje después de 5 segundos
      setTimeout(() => {
        document.getElementById('mensajeExito').style.display = 'none';
      }, 5000);
    } else {
      throw new Error('Error en el envío');
    }
  })
  .catch(error => {
    alert('Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.');
    console.error('Error:', error);
  })
  .finally(() => {
    submitBtn.disabled = false;
    submitBtn.textContent = 'Enviar consulta';
  });
});
</script>

<?php include '../includes/footer.php'; ?>