const menu = document.getElementById('menu');
const overlay = document.getElementById('menu-overlay');
const closeBtn = document.getElementById('closeBtn');
const musicButton = document.getElementById('musicButton');
const audio = document.getElementById('backgroundMusic');

let isPlaying = false;

// Menú para Teléfono y Tableta
menu.addEventListener('click', () => {
  overlay.style.display = 'flex';
});
closeBtn.addEventListener('click', () => {
  overlay.style.display = 'none';
})

// Widget Activar / Desactivar Música
musicButton.addEventListener('click', () => {
  if (isPlaying) {
    audio.pause();
    musicButton.classList.remove('active');
  } else {
    audio.play();
    musicButton.classList.add('active');
  }
  isPlaying = !isPlaying;
});


// NUEVO
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