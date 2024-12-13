<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
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

    // Consulta para seleccionar las citas del paciente actual
    $stmt = $pdo->prepare('
        SELECT u.curp, u.nombre, u.apellido_p, u.apellido_m, c.fecha_cita, c.hora_cita, c.sintomas, c.curp_pac
        FROM usuario u
        INNER JOIN cita c ON u.curp = c.curp_med
        WHERE c.curp_pac = :curp_pac
        AND (c.diagnostico = :diagnostico OR c.diagnostico IS NULL)
        AND (c.medicamentos = :medicamentos OR c.medicamentos IS NULL)
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
<h2>Mis Citas</h2>
<div class="overflow">
  <table>
    <thead>
      <tr>
        <th>CURP Médico</th>
        <th>Nombre Médico</th>
        <th>Apellido P Médico</th>
        <th>Apellido M Medico</th>
        <th>Fecha</th>
        <th>Hora Inicio</th>
        <th>Sintomas</th>
        <th>Estado de la Cita</th> <!-- Nueva columna para el estado de la cita -->
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if ($citas): ?>
        <?php foreach ($citas as $cita): ?>
        <?php
        $fecha_hora_cita = $cita['fecha_cita'] . ' ' . $cita['hora_cita'];
        $hora_cita = new DateTime($fecha_hora_cita);  // Hora exacta de la cita

        // Obtener la hora actual
        $hora_actual = new DateTime("America/Mexico_City");  // Hora actual del sistema

        $cita_vencida = ($hora_actual >= $hora_cita);  // Si la hora actual es mayor, la cita ya pasó
        $cita_vencida2 = ($hora_actual <= $hora_cita);  // Si la hora actual es menor, la cita está por venir
        ?>
        <tr>
            <td><?= htmlspecialchars($cita['curp']); ?></td>
            <td><?= htmlspecialchars($cita['nombre']); ?></td>
            <td><?= htmlspecialchars($cita['apellido_p']); ?></td>
            <td><?= htmlspecialchars($cita['apellido_m']); ?></td>
            <td><?= htmlspecialchars($cita['fecha_cita']); ?></td>
            <td><?= htmlspecialchars($cita['hora_cita']); ?></td>
            <td><?= htmlspecialchars($cita['sintomas']); ?></td>
            <td>
                <?php if ($cita_vencida): ?>
                    <span style="color: red; font-weight: bold;">Cita vencida, agende otra! </span>
                    <?php endif; ?>
                    <?php if ($cita_vencida2): ?>
                    <span style="color: green; font-weight: bold;">Cita validad!</span>
                    <?php endif; ?>
                    </td>
            <td>
                <a href="delete_cita.php?curp_pac=<?= urlencode($cita['curp_pac']); ?>&curp_med=<?= urlencode($cita['curp']); ?>" class="select-btn">Eliminar</a>
            </td>
        </tr>
    
    <?php endforeach; ?>

      <?php else: ?>
        <tr>
          <td colspan="8">No hay resultados disponibles.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
