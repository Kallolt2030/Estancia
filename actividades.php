<?php
  require 'includes/funciones.php';
  includeTemplate('header');
?>

<h2 class="actividades-h2">Actividades</h2>

<div class="contenedor-principal">
  
<div class="carrusel">
  <div class="actividad">
    <img src="/build/img/estiramiento.jpg" alt="Estiramiento">
    <h3>Estiramientos</h3>
    <button onclick="abrirModal('modal1')">Información</button>
  </div>

  <div class="actividad">
    <img src="/build/img/juegos.jpg" alt="Juegos de mesa">
    <h3>Juegos de mesa</h3>
    <button center onclick="abrirModal('modal2')">Información</button>
  </div>

  <div class="actividad">
    <img src="/build/img/dibujo.jpeg" alt="Dibujo">
    <h3>Dibujo</h3>
    <button onclick="abrirModal('modal3')">Información</button>
  </div>
</div>

<!-- Modal 1 -->
<div id="modal1" class="actividades-modal">
  <div class="actividades-modal-content">
    <span class="close" onclick="cerrarModal('modal1')">&times;</span>
    <h3>Estiramientos</h3>
    <p>Actividad suave para mejorar la movilidad, relajarse y prevenir lesiones.</p>
  </div>
</div>

<!-- Modal 2 -->
<div id="modal2" class="actividades-modal">
  <div class="actividades-modal-content">
    <span class="close" onclick="cerrarModal('modal2')">&times;</span>
    <h3>Juegos de mesa</h3>
    <p>Los juegos de mesa estimulan la mente, la socialización y la diversión entre los participantes.</p>
  </div>
</div>

<!-- Modal 3 -->
<div id="modal3" class="actividades-modal">
  <div class="actividades-modal-content">
    <span class="close" onclick="cerrarModal('modal3')">&times;</span>
    <h3>Dibujo</h3>
    <p>Actividad artística que fomenta la creatividad, relajación y la expresión personal.</p>
  </div>
</div>

</div>

<?php
  includeTemplate('footer');
?>