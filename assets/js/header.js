  let prevScrollPos = window.pageYOffset;
  const header = document.querySelector('header');

  window.addEventListener('scroll', () => {
    const currentScrollPos = window.pageYOffset;

    if (prevScrollPos > currentScrollPos || currentScrollPos < 20) {
      header.classList.remove('oculto');
    } else {
      header.classList.add('oculto');
    }

    prevScrollPos = currentScrollPos;
  });

  document.addEventListener('mousemove', (e) => {
    if (e.clientY < 60) {
      header.classList.remove('oculto');
    }
  });

