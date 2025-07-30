<?php
  require 'includes/funciones.php';
  includeTemplate('header', $hero = false, $hero_image = true, $hero_image_name = 'hero-servicios.jpg');
?>

<main>
  <div class="services-menu container section">
    <h2 class="margin-title text-left highlighted-black-text">Nuestros Servicios</h2>
    <div class="services-menu-list">
      <p>Comedor</p>
      <p>Enfermería</p>
      <p>Psicogerontologia</p>
      <p>Terapia Cognitiva</p>
      <p>Terapia Ocupacional</p>
      <p>Acondicionamiento Físico</p>
      <p>Kinesiología</p>
      <p>Psicología</p>
      <p>Asistencia Espiritual</p>
    </div>
  </div>

  <section class="section container">
    <h2 class="margin-title text-left"><a href="servicios.php" class="highlighted-black-text">Nuestros Servicios</a></h2>
    <div class="services-section">
      <div class="services-list">
          <div class="services-item">
            <img src="/build/img/enfermeria.jpg" alt="">
            <div class="services-item-text">
              <h3 class="text-left highlighted-white-text">Enfermería</h3>
              <p>Cuidado profesional las 24 horas, centrado en la salud, seguridad y bienestar del adulto mayor, con atención personalizada y seguimiento continuo.</p>
            </div>
          </div>
          <div class="services-item">
            <img src="/build/img/terapia-cognitiva.jpg" alt="">
            <div class="services-item-text">
              <h3 class="text-left highlighted-white-text">Terapia Cognitiva</h3>
              <p>Estimulación de las funciones cognitivas como la memoria, la atención y el lenguaje, promoviendo el envejecimiento activo y la calidad de vida del adulto mayor.</p>
            </div>
          </div>
          <div class="services-item">
            <img src="/build/img/acondicionamiento-fisico.jpg" alt="">
            <div class="services-item-text">
              <h3 class="text-left highlighted-white-text">Acondicionamiento Físico</h3>
              <p>Ejercicio físico adaptado para mantener la fuerza, el equilibrio y la movilidad del adulto mayor, promoviendo una vida activa y saludable.</p>
            </div>
          </div>
      </div>
    </div>

  </section>

  <div class="serv-section container section">
    <div class="serv-desc">
      <h2 class="margin-title text-left highlighted-black-text">Comedor</h2>
      <p>En la Estancia de Vida de Nuestra Señora de Guadalupe, nuestro comedor está diseñado para ofrecer a los Adultos Mayores un espacio acogedor, limpio y agradable donde disfrutar de sus alimentos. Creemos que la hora de la comida es un momento especial, no solo para nutrir el cuerpo, sino también para fomentar la convivencia y el sentido de comunidad.</p>
      <br>
      <p>Contamos con un ambiente tranquilo y ordenado, ideal para compartir en compañía. Nuestros menús son cuidadosamente elaborados por profesionales, tomando en cuenta las necesidades nutricionales propias de esta etapa de la vida, garantizando alimentos saludables, balanceados y preparados con dedicación.</p>
    </div>
    <img src="/build/img/comedor.jpg" alt="">
  </div>
  <div class="serv-section container section">
    <img src="/build/img/enfermeria.jpg" alt="">
    <div class="serv-desc">
      <h2 class="margin-title text-left highlighted-black-text">Asistencia Espiritual</h2>
      <p>En la Estancia de Vida de Nuestra Señora de Guadalupe, comprendemos la importancia de la dimensión espiritual en el bienestar integral de nuestros Adultos Mayores. Por ello, ofrecemos un servicio de asistencia espiritual que brinda acompañamiento, consuelo y fortaleza en un ambiente de respeto y libertad de creencias.</p>
      <br>
      <p>Contamos con espacios adecuados para la oración, la reflexión y actividades que nutren la fe. Nuestro objetivo es ofrecer un entorno en el que cada residente encuentre paz interior, motivación y esperanza, fomentando un sentido profundo de tranquilidad y plenitud en esta etapa de la vida.</p>
    </div>
  </div>
</main>

<?php
  includeTemplate('footer');
?>