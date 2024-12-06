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

    // Obtener el CURP del usuario desde la tabla 'roles' usando el correo
    $stmt1 = $pdo->prepare('
        SELECT curp, correo 
        FROM roles 
        WHERE correo = :correo
    ');
    $stmt1->execute(['correo' => $correo]);
    $roles = $stmt1->fetch(PDO::FETCH_ASSOC);

    if (!$roles) {
        // Si no se encuentra el correo en la tabla roles, mostrar error
        echo json_encode(['error' => 'Correo no encontrado en los roles.']);
        exit;
    }

    // Obtener el CURP y el correo del usuario desde roles
    $curp = $roles['curp'];
    $correoRoles = $roles['correo'];  // El correo que quieres mostrar

    // Obtener los datos personales del usuario con el CURP obtenido
    $stmt2 = $pdo->prepare('
        SELECT u.curp, u.nombre, u.apellido_p, u.apellido_m, u.fecha_nac, 
               TIMESTAMPDIFF(YEAR, u.fecha_nac, CURDATE()) AS edad, u.sexo, u.telefono, u.direccion
        FROM usuario u
        WHERE u.curp = :curp
    ');

    $stmt2->execute(['curp' => $curp]);
    $datosPaciente = $stmt2->fetch(PDO::FETCH_ASSOC);

    // Agregar el correo de la tabla 'roles' a los datos del paciente
    if ($datosPaciente) {
        $datosPaciente['correo'] = $correoRoles;  // Reemplazar el correo de 'usuario' con el de 'roles'
        echo json_encode($datosPaciente);
    } else {
        echo json_encode(['error' => 'No se encontraron datos personales.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener datos: ' . $e->getMessage()]);
}
?>
