<?php
require_once 'inc/db.php';
session_start();

// Verificar si el médico ha iniciado sesión
if (!isset($_SESSION['usuario']['curp']) || $_SESSION['usuario']['rol'] !== 'medico') {
    $_SESSION['error'] = "Debe iniciar sesión como médico para acceder.";
    header('Location: login.php');
    exit;
}

$curp_med = $_SESSION['usuario']['curp'];
$curp_pac = $_GET['curp_pac'] ?? ''; 
$nom_pac = $_GET['nombre'] ?? '';
$apellidop = $_GET['apellido_p'] ?? ''; 
$apellidom = $_GET['apellido_m'] ?? ''; 

$fecha = $_GET['fecha_cita'] ?? ''; 
$hora_inicio = $_GET['hora_cita'] ?? ''; 
$hora_final = $_GET['hora_fin'] ?? ''; 
$sintoma = $_GET['sintomas'] ?? ''; 
$diagnostico = $_GET['diagnostico'] ?? ''; 
$medicamento = $_GET['medicamentos'] ?? ''; 

if (!$curp_pac) {
    echo "No se proporcionó el CURP del paciente.";
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener todos los detalles del paciente con el CURP proporcionado,
    // excluyendo registros con 'N/A' en diagnostico o medicamentos
    $stmt = $pdo->prepare('
        SELECT u.curp, u.nombre, u.apellido_p, u.apellido_m, 
               c.fecha_cita, c.hora_cita, c.hora_fin, c.sintomas, 
               c.diagnostico, c.medicamentos
        FROM usuario u
        INNER JOIN cita c ON u.curp = c.curp_pac
        WHERE u.curp = :curp_pac 
          AND c.diagnostico != :na_value
          AND c.medicamentos != :na_value
        ORDER BY c.fecha_cita DESC, c.hora_cita DESC
    ');

    $stmt->execute(['curp_pac' => $curp_pac, 'na_value' => 'N/A']);

    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al obtener los datos del paciente: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<?php include_once 'inc/datos_medico.php'; ?>

<style>
/* Reseteo de márgenes, rellenos y establecer box-sizing */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
    top: 30px;
}

/* Estilo del cuerpo */
body {
    margin: 0;
    padding: 20px;
}

/* Contenedor principal con fondo y sombra */
.container {
    max-width: 1400px;
    margin: auto;
    background: rgba(255, 255, 255, 0.8);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    position: relative;
}

/* Estilo de título */
h2 {
    text-align: center;
    color: #ff8b2c;
    margin-bottom: 20px;
}

/* Estilo de los datos */
.user-data {
    background: rgba(255, 255, 255, 0.7);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

/* Estilo de cada dato individual */
.data-item {
    margin: 20px 0;
    display: flex;
    justify-content: space-between;
    font-size: 16px;
}

/* Estilo para las etiquetas */
.data-item label {
    font-weight: bold;
}

/* Fondo de la página */
.background img {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: -1;
    filter: brightness(0.6);
}

/* Estilo del botón de seleccionar */
.select-btn {
    font-weight: bold;
    color: #fff;
    background-color: #ff8b2c;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s ease;
}

.select-btn:hover {
    background-color: #ca9f28;
    text-decoration: none;
}

/* Icono circular */
.entrar-icon {
    width: 35px;
    height: 35px;
    margin-right: 8px;
    vertical-align: middle;
    clip-path: circle(50% at 50% 50%);
}

/* Estilo del contenedor del icono */
.icon-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    margin-bottom: 20px;
}

/* Estilo del icono */
.icon {
    width: 55px;
    height: 55px;
    clip-path: circle(50% at 50% 50%);
}

/* Estilo de las filas de la tabla */
.overflow table {
    width: 100%;
    border-collapse: collapse;
}

.overflow table th, .overflow table td {
    padding: 12px 15px;
    text-align: left;
}

.overflow table th {
    background-color: #f8f8f8;
    font-weight: bold;
}

.services {
    padding-top: 40px;
}

.services h1 {
    text-align: center;
    font-size: 2rem;
    color: #ff8b2c;
    margin-bottom: 50px;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(1000px, 2fr));
    gap: 30px;
}

.service-card {
    background: #fff;
    box-shadow: 0 4px 40px #000000;
    overflow: hidden;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.service-card:hover {
    transform: translateY(-10px);
}

.service-info {
    padding: 15px;
    text-align: center;
}

/* Estilo para la fila de fecha y horas en una sola línea */
.row {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 0px;
    margin-bottom: 10px;
}

/* Ajustar el espaciado y apariencia de los elementos dentro de la fila */
.row h3 {
    margin: 0 10px 0 0;
    font-size: 1rem;
    color: #ff8b2c;
}

.row p {
    margin: 0 15px;
    font-size: 1rem;
    color: #080808;
}

.row2 {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 0px;
    margin-bottom: 20px;
}

/* Estilo general del contenido restante */
.service-info h3 {
    font-size: 1.2rem;
    color: #ff8b2c;
    margin-bottom: 5px;
}

.service-info p {
    font-size: 1rem;
    color: #080808;
    margin-bottom: 10px;
}
</style>

<h2>Expediente del Paciente</h2>

<!-- Datos del Paciente -->
<p style="text-align:center; letter-spacing: 0.1em;">
    <strong>Nombre:</strong> 
    <span><?= htmlspecialchars($nom_pac); ?></span> 
    <span><?= htmlspecialchars($apellidop); ?></span> 
    <span><?= htmlspecialchars($apellidom); ?></span>
</p>
<p style="text-align:center;">
    <strong>CURP:</strong> <?= htmlspecialchars($curp_pac); ?>
</p>

<div class="overflow">
  <?php if ($patients): ?>
    <?php foreach ($patients as $patient): ?>
      <br><br><br>
      <hr>
      <section id="services" class="services">
        <div class="services-grid">
          <div class="service-card">
            <div class="service-info">
              <div class="row">
                <h3>Fecha:</h3> <p><?= htmlspecialchars($patient['fecha_cita']); ?></p>
                <h3>Hora Inicio:</h3> <p><?= htmlspecialchars($patient['hora_cita']); ?></p>
                <h3>Hora Final:</h3> <p><?= htmlspecialchars($patient['hora_fin']); ?></p>
              </div>
              <div class="row2">
                <h3>Síntoma:</h3> <p style="display: inline;"><?= htmlspecialchars($patient['sintomas']); ?></p>
              </div>
              <div class="row2">
              <h3>Diagnóstico:</h3>
              <p><?= htmlspecialchars($patient['diagnostico']); ?></p>
              </div>
              <div class="row2">
              <h3>Medicamento:</h3>
              <p><?= htmlspecialchars($patient['medicamentos']); ?></p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <hr> <!-- Separador entre citas -->
    <?php endforeach; ?>
  <?php else: ?>
    <p>No hay citas disponibles para este paciente.</p>
  <?php endif; ?>
</div>