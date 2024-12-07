<?php
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

    // Consulta para seleccionar las citas del médico actual, donde diagnóstico y medicamentos son 'N/A'
      $stmt = $pdo->prepare('
        SELECT u.curp, u.nombre, u.apellido_p, u.apellido_m, c.fecha_cita, c.hora_cita
        FROM usuario u
        INNER JOIN cita c ON u.curp = c.curp_pac
        WHERE c.curp_med = :curp_med
        AND c.fecha_cita = :selectedDate
        AND (c.diagnostico = :diagnostico OR c.diagnostico IS NULL)
        AND (c.medicamentos = :medicamentos OR c.medicamentos IS NULL)
    ');

    $stmt->execute([
        'curp_med' => $curp_med,
        'selectedDate' => $selectedDate,
        'diagnostico' => 'N/A',
        'medicamentos' => 'N/A'
    ]);


    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener las citas: " . htmlspecialchars($e->getMessage());
    exit;
}
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
        <th>  </th>
        <th> </th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($patients): ?>
        <?php foreach ($patients as $patient): ?>
        <tr>
          <td><?= htmlspecialchars($patient['curp']); ?></td>
          <td><?= htmlspecialchars($patient['nombre']); ?></td>
          <td><?= htmlspecialchars($patient['apellido_p']); ?></td>
          <td><?= htmlspecialchars($patient['apellido_m']); ?></td>
          <td><?= htmlspecialchars($patient['fecha_cita']); ?></td>
          <td><?= htmlspecialchars($patient['hora_cita']); ?></td>
          <td>
                <a href="reporte_guardado.php?curp_pac=<?= urlencode($patient['curp']); ?>&nombre=<?= urlencode($patient['nombre']); ?>&apellido_p=<?= urlencode($patient['apellido_p']); ?>&apellido_m=<?= urlencode($patient['apellido_m']); ?>&fecha_cita=<?= urlencode($patient['fecha_cita']); ?>&hora_cita=<?= urlencode($patient['hora_cita']); ?>" class="select-btn">Seleccionar</a>
              </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7">No hay citas registradas para este médico con diagnóstico y medicamentos.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
