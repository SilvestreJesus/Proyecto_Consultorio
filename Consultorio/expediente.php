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
$curp_pac = $_GET['curp_pac'] ?? ''; // Obtener el CURP del paciente desde la URL

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
<link rel="stylesheet" href="assets/Css/reporte.css">
<h2>Expediente del Paciente</h2>

<div class="overflow">
  <table>
    <thead>
      <tr>
        <th>CURP</th>
        <th>Nombre</th>
        <th>Apellido Paterno</th>
        <th>Apellido Materno</th>
        <th>Fecha de Cita</th>
        <th>Hora de Inicio</th>
        <th>Hora de Fin</th>
        <th>Sintomas</th>
        <th>Diagnóstico</th>
        <th>Medicamento</th>
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
            <td><?= htmlspecialchars($patient['hora_fin']); ?></td>
            <td><?= htmlspecialchars($patient['sintomas']); ?></td>
            <td><?= htmlspecialchars($patient['diagnostico']); ?></td>
            <td><?= htmlspecialchars($patient['medicamentos']); ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="10">No hay registros para este paciente.</td>
        </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
