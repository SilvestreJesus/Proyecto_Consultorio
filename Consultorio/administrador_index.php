<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienestar Médico</title>  
    <link rel="icon" type="image/x-icon" href="inc/Imagenes/logo.png" />
    <link rel="stylesheet" href="assets/Css/index.css">
</head>
<body>
    <!-- Encabezado -->
    <header class="header">
        <div class="logo">Bienestar Médico </div>
        <nav class="nav">
            <div class="nav-links">
                <a href="#home">Inicio</a>
                <a href="#services">Servicios</a>
                <a href="#us">Nosotros</a>
                <a href="#reseñas">Reseñas</a>
                <a href="#contact">Contacto</a>
                <a href="registrar_medico.php"  class="entrar-link"> <img src="inc/Imagenes/icono.png" alt="Icono" class="entrar-icon" > Registrar Medico
                <a href="logout.php">Salir</a>
            </a>
                
            </div>
        </nav>
    </header>

    
    <main class="container">
    <!-- Fondo de video -->
    <section id="home" class="background">
        <video class="videoBackground" autoplay muted loop playsinline>
            <source src="inc/Videos/fondo.mp4" type="video/mp4">
            Tu navegador no soporta videos HTML5.
        </video>
        <div class="background-contenido">
            <h1>Bienvenidos a <span>Bienestar Médico</span></h1>
            <p style="color: #ff792c;">Tu salud médica es nuestra prioridad</p>
        </div>
    </section>



   <!-- Sección Servicios -->
   <section id="services" class="services">
    
    <h1>Nuestros Servicios</h1>
    <div class="services-grid">
        <!-- Card 1 -->
        <div class="service-card">
            <img src="inc/Imagenes/chequeos.jpeg" alt="Chequeos Generales">
            <div class="service-info">
                <h3>01. Chequeos Generales</h3>
                <p>Realizamos una evaluación completa de tu salud, garantizando la detección temprana de cualquier posible afección.</p>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="service-card">
            <img src="inc/Imagenes/especializadas.jpeg" alt="Consultas Especializadas">
            <div class="service-info">
                <h3>02. Consultas Especializadas</h3>
                <p>Nuestros médicos especialistas están disponibles para ofrecer diagnósticos y tratamientos personalizados.</p>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="service-card">
            <img src="inc/Imagenes/vacunacion.png" alt="Servicios de Vacunación">
            <div class="service-info">
                <h3>03. Servicios de Vacunación</h3>
                <p>Ofrecemos una amplia gama de vacunas para protegerte y prevenir enfermedades.</p>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="service-card">
            <img src="inc/Imagenes/heridas.jpeg" alt="Atención de Heridas y Suturas">
            <div class="service-info">
                <h3>04. Atención de Heridas y Suturas</h3>
                <p>Brindamos atención profesional para lesiones menores, incluyendo limpieza y sutura.</p>
            </div>
        </div>
        <!-- Card 5 -->
        <div class="service-card">
            <img src="inc/Imagenes/cronica.jpeg" alt="Manejo de Enfermedades Crónicas">
            <div class="service-info">
                <h3>05. Manejo de Enfermedades Crónicas</h3>
                <p>Contamos con un enfoque integral para el manejo de enfermedades crónicas como la diabetes y la hipertensión.</p>
            </div>
        </div>
        <!-- Card 6 -->
        <div class="service-card">
            <img src="inc/Imagenes/diagnostico.jpeg" alt="Pruebas Diagnósticas">
            <div class="service-info">
                <h3>06. Pruebas Diagnósticas</h3>
                <p>Realizamos pruebas rápidas y eficientes como análisis de glucosa, colesterol y otras pruebas de rutina.</p>
            </div>
        </div>
    </div>
</section>


