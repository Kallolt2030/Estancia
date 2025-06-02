document.addEventListener('DOMContentLoaded', function() {
  // Carrusel automático
  const carrusel = document.querySelector('.carrusel-imagenes');
  const imagenes = document.querySelectorAll('.carrusel-imagen');
  let indice = 0;
  const totalImagenes = imagenes.length;
  
  function moverCarrusel() {
    indice = (indice + 1) % totalImagenes;
    const desplazamiento = -indice * 100;
    carrusel.style.transform = `translateX(${desplazamiento}%)`;
  }
  
  // Cambia la imagen cada 5 segundos
  setInterval(moverCarrusel, 5000);
  
  // Opcional: Pausar al hacer hover
  carrusel.addEventListener('mouseenter', function() {
    clearInterval(intervalo);
  });
  
  carrusel.addEventListener('mouseleave', function() {
    intervalo = setInterval(moverCarrusel, 5000);
  });
  
  let intervalo = setInterval(moverCarrusel, 5000);
});