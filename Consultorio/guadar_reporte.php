<?php
require_once 'inc/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar datos del formulario
    $curp_pac = $_POST['curp_pac'];
    $curp_med = $_POST['curp_med'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];
    $diagnostico = $_POST['diagnostico'];
    $medicamentos = $_POST['medicamentos'];

    try {
        $query = '
            UPDATE cita 
            SET diagnostico = :diagnostico, 
                medicamentos = :medicamentos
            WHERE curp_pac = :curp_pac 
              AND curp_med = :curp_med 
              AND fecha_cita = :fecha_cita 
              AND hora_cita = :hora_cita
        ';
    
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'diagnostico' => $diagnostico,
            'medicamentos' => $medicamentos,
            'curp_pac' => $curp_pac,
            'curp_med' => $curp_med,
            'fecha_cita' => $fecha_cita,
            'hora_cita' => $hora_cita,
        ]);

        $_SESSION['mensaje'] = "Datos actualizados con éxito.";
        $_SESSION['tipo_mensaje'] = "success"; // Para diferenciar tipos de mensajes
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error al actualizar: " . htmlspecialchars($e->getMessage());
        $_SESSION['tipo_mensaje'] = "error";
    }

    // Redirige de nuevo a la misma página
    header('Location: atender_citas.php');  
    exit;
}
  
?>
