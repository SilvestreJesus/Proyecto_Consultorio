<?php
require_once 'inc/db.php';
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$correo = $_SESSION['usuario']['correo'];

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los datos personales del usuario logueado que es un médico
    $stmt = $pdo->prepare('
        SELECT u.curp, u.nombre, u.apellido_p, u.apellido_m, u.fecha_nac, 
               TIMESTAMPDIFF(YEAR, u.fecha_nac, CURDATE()) AS edad, u.sexo, u.telefono, u.direccion, u.correo, 
               u.cedula, u.especialidad
        FROM usuario u
        INNER JOIN roles r ON u.curp = r.curp AND u.correo = r.correo
        WHERE u.correo = :correo AND r.rol = :rol
    ');
    $stmt->execute(['correo' => $correo, 'rol' => 'medico']);
    $datosMedico = $stmt->fetch(PDO::FETCH_ASSOC);


    // Devolver los datos en formato JSON
    if ($datosMedico) {
        echo json_encode($datosMedico);
    } else {
        echo json_encode(['error' => 'No se encontraron datos personales o no tiene el rol de médico.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener datos: ' . $e->getMessage()]);
}
?>
