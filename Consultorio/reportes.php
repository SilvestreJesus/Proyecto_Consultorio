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

    // Consulta para obtener los detalles de las citas atendidas por el médico
    $stmt = $pdo->prepare('
        SELECT DISTINCT u.curp, u.nombre, u.apellido_p, u.apellido_m, c.fecha_cita, c.hora_cita, c.hora_fin, c.sintomas, c.diagnostico, c.medicamentos
        FROM usuario u
        INNER JOIN cita c ON u.curp = c.curp_pac
        WHERE c.curp_med = :curp_med
        AND c.diagnostico IS NOT NULL
        AND c.diagnostico != :diagnostico
        AND c.medicamentos IS NOT NULL
        AND c.medicamentos != :medicamentos
        ORDER BY c.fecha_cita DESC
    ');

    $stmt->execute([
        'curp_med' => $curp_med,
        'diagnostico' => 'N/A',
        'medicamentos' => 'N/A'
    ]);
    $patients_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al obtener los datos: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<?php include_once 'inc/datos_medico.php'; ?>
<link rel="stylesheet" href="assets/Css/reporte.css">
<style>
    select {
        padding: 8px;
        margin-right: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    button {
        background-color: #ff8b2c;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover {
        background-color: #e6731a;
        transform: scale(1.05);
    }

    button:active {
        background-color: #cc5d14;
        transform: scale(1);
    }

    label {
        font-weight: bold;
        margin-right: 5px;
    }

    div {
        margin-bottom: 20px;
    }
</style>

<h2>Reportes</h2>

<!-- Dropdown para nombres de pacientes -->
<div>
    <label for="nombre_doctor">Paciente:</label>
    <select id="nombre_doctor">
        <option value="">Selecciona un paciente...</option>
        <?php foreach ($patients_data as $data): ?>
            <option value="<?= htmlspecialchars($data['nombre']); ?>">
                <?= htmlspecialchars($data['nombre']); ?> <?= htmlspecialchars($data['apellido_p']); ?> <?= htmlspecialchars($data['apellido_m']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button id="filtrar_nombre">Filtrar por Paciente</button>
</div>

<!-- Dropdown para fechas -->
<div>
    <label for="fecha">Fecha:</label>
    <select id="fecha">
        <option value="">Selecciona una fecha...</option>
        <?php 
        $fechas = array_unique(array_column($patients_data, 'fecha_cita'));
        foreach ($fechas as $fecha): ?>
            <option value="<?= htmlspecialchars($fecha); ?>">
                <?= htmlspecialchars($fecha); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button id="filtrar_fecha">Filtrar por Fecha</button>
</div>

<div class="overflow" id="table">
  <table>
    <thead>
      <tr>
        <th>CURP</th>
        <th>Nombre</th>
        <th>Apellido Paterno</th>
        <th>Apellido Materno</th>
        <th>Fecha</th>
        <th>Hora Inicio</th>
        <th>Hora Fin</th>
        <th>Síntomas</th>
        <th>Diagnóstico</th>
        <th>Medicamentos</th>
      </tr>
    </thead>
    <tbody id="tabla_resultados">
        <?php if ($patients_data): ?>
        <?php foreach ($patients_data as $data): ?>
        <tr>
            <td><?= htmlspecialchars($data['curp']); ?></td>
            <td><?= htmlspecialchars($data['nombre']); ?></td>
            <td><?= htmlspecialchars($data['apellido_p']); ?></td>
            <td><?= htmlspecialchars($data['apellido_m']); ?></td>
            <td><?= htmlspecialchars($data['fecha_cita']); ?></td>
            <td><?= htmlspecialchars($data['hora_cita']); ?></td>
            <td><?= htmlspecialchars($data['hora_fin']); ?></td>
            <td><?= htmlspecialchars($data['sintomas']); ?></td>
            <td><?= htmlspecialchars($data['diagnostico']); ?></td>
            <td><?= htmlspecialchars($data['medicamentos']); ?></td>
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

<script>
    document.getElementById('filtrar_nombre').addEventListener('click', function() {
        const nombre = document.getElementById('nombre_doctor').value.toLowerCase();
        const filas = document.querySelectorAll('#tabla_resultados tr');

        filas.forEach(fila => {
            const nombreFila = fila.children[1].textContent.toLowerCase() + ' ' + fila.children[2].textContent.toLowerCase() + ' ' + fila.children[3].textContent.toLowerCase();
            fila.style.display = nombre && !nombreFila.includes(nombre) ? 'none' : '';
        });
    });

    document.getElementById('filtrar_fecha').addEventListener('click', function() {
        const fecha = document.getElementById('fecha').value;
        const filas = document.querySelectorAll('#tabla_resultados tr');

        filas.forEach(fila => {
            const fechaFila = fila.children[4].textContent;
            fila.style.display = fecha && fechaFila !== fecha ? 'none' : '';
        });
    });
</script>
