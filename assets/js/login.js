document.addEventListener('DOMContentLoaded', () => {
    const doorContainer = document.getElementById('doorContainer');
    const form = document.querySelector('.login-form');
    
    // Pequeño retraso para iniciar animación
    setTimeout(() => {
        doorContainer.classList.add('doors-open');
    }, 300);
    
    // Animación al enviar formulario
    if(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Animación de cierre
            doorContainer.classList.remove('doors-open');
            
            // Pequeño retraso antes de enviar
            setTimeout(() => {
                this.submit();
            }, 1000);
        });
    }
});