<?php
require_once 'inc/db.php';  // Asegúrate de que la conexión a la base de datos esté configurada aquí

session_start();  // Iniciar la sesión para almacenar mensajes



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $curp_med = $_POST['curp_med'];
    $curp_pac = $_POST['curp_pac'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];
    $nombre = $_POST['nombre_med'];
    $apellido_p = $_POST['apellido_p'] ?? ''; // En caso de que no se envíen estos valores por alguna razón
    $apellido_m = $_POST['apellido_m'] ?? '';
    $sintomas = $_POST['sintomas'];

    // Verificar si ya existe una cita para esa fecha y hora
    $stmt_check_hora = $pdo->prepare('
    SELECT COUNT(*) 
    FROM cita 
    WHERE curp_med = :curp_med AND fecha_cita = :fecha_cita AND hora_cita = :hora_cita
    ');
    $stmt_check_hora->execute([
        'curp_med' => $curp_med,
        'fecha_cita' => $fecha_cita,
        'hora_cita' => $hora_cita
    ]);
    $hora_ocupada = $stmt_check_hora->fetchColumn();

    if ($hora_ocupada > 0) {
        // Si la hora ya está ocupada, mostrar un mensaje
        $_SESSION['error_message'] = "Horario ocupada, elige otra.";
        header('Location: salva_cita.php?curp_med=' . urlencode($curp_med) . '&nombre=' . urlencode($nombre) . '&apellido_p=' . urlencode($apellido_p) . '&apellido_m=' . urlencode($apellido_m));  // Redirige a la página con los parámetros
        exit;
    }

    // Verificar si el curp_pac existe en la tabla usuario
    $stmt_check_pac = $pdo->prepare('SELECT COUNT(*) FROM usuario WHERE curp = :curp');
    $stmt_check_pac->execute(['curp' => $curp_pac]);
    $exists = $stmt_check_pac->fetchColumn();

    if ($exists > 0) {
        // Si el paciente existe, insertar la cita
        try {
            $stmt = $pdo->prepare('
                INSERT INTO cita (curp_pac, fecha_cita, hora_cita, hora_fin, sintomas, diagnostico, medicamentos, curp_med)
                VALUES (:curp_pac, :fecha_cita, :hora_cita, :hora_fin, :sintomas, :diagnostico, :medicamentos, :curp_med)
            ');
            $stmt->execute([
                'curp_pac' => $curp_pac,
                'fecha_cita' => $fecha_cita,
                'hora_cita' => $hora_cita,
                'hora_fin' => NULL,  // Ajusta este valor según tus necesidades
                'sintomas' => $sintomas,
                'diagnostico' => 'N/A',  // Ajusta según sea necesario
                'medicamentos' => 'N/A',  // Ajusta según sea necesario
                'curp_med' => $curp_med
            ]);

            // Mensaje de éxito almacenado en la sesión
            $_SESSION['success_message'] = "Cita registrada exitosamente.";
            header('Location: agendar_cita.php');  // Cambia por la página que deseas
            exit;
        } catch (Exception $e) {
            // En caso de error al registrar la cita
            $_SESSION['error_message'] = "Hubo un error al registrar la cita. Inténtalo nuevamente.";
            header('Location: salva_cita.php');  // Cambia por la página que deseas
            exit;
        }
    } else {
        // Si el paciente no existe, mostrar un mensaje
        $_SESSION['error_message'] = "El paciente con CURP $curp_pac no está registrado.";
        header('Location: salva_cita.php');  // Cambia por la página que deseas
        exit;
    }
}
?>
