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

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para seleccionar las citas del médico actual, donde diagnóstico y medicamentos son 'N/A'
    $stmt = $pdo->prepare('
        SELECT u.curp, u.nombre, u.apellido_p, u.apellido_m, c.fecha_cita, c.hora_cita, c.hora_fin, c.sintomas, c.diagnostico, c.medicamentos
        FROM usuario u
        INNER JOIN cita c ON u.curp = c.curp_pac
        WHERE c.curp_med = :curp_med
        AND c.diagnostico != :diagnostico
        AND c.diagnostico IS NOT NULL
        AND c.medicamentos != :medicamentos
        AND c.medicamentos IS NOT NULL
    ');
        
    $stmt->execute([
        'curp_med' => $curp_med,
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
<link rel="stylesheet" href="assets/Css/reporte.css">
<h2>Reportes</h2>

<div class="overflow">
  <table>
    <thead>
      <tr>
        <th>CURP</th>
        <th>Nombre</th>
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
            <td colspan="10">No hay reportes con diagnóstico y medicamentos completos.</td>
        </tr>
    <?php endif; ?>

    </tbody>
  </table>
</div>
