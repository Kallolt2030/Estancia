<?php
  require 'includes/funciones.php';
  includeTemplate('header');
?>

<div class="background"></div>
<div class="contenedor">
  <div class="formulario">
    <h2>¡Contáctanos!</h2>
    <div id="mensajeExito" style="display: none; background: #4CAF50; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
      ¡Gracias por tu mensaje! Te atenderemos pronto.
    </div>
    
    <form id="contactForm" action="https://formsubmit.co/ktbernalgallegos@gmail.com" method="POST">
      <input type="hidden" name="_next" value="http://localhost/gracias.html">
      <input type="hidden" name="_subject" value="Nuevo mensaje desde el sitio web">
      <input type="hidden" name="_template" value="box">
      <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" required>
      <input type="email" name="email" id="email" placeholder="Tu correo electrónico" required>
      <input type="text" name="telefono" id="telefono" placeholder="Teléfono de contacto">
      <textarea name="mensaje" id="mensaje" placeholder="¿Cómo podemos ayudarte?" rows="5" required></textarea>
      <button type="submit" id="submitBtn">Enviar consulta</button>
    </form>
  </div>

  <div class="informacion">
    <div class="direccion">
      <h3>¿Dónde estamos?</h3>
      <p>Estancia de Vida de Nuestra Señora de Guadalupe</p>
      <p>Calle Soledad 207, Colonia el Llanito C.P 20240 Aguascalientes, Ags. </p>
      <p>Horario de servicio las 24 horas</p>
      <p>Guarderia de 08:00 a 17:00</p>
      <p>Informes 10:00 a 17:00</p>
      <p>Correo: estanciadevida2010@gmail.com<br>Tel: (449) 8943201</p>
    </div>

    <!-- Redes sociales -->
    <div class="redes-sociales">
      <h3>Redes Sociales</h3>
      <div class="iconos">
        <a href="#"><img src="../assets/iconos/facebooklog.svg" alt="Facebook"></a>
        <a href="#"><img src="../assets/iconos/instagramlog.svg" alt="Instagram"></a>
      </div>

      <!-- Botón de WhatsApp -->
      <div style="margin-top: 20px; center: center;">
        <a href="https://wa.me/524498943201?text=Hola%20,%20me%20gustaría%20recibir%20más%20información%20sobre%20los%20servicios%20de%20Estancia%20de%20Vida%20de%20Nuestra%20Señora%20de%20Guadalupe%20." class="whatsapp-link">
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

<?php
  includeTemplate('footer');
?>