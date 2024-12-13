<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'inc/db.php';
session_start();

// Obtener los valores de día, mes y año desde la URL
$selectedDay = isset($_GET['day']) ? $_GET['day'] : null;
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : null;
$selectedYear = isset($_GET['year']) ? $_GET['year'] : null;

// Validar que todos los parámetros de fecha estén presentes
if ($selectedDay && $selectedMonth && $selectedYear) {
    $selectedDate = "$selectedYear-$selectedMonth-$selectedDay"; // Formato YYYY-MM-DD
} else {
    echo "No se seleccionó una fecha válida.";
    exit;
}

// Verificar si el médico ha iniciado sesión
if (!isset($_SESSION['usuario']['curp']) || $_SESSION['usuario']['rol'] !== 'medico') {
    $_SESSION['error'] = "Debe iniciar sesión como médico para acceder.";
    header('Location: login.php');
    exit;
}

$curp_med = $_SESSION['usuario']['curp'];

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para seleccionar las citas del médico actual, ordenando por fecha y hora
    $stmt = $pdo->prepare('
        SELECT u.curp, u.nombre, u.apellido_p, u.apellido_m, c.fecha_cita, c.hora_cita
        FROM usuario u
        INNER JOIN cita c ON u.curp = c.curp_pac
        WHERE c.curp_med = :curp_med
        AND c.fecha_cita = :selectedDate
        AND (c.diagnostico = :diagnostico OR c.diagnostico IS NULL)
        AND (c.medicamentos = :medicamentos OR c.medicamentos IS NULL)
        ORDER BY c.fecha_cita DESC, c.hora_cita DESC
    ');

    $stmt->execute([
        'curp_med' => $curp_med,
        'selectedDate' => $selectedDate,
        'diagnostico' => 'N/A',
        'medicamentos' => 'N/A'
    ]);

    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener las citas: " . htmlspecialchars($e->getMessage());
    exit;
}

$currentTime = new DateTime("now", new DateTimeZone("America/Mexico_City"));  // Hora actual del sistema
?>

<?php include_once 'inc/datos_medico.php'; ?>
<link rel="stylesheet" href="assets/Css/atender.css">
<h2>Mis Pacientes y sus Citas</h2>

<div class="overflow">
  <table>
    <thead>
      <tr>
        <th>CURP</th>
        <th>Nombre</th>
        <th>Apellido Paterno</th>
        <th>Apellido Materno</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Estado de la Cita</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if ($citas): ?>
        <?php foreach ($citas as $cita): ?>
        <?php
        $fecha_hora_cita = $cita['fecha_cita'] . ' ' . $cita['hora_cita'];
        $hora_cita = new DateTime($fecha_hora_cita, new DateTimeZone("America/Mexico_City"));

        // Verificar si la cita ya pasó o está disponible
        $cita_vencida = ($currentTime > $hora_cita);
        ?>
        <tr>
          <td><?= htmlspecialchars($cita['curp']); ?></td>
          <td><?= htmlspecialchars($cita['nombre']); ?></td>
          <td><?= htmlspecialchars($cita['apellido_p']); ?></td>
          <td><?= htmlspecialchars($cita['apellido_m']); ?></td>
          <td><?= htmlspecialchars($cita['fecha_cita']); ?></td>
          <td><?= htmlspecialchars($cita['hora_cita']); ?></td>
          <td>
            <?php if ($cita_vencida): ?>
              <span style="color: red; font-weight: bold;">Cita vencida!</span>
            <?php else: ?>
              <span style="color: green; font-weight: bold;">Cita disponible!</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="reporte_guardado.php?curp_pac=<?= urlencode($cita['curp']); ?>&nombre=<?= urlencode($cita['nombre']); ?>&apellido_p=<?= urlencode($cita['apellido_p']); ?>&apellido_m=<?= urlencode($cita['apellido_m']); ?>&fecha_cita=<?= urlencode($cita['fecha_cita']); ?>&hora_cita=<?= urlencode($cita['hora_cita']); ?>" class="select-btn">Seleccionar</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="8">No hay citas registradas para este médico con diagnóstico y medicamentos.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
