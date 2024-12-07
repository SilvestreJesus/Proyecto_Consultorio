<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bienestar Médico</title>
    <link rel="icon" type="image/x-icon" href="inc/Imagenes/logo.png" />
    <link rel="stylesheet" href="assets/Css/citas_agendadas.css">
  </head>
  <body>
    <header>
      <div class="background">
        <img src="inc/Imagenes/inicio.png" alt="Background">
      </div>
      <nav id="menu">
        <a href="medico_index.php">
          <img src="inc/Imagenes/casa.jpeg" class="entrar-icon">
        </a>
      </nav>
    </header>

    <main class="container">
    <h1 >Mi Agenda</h1>
      <h1 id="calendar-title"></h1>
      <div class="calendar-nav">
        <button id="prev-month">Anterior</button>
        <button id="next-month">Siguiente</button>
      </div>
      <div id="calendar"></div>
    </main>

    <!-- JS -->
    <script src="assets/Js/citas_agendadas.js"></script>
  </body>
</html>
