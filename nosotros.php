<?php
  require 'includes/funciones.php';
  includeTemplate('header', $hero = false, $hero_image = true, $hero_image_name = 'hero-nosotros.jpg');
?>

<main>
  <section class="goal-section container section">
    <p class="text-left large-text">
      <span>Nuestro objetivo es brindar al Adulto Mayor</span>
      <span>y a sus familias un lugar especializado en</span>
      <span>la atención y el cuidado, ofreciendo el</span>
      <span>servicio de Centro de día o de Residencia</span>
      <span>a los Adultos Mayores.</span>
    </p>
  </section>

  <section class="container section">
    <h2 class="margin-title text-left highlighted-black-text">¿Quiénes Somos?</h2>
    <div class="about-us-section">
      <img src="/build/img/about-us-image.jpg" alt="">
      <div class="about-us-text">
        <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed massa nibh, ornare quis justo vitae, luctus tincidunt enim. Curabitur tortor lectus, semper et venenatis hendrerit, varius at massa. Sed laoreet sit amet elit id sollicitudin. Mauris porta eros in lacus venenatis, consectetur consectetur sapien semper. Sed lacus velit, consequat non mattis non, euismod ut nisl. Donec in nisl eu lectus suscipit ullamcorper. Quisque eu sollicitudin turpis. Morbi turpis augue, pharetra vel erat ut, consectetur ornare magna. Vestibulum et turpis posuere, porttitor urna eu, auctor lectus. Ut ultrices euismod faucibus.</p>
        <br>
        <p>Pellentesque vel ex nec ex interdum scelerisque. Nam dignissim tincidunt est volutpat dictum. Nam eu dolor interdum, consectetur risus at, vehicula neque. Integer consectetur risus turpis. Nulla auctor ante et augue maximus, ut gravida neque eleifend. Aenean vitae semper neque. Curabitur non magna sit amet massa sollicitudin maximus.
        </p>
      </div>
    </div>
  </section>

  <section class="container section">
    <h2 class="margin-title text-left highlighted-black-text">Nuestros Datos nos Respaldan</h2>
    <div class="facts-section">
      <div class="facts-item">
        <p class="facts-text">Años de Experiencia</p>
        <p class="facts-number text-left">+20</p>
      </div>
      <div class="facts-item">
        <p class="facts-text">Personal Calificado</p>
        <p class="facts-number text-left">+20</p>
      </div>
      <div class="facts-item">
        <p class="facts-text">Servicios</p>
        <p class="facts-number text-left">+8</p>
      </div>
    </div>
  </section>
  
  <section class="container section">
    <div class="mission-vission-section"> 
      <div class="mission-vission-section-text">
        <div>
          <h2 class="margin-title text-left highlighted-black-text">Misión</h2>
          <p>Ofrecer servicios profesionales de atención gerontológica de calidad, estimulando el mantenimiento físico y mental de los Adultos Mayores que ingresan, esto, por medio de un servicio integral, profesional y cálido en espacios adecuados para la realización de distintas actividades que fomentan la participación y convivencia.</p>
        </div>
        <div>
          <h2 class="margin-title text-left highlighted-black-text">Visión</h2>
          <p>Ser referente en el cuidado de Adultos Mayores, como una institución de confianza que brinda los mejores servicios en modalidad de Centro de Día y Residencia.</p>
        </div>
      </div>
      <img src="/build/img/mission.jpg" alt=""> 
    </div>
  </section>

  <section class="ceo-message-section container section">
    <div class="ceo-message--text">
      <blockquote class="large-text">
        La vejez no es una carga, es una etapa llena de historias, aprendizajes y dignidad que merece ser vivida con respeto y amor.
      </blockquote>
      <p class="highlighted-black-text">- Eduardo Varela Valdés (Director General)</p>
    </div>
    <img src="/build/img/staff-ceo.jpg" alt="CEO de la Estancia">
  </section>


  <section class="container section">
    <h2 class="margin-title text-left highlighted-black-text">Nuestro Equipo</h2>
    <div class="team-section">
      <div class="team-list">
        <div class="team-item">
          <img src="/build/img/staff.jpg" alt="">
          <h4 class="text-left highlighted-black-text">Javier Sordo Madaleno</h4>
          <p>CEO</p>
        </div>
        <div class="team-item">
          <img src="/build/img/staff.jpg" alt="">
          <h4 class="text-left highlighted-black-text">Javier Sordo Madaleno</h4>
          <p>CEO</p>
        </div>
        <div class="team-item">
          <img src="/build/img/staff.jpg" alt="">
          <h4 class="text-left highlighted-black-text">Javier Sordo Madaleno</h4>
          <p>CEO</p>
        </div>
        <div class="team-item">
          <img src="/build/img/staff.jpg" alt="">
          <h4 class="text-left highlighted-black-text">Javier Sordo Madaleno</h4>
          <p>CEO</p>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
  includeTemplate('footer');
?>