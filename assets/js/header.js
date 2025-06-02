const botonMenu = document.querySelector('.menu-toggle');
const overlayMenu = document.getElementById('menu');
const cerrarMenu = document.querySelector('.cerrar-menu');
 
botonMenu.addEventListener('click', () => {
  overlayMenu.classList.add('abierto');
});
 
cerrarMenu.addEventListener('click', () => {
  overlayMenu.classList.remove('abierto');
});
 