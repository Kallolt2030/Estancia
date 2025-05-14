let lastScroll = 0;
const nav2 = document.querySelector('.nav-inferior');

window.addEventListener('scroll', () => {
  const currentScroll = window.pageYOffset;

  if (currentScroll > lastScroll) {
    nav2.classList.add('oculto'); // Oculta nav 2
  } else {
    nav2.classList.remove('oculto'); // Muestra nav 2
  }

  lastScroll = currentScroll;
});
