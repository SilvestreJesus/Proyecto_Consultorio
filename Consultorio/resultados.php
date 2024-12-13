<?php
require_once 'inc/db.php';
session_start();

// Verificar si el paciente ha iniciado sesión
if (!isset($_SESSION['usuario']['curp']) || $_SESSION['usuario']['rol'] !== 'paciente') {
    $_SESSION['error'] = "Debe iniciar sesión como paciente para acceder.";
    header('Location: login.php');
    exit;
}

// Obtener el CURP del paciente de la sesión
$curp_pac = $_SESSION['usuario']['curp'];

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para seleccionar las citas del paciente actual, donde diagnóstico y medicamentos están completos
    $stmt = $pdo->prepare('
        SELECT u.curp, u.nombre, u.apellido_p, u.apellido_m, c.fecha_cita, c.hora_cita, c.hora_fin, c.sintomas, c.diagnostico, c.medicamentos
        FROM usuario u
        INNER JOIN cita c ON u.curp = c.curp_med
        WHERE c.curp_pac = :curp_pac
        AND c.diagnostico != :diagnostico
        AND c.diagnostico IS NOT NULL
        AND c.medicamentos != :medicamentos
        AND c.medicamentos IS NOT NULL
        ORDER BY c.fecha_cita DESC, c.hora_cita DESC
    ');

    $stmt->execute([
        'curp_pac' => $curp_pac,
        'diagnostico' => 'N/A',
        'medicamentos' => 'N/A'
    ]);

    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al obtener las citas: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<?php include_once 'inc/datos_paciente.php'; ?>
<link rel="stylesheet" href="assets/Css/resultados.css">
<h2>Mis Resultados</h2>

<div class="overflow">
  <table>
    <thead>
      <tr>
        <th>CURP Médico</th>
        <th>Nombre Médico</th>
        <th> </th>
        <th> </th>
        <th>Fecha</th>
        <th>Hora Inicio</th>
        <th>Hora Fin</th>
        <th>Sintomas</th>
        <th>Diagnostico</th>
        <th>Medicamento</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($citas): ?>
        <?php foreach ($citas as $cita): ?>
        <tr>
          <td><?= htmlspecialchars($cita['curp']); ?></td>
          <td><?= htmlspecialchars($cita['nombre']); ?></td>
          <td><?= htmlspecialchars($cita['apellido_p']); ?></td>
          <td><?= htmlspecialchars($cita['apellido_m']); ?></td>
          <td><?= htmlspecialchars($cita['fecha_cita']); ?></td>
          <td><?= htmlspecialchars($cita['hora_cita']); ?></td>
          <td><?= htmlspecialchars($cita['hora_fin']); ?></td>
          <td><?= htmlspecialchars($cita['sintomas']); ?></td>
          <td><?= htmlspecialchars($cita['diagnostico']); ?></td>
          <td><?= htmlspecialchars($cita['medicamentos']); ?></td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="10">No hay resultados disponibles.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