<!-- Sección Nosotros -->
<section id="us" class="about">
    <h1>Nosotros</h1>
    <div class="about-container">
        <!-- Imagen en la izquierda -->
        <div class="about-image">
            <img src="inc/Imagenes/doctor.png" alt="Dr. Jane Smith">
        </div>
        <!-- Información en la derecha -->
        <div class="about-text">
            <h3>Conoce al Dr. Jesús</h3>
            <p><strong>Médico General y Especialista en Cuidado Integral.</strong></p>
            <p>El bienestar del paciente es nuestra prioridad. Aquí, el paciente será escuchado, entendido y cuidado con dedicación y respeto. Pero, cuando el río de la salud fluye con calma, la vida encuentra su equilibrio.

            </p>
            <p>
                En este espacio, no solo tratamos enfermedades, cultivamos confianza. Una consulta no es solo un momento, es un puente hacia una vida más plena. Caminamos juntos hacia días más saludables, dejando atrás las sombras del malestar.
                
                Porque en cada visita, el latido de tu salud se convierte en nuestra misión.
                <p><strong>(+52) 481 123 8978</strong></p>
        </div>
    </div>
</section>


<section id="reseñas" class="reviews-section">
    <h1>Reseñas</h1>
    <p class="subtitle">Pacientes que tomaron nuestros servicios médicos</p>
    <div class="reviews-container">
        <div class="review-card">
            <h2>¡El es el mejor!</h2>
            <p>El Dr. Jesús es la mejor médico. Recomiendo al  Dr. Jesús para sus tipos de problemas. ¡Mucha suerte!
            </p>
            <blockquote>❝</blockquote>
            <div class="author">
                <img src="inc/Imagenes/persona.png" alt="Mitty Terra">
                <p>Por Mitty Terra</p>
            </div>
        </div>
        <div class="review-card">
            <h2>¡El mejor médico!</h2>
            <p>El Dr. Jesús es la mejor médico. Recomiendo al  Dr. Jesús para sus tipos de problemas. ¡Mucha suerte!
            </p>
            <blockquote>❝</blockquote>
            <div class="author">
                <img src="inc/Imagenes/persona.png" alt="Samuel">
                <p>Por Samuel</p>
            </div>
        </div>
        <div class="review-card">
            <h2>¡Gran Médico!</h2>
            <p> El Dr. Jesús es un gran doctor. Me atendio con  delicadeza y me ayudó a eliminar el dolor de cabeza.</p>
            <blockquote>❝</blockquote>
            <div class="author">
                <img src="inc/Imagenes/persona.png" alt="Linda">
                <p>Por Linda</p>
            </div>
        </div>
        <div class="review-card">
            <h2>¡Recomendado!</h2>
            <p>El Dr. Jesús es el mejor médico. La recomiendo para todo tipo de problemas. ¡Mucha suerte!</p>
            <blockquote>❝</blockquote>
            <div class="author">
                <img src="inc/Imagenes/persona.png" alt="Sandy Mave">
                <p>Por Sandy Mave</p>
            </div>
        </div>
    </div>
</section>



    <!-- Sección Contacto -->

    <section id="contact" class="contact">
        <div class="servicio-contact">
          <h1>¡Contáctanos a continuación!</h1>
          <p>Consulte con nosotros cualquier duda sobre su salud y bienestar general.</p>
          <div class="tarjeta-contact">
            <div class="tarjeta">
              <div class="icon">📞</div>
              <h3>Llámanos:</h3>
              <p>(+52) 481 123 8978</p>
            </div>
            <div class="tarjeta">
              <div class="icon">📧</div>
              <h3>Envíenos un correo electrónico:</h3>
              <p>bienestar.medico2020@gmail.com</p>
            </div>
          </div>
        </div>
        
      </section>
      <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3699.4047510812!2d-99.01412232556626!3d21.995797279906615!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d612a9f568d847%3A0xb0db6edee561bf47!2sIMSS-%20Unidad%20de%20Medicina%20Familiar%203!5e0!3m2!1ses!2smx!4v1732426301287!5m2!1ses!2smx" width="900" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
        </main>
    <footer>

      <p>&copy; 2024 - Todos los derechos reservados</p>
    </footer>

</body>
</html>
