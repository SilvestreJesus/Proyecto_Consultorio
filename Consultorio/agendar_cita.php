<?php
require_once 'inc/db.php';

// Verificar si se ha enviado una búsqueda
$especialidad = isset($_GET['especialidad']) && trim($_GET['especialidad']) !== '' ? $_GET['especialidad'] : null;

// Construir la consulta con o sin filtro según el valor de $especialidad
if ($especialidad) {
    $stmt = $pdo->prepare('
        SELECT u.nombre, u.apellido_p, u.apellido_m, u.cedula, u.especialidad, u.curp
        FROM usuario u
        INNER JOIN roles r ON u.curp = r.curp
        WHERE r.rol = :rol
        AND u.especialidad LIKE :especialidad
    ');
    $stmt->execute([
        'rol' => 'medico',
        'especialidad' => "%$especialidad%"
    ]);
} else {
    $stmt = $pdo->prepare('
        SELECT u.nombre, u.apellido_p, u.apellido_m, u.cedula, u.especialidad, u.curp
        FROM usuario u
        INNER JOIN roles r ON u.curp = r.curp
        WHERE r.rol = :rol
    ');
    $stmt->execute(['rol' => 'medico']);
}

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once 'inc/fondo.php'; ?>
<link rel="stylesheet" href="assets/Css/agendar.css">
<h2>Médicos</h2>

<!-- Barra de búsqueda -->
<form method="get" action="" class="search-bar">
    <label for="especialidad">Buscar por Especialidad:</label>
    <input type="text" id="especialidad" name="especialidad" value="<?= htmlspecialchars($especialidad ?? ''); ?>" placeholder="Ej: Cardiología">
    <button type="submit">Buscar</button>
</form>

<div class="overflow">
  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th> </th>
        <th> </th>
        <th>Cédula</th>
        <th>Especialidad</th>
        <th> </th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($users) > 0): ?>
        <?php foreach ($users as $user): ?>
        <tr>
          <td><?= htmlspecialchars($user['nombre']); ?></td>
          <td><?= htmlspecialchars($user['apellido_p']); ?></td>
          <td><?= htmlspecialchars($user['apellido_m']); ?></td>
          <td><?= htmlspecialchars($user['cedula']); ?></td>
          <td><?= htmlspecialchars($user['especialidad']); ?></td>
          <td>
            <a href="salva_cita.php?curp_med=<?= urlencode($user['curp']); ?>&nombre=<?= urlencode($user['nombre']); ?>&apellido_p=<?= urlencode($user['apellido_p']); ?>&apellido_m=<?= urlencode($user['apellido_m']); ?>" class="select-btn">Seleccionar</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6">No se encontraron médicos con la especialidad "<?= htmlspecialchars($especialidad); ?>".</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
