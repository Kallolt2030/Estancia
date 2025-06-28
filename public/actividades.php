<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/actividades.css">

<h2>Actividades</h2>

<div class="contenedor-principal">
  
<div class="carrusel">
  <div class="actividad">
    <img src="../assets/img/estiramiento.jpg" alt="Estiramiento">
    <h3>Estiramientos</h3>
    <button onclick="abrirModal('modal1')">Información</button>
  </div>

  <div class="actividad">
    <img src="../assets/img/juegos.jpg" alt="Juegos de mesa">
    <h3>Juegos de mesa</h3>
    <button center onclick="abrirModal('modal2')">Información</button>
  </div>

  <div class="actividad">
    <img src="../assets/img/dibujo.jpeg" alt="Dibujo">
    <h3>Dibujo</h3>
    <button onclick="abrirModal('modal3')">Información</button>
  </div>
</div>

<!-- Modal 1 -->
<div id="modal1" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal('modal1')">&times;</span>
    <h3>Estiramientos</h3>
    <p>Actividad suave para mejorar la movilidad, relajarse y prevenir lesiones.</p>
  </div>
</div>

<!-- Modal 2 -->
<div id="modal2" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal('modal2')">&times;</span>
    <h3>Juegos de mesa</h3>
    <p>Los juegos de mesa estimulan la mente, la socialización y la diversión entre los participantes.</p>
  </div>
</div>

<!-- Modal 3 -->
<div id="modal3" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal('modal3')">&times;</span>
    <h3>Dibujo</h3>
    <p>Actividad artística que fomenta la creatividad, relajación y la expresión personal.</p>
  </div>
</div>

</div>



<script>
function abrirModal(id) {
  document.getElementById(id).style.display = "flex";
}

function cerrarModal(id) {
  document.getElementById(id).style.display = "none";
}

window.onclick = function(event) {
  document.querySelectorAll('.modal').forEach(modal => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
};
</script>

<?php include '../includes/footer.php'; ?>


