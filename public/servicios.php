<?php include '../includes/header.php'; ?>
<section class="todos-servicios">
    <link rel="stylesheet" href="../assets/css/servicios.css">
    <div class="contenedor-servicios">
        <h2 class="titulo-general">Nuestros Servicios</h2>

        <div class="grid-servicios">
            <div class="tarjeta-servicio">
                <img src="../assets/img/rehabilitacion-1.jpg" alt="Rehabilitación Física">
                <h3>Rehabilitación Física</h3>
                <p>Mejoramos la movilidad, reduciendo el dolor y fortaleciendo la autonomía de nuestros residentes.</p>
                <button class="boton-servicio" data-id="rehabilitacion">Ver más</button>
            </div>

            <div class="tarjeta-servicio">
                <img src="../assets/img/comedor-1.jpg" alt="Servicio de Comedor">
                <h3>Comedor</h3>
                <p>Desayuno, comida y colaciones saludables para una dieta balanceada.</p>
                <button class="boton-servicio" data-id="comedor">Ver más</button>
            </div>

            <div class="tarjeta-servicio">
                <img src="../assets/img/enfermeria-1.jpg" alt="Servicio de Enfermería">
                <h3>Enfermería</h3>
                <p>Atención 24 hrs: presión, temperatura y cuidados generales.</p>
                <button class="boton-servicio" data-id="enfermeria">Ver más</button>
            </div>

            <div class="tarjeta-servicio">
                <img src="../assets/img/kinesiologia-1.jpg" alt="Servicio de Kinesiología">
                <h3>Kinesiología</h3>
                <p>Tratamientos diarios para mejorar movilidad y coordinación.</p>
                <button class="boton-servicio" data-id="kinesiologia">Ver más</button>
            </div>

            <div class="tarjeta-servicio">
                <img src="../assets/img/psicologia-1.jpg" alt="Servicio de Psicología">
                <h3>Psicología</h3>
                <p>Actividades cognitivas, atención emocional y socialización.</p>
                <button class="boton-servicio" data-id="psicologia">Ver más</button>
            </div>

            <div class="tarjeta-servicio">
                <img src="../assets/img/espiritual-1.jpg" alt="Asistencia Espiritual">
                <h3>Asistencia Espiritual</h3>
                <p>Rosario diario y misa semanal para bienestar espiritual.</p>
                <button class="boton-servicio" data-id="espiritual">Ver más</button>
            </div>
        </div>
    </div>


    <div id="modalServicio" class="modal">
        <div class="modal-contenido">
            <span class="cerrar-modal">&times;</span>
            <h2 id="modalTitulo"></h2>
            <p id="modalDescripcion"></p>
            <ul id="modalHorarios"></ul>
        </div>
    </div>
</section>

<script>
const servicios = {
    rehabilitacion: {
        titulo: "Rehabilitación Física",
        descripcion: "Mejoramos la movilidad, reducimos el dolor y fortalecemos la autonomía de nuestros residentes con planes personalizados.",
        horarios: ["Lunes a Viernes: 9:00 a.m. – 12:00 p.m.", "Sábados: 10:00 a.m. – 1:00 p.m."]
    },
    comedor: {
        titulo: "Comedor",
        descripcion: "Desayuno, comida y colaciones saludables preparadas para una dieta balanceada en adultos mayores.",
        horarios: ["Desayuno: 8:00 – 9:30 a.m.", "Comida: 1:00 – 2:30 p.m.", "Colación: 5:00 p.m."]
    },
    enfermeria: {
        titulo: "Enfermería",
        descripcion: "Cuidado 24 horas, incluyendo toma de presión, temperatura y apoyo diario al residente.",
        horarios: ["Disponible las 24 horas del día."]
    },
    kinesiologia: {
        titulo: "Kinesiología",
        descripcion: "Ejercicios grupales e individuales para mejorar el movimiento y coordinación en adultos mayores.",
        horarios: ["De lunes a viernes, sesiones por la mañana."]
    },
    psicologia: {
        titulo: "Psicología",
        descripcion: "Atención psicológica individual, estimulación cognitiva y actividades sociales con psicólogos y terapeutas.",
        horarios: ["Actividades diarias según programación."]
    },
    espiritual: {
        titulo: "Asistencia Espiritual",
        descripcion: "Espacios de fe y paz: Rosario diario y misa semanal para la espiritualidad de nuestros residentes.",
        horarios: ["Rosario: Diario a las 6:00 p.m.", "Misa: Domingo a las 10:00 a.m."]
    }
};

document.querySelectorAll('.boton-servicio').forEach(boton => {
    boton.addEventListener('click', () => {
        const id = boton.getAttribute('data-id');
        const servicio = servicios[id];

        document.getElementById('modalTitulo').textContent = servicio.titulo;
        document.getElementById('modalDescripcion').textContent = servicio.descripcion;

        const listaHorarios = document.getElementById('modalHorarios');
        listaHorarios.innerHTML = '';
        servicio.horarios.forEach(h => {
            const li = document.createElement('li');
            li.textContent = h;
            listaHorarios.appendChild(li);
        });

        document.getElementById('modalServicio').style.display = 'flex'; 
    });
});


document.querySelector('.cerrar-modal').addEventListener('click', () => {
    document.getElementById('modalServicio').style.display = 'none';
});

window.addEventListener('click', e => {
    if (e.target.id === 'modalServicio') {
        document.getElementById('modalServicio').style.display = 'none';
    }
});
</script>



<?php include '../includes/footer.php'; ?>
