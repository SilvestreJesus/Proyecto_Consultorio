<?php
require_once 'inc/db.php';

// Obtener el CURP del paciente y el CURP del médico desde la URL
$curp_pac = $_GET['curp_pac'] ?? null;
$curp_med = $_GET['curp_med'] ?? null;

if (!$curp_pac || !$curp_med) {
    header('Location: paciente_index.php');
    exit;
}

try {
    // Establecer el atributo de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para eliminar la cita asociada al CURP del paciente
    $stmt = $pdo->prepare('
        DELETE FROM cita 
        WHERE curp_pac = :curp_pac AND curp_med = :curp_med
    ');
    $stmt->execute(['curp_pac' => $curp_pac, 'curp_med' => $curp_med]);

    // Redirigir después de la eliminación
    header('Location: paciente_index.php');
    exit;

} catch (PDOException $e) {
    echo "Error al eliminar la cita: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
