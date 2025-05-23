<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/nosotros.css">
<!-- Añadir librería AOS para animaciones -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<div class="video">
  <div class="overlay">
    <div class="contenedor contenido-video">
      <h2 data-aos="fade-down" data-aos-duration="1000">Estancia de Vida Nuestra Señora de Guadalupe</h2>
      <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit amet dolore harum ea minima debitis corrupti blanditiis placeat! Distinctio perspiciatis necessitatibus eum inventore voluptates pariatur nesciunt fuga eligendi dolore in?</p>
    </div>
  </div>

  <video muted loop autoplay>
    <source src="../assets/video/29903-378294413_small.mp4" type="video/mp4">
  </video>
</div>

<main>
  
</main>
<section class="quienes-somos contenedor" data-aos="fade-up">
  <div class="quienes-somos__titulo">
    <h2>¿Quiénes Somos?</h2>
  </div>
  <div class="quienes-somos__texto">
    <p>
      En Estancia de Vida Nuestra Señora de Guadalupe, nos dedicamos con amor y compromiso al bienestar integral de las personas de la tercera edad. Somos un equipo de profesionales especializados que entiende la importancia de brindar no solo cuidados médicos y atención diaria, sino también un ambiente cálido, seguro y digno.
      
      Nuestra estancia fue creada con la firme convicción de que en la etapa adulta mayor se debe vivir con calidad, respeto y alegría. Ofrecemos un espacio diseñado para fomentar la tranquilidad, la convivencia y la atención personalizada, donde cada residente es tratado como parte de nuestra familia.

      Con instalaciones cómodas, programas de actividades recreativas, atención médica y emocional, así como un equipo humano comprometido, buscamos ser un verdadero hogar para quienes nos confían el cuidado de sus seres queridos.
    </p>
  </div>
</section>

<section class="historia contenedor">
  <div class="historia__imagen" data-aos="fade-right">
    <img src="../assets/img/historia.jpg" alt="Imagen de historia" class="imagen-con-sombra">
  </div>

  <div class="historia__texto" data-aos="fade-left">
    <h2>Nuestra Historia</h2>
    <p> 
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris condimentum maximus egestas. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in purus metus. Cras ut urna nibh. Fusce porttitor nibh ligula, ac euismod metus tempor cursus. Phasellus molestie, justo sed egestas sodales, leo nisi luctus libero, eu rhoncus libero est ullamcorper justo. Quisque at urna non mi sollicitudin pretium non vel erat. Donec tincidunt velit dolor, vitae eleifend eros mollis mattis. Duis quis arcu vitae odio ultricies dictum.
      Curabitur fringilla enim sed cursus mattis. Aliquam nec dignissim odio. Praesent suscipit augue metus, ut feugiat velit vulputate rutrum. Cras rhoncus egestas imperdiet. Nulla ultricies laoreet maximus. Nunc eu ligula sed purus fermentum facilisis sit amet nec ipsum. Curabitur aliquet, lectus nec aliquet elementum, enim arcu posuere lorem, et posuere libero felis non mauris. Aliquam interdum orci nisl, ac faucibus dolor imperdiet sagittis. Donec non elementum nisl.
      
      Aliquam sit amet orci molestie, efficitur nisi eleifend, facilisis ante. Nulla dapibus sapien nec enim pellentesque, ac facilisis turpis posuere. Nulla vehicula erat a molestie porttitor. Mauris quis lectus a arcu vehicula sollicitudin. Donec ultricies lobortis arcu vitae eleifend. Ut at orci vitae orci tincidunt tristique in quis felis. Aliquam sit amet justo interdum, imperdiet ante et, vulputate mi. Etiam nulla nibh, consectetur id dolor a, gravida gravida elit. Donec eu elit et leo volutpat bibendum eget eget sapien
    </p>
  </div>
</section>

<div class="valores contenedor" data-aos="zoom-in">
  <h2>Nuestros Valores</h2>
</div>

<!-- Inicializar AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true
  });
</script>

<?php include '../includes/footer.php'; ?>