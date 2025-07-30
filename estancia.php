<?php
  require 'includes/funciones.php';
  includeTemplate('header', $hero = false, $hero_image = true, $hero_image_name = 'hero-estancia.jpg');
?>

<main>
  <section class="dark-background">
    <div class="info-estancia container section">
      <div class="info-estancia-item">
        <h3 class="margin-title text-left highlighted-white-text">Ubicación</h3>
        <p>Contamos con una excelente ubicación en la zona centrica de la ciuda de Aguascalientes.</p>
      </div>
      <div class="info-estancia-item">
        <h3 class="margin-title text-left highlighted-white-text">Instalaciones</h3>
        <p>Nuestra estancia cuenta con capilla, comedor, salas de entretenimiento y áreas verdes.</p>
      </div>
      <div class="info-estancia-item">
        <h3 class="margin-title text-left highlighted-white-text">Habitaciones</h3>
        <p>Nuestras habitaciones se caracterizan por la seguridad y el confort en beneficio del Adulto Mayor.</p>
      </div>
    </div>
  </section>

  <section class="where-section container section">
    <h2 class="title-margin highlighted-black-text">¿Donde se encuentra Estancia de Vida?</h2>
    <p>La Estancia de Vida de Nuestra Señora de Guadalupe goza de una ubicación privilegiada en el corazón de la ciudad de Aguascalientes, específicamente dentro de la tradicional colonia El Llanito. Su localización estratégica permite estar cerca de los servicios esenciales de la ciudad, pero al mismo tiempo, se encuentra apartada del bullicio de las avenidas principales, ofreciendo un entorno de paz y seguridad.Este equilibrio convierte a nuestra estancia en un lugar accesible, sereno y acogedor, ideal para el bienestar de nuestros Adultos Mayores. Aquí, cada espacio ha sido pensado para brindar comodidad, tranquilidad y un ambiente que favorezca la convivencia, la atención personalizada y la calidad de vida que nuestros residentes merecen.</p>
  </section>

  <section class="container section">
    <h2 class="title-margin text-left highlighted-black-text">Habitaciones</h2>
    <div class="rooms-section">
      <img src="/build/img/room.jpg" alt="Hábitacion">
      <div class="rooms-section-text">
        <p>En la Estancia de Vida de Nuestra Señora de Guadalupe, nuestras habitaciones han sido diseñadas cuidadosamente para ofrecer a nuestros Adultos Mayores un entorno cómodo, seguro y tranquilo. Cada espacio está pensado para brindar una sensación de hogar, con áreas limpias, bien iluminadas y equipadas con todo lo necesario para garantizar su bienestar. Disponemos de habitaciones con mobiliario funcional, camas ergonómicas y detalles que promueven la autonomía y la comodidad. Además, cuentan con sistemas de ventilación adecuados, accesos adaptados y supervisión constante para mayor tranquilidad de los residentes y sus familias.</p>
        <br>        
        <p>En nuestra estancia, creemos que un espacio digno y confortable contribuye al bienestar físico y emocional de cada persona. Por ello, nuestras habitaciones son más que un lugar para descansar: son espacios pensados para vivir con calidad, respeto y calidez.</p>
      </div>
    </div>
  </section>
  
  <div class="container section">
    <div class="entertainment-section mb-1">
      <h2 class="margin-title text-left highlighted-black-text">Sala de Entretenimiento</h2>
      <p>En la Estancia de Vida de Nuestra Señora de Guadalupe, contamos con una sala de entretenimiento especialmente diseñada para fomentar la recreación, la convivencia y el disfrute de nuestros Adultos Mayores.</p>
      
      <p>Nuestra sala está equipada con televisión, música y áreas cómodas para reuniones y actividades grupales. Aquí, organizamos sesiones de cine y eventos que promueven la interacción y el compañerismo, fortaleciendo el sentido de comunidad entre nuestros residentes. Creemos firmemente que la diversión y la socialización son esenciales para una vida plena. Por ello, nuestra sala de entretenimiento no solo brinda esparcimiento, sino que también contribuye al bienestar emocional y mental de quienes forman parte de nuestra estancia.</p>
    </div>
    <div class="entertainment-photos">
      <img src="/build/img/entretenimiento-0.jpg" alt="">
      <img src="/build/img/entretenimiento-1.jpg" alt="">
    </div>
  </div>

  <div class="container section">
    <div class="ubication-section">
      <div class="ubication-section-desc">
        <h2 class="title-margin highlighted-black-text">Nuestra Ubicación</h2>
        <p>Estado: Aguascalientes</p>
        <p>Colonia: El Llanito</p>
        <p>Código Postal: 20240</p>
        <p>Calle: La Soledad #207</p>
      </div>
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3702.4909327868395!2d-102.2903035253468!3d21.877163258100694!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8429ee7734b2b4f5%3A0x525d90880241f87e!2sESTANCIA%20DE%20VIDA%20NUESTRA%20SE%C3%91ORA%20DE%20GUADALUPE%20A.C.!5e0!3m2!1ses!2smx!4v1753851636215!5m2!1ses!2smx" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</main>

<?php
  includeTemplate('footer');
?>