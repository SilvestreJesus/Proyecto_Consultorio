<?php
require_once 'inc/db.php'; // Asegúrate de que la conexión a PostgreSQL esté configurada aquí

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los valores del formulario
    $curp = $_POST['curp'];
    $nombre = $_POST['nombre'];
    $apellidoP = $_POST['apellidoP'];
    $apellidoM = $_POST['apellidoM'];
    $fecha = $_POST['fecha'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];
    $contra = $_POST['contra'];

    try {
        // Iniciar la transacción
        $pdo->beginTransaction();

        // Verificar si el correo ya existe en la tabla 'roles'
        $checkCorreoStmt = $pdo->prepare('SELECT * FROM roles WHERE correo = :correo');
        $checkCorreoStmt->execute(['correo' => $correo]);
        $existingCorreo = $checkCorreoStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingCorreo) {
            $_SESSION['error_message'] = "El correo ya está registrado.";
            header('Location: registrarte.php');
            exit;
        }

        // Verificar si el usuario con esta CURP ya existe
        $checkUserStmt = $pdo->prepare('SELECT * FROM usuario WHERE curp = :curp');
        $checkUserStmt->execute(['curp' => $curp]);
        $existingUser = $checkUserStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            // Insertar el nuevo rol de paciente junto con la contraseña
            $stmt2 = $pdo->prepare('INSERT INTO roles (curp, correo, contraseña, rol) VALUES (:curp, :correo, :contra, :rol)');
            $stmt2->execute([
                'curp' => $curp,
                'correo' => $correo,
                'contra' => md5($contra),
                'rol' => 'paciente'
            ]);
        } else {
            // Insertar usuario y rol
            $stmt = $pdo->prepare('
                INSERT INTO usuario (curp, nombre, apellido_p, apellido_m, fecha_nac, edad, sexo, telefono, direccion, correo, contraseña, cedula, especialidad)
                VALUES (:curp, :nombre, :apellido_p, :apellido_m, :fecha_nac, :edad, :sexo, :telefono, :direccion, :correo, :contra, :cedula, :especialidad)
            ');
            $stmt->execute([
                'curp' => $curp,
                'nombre' => $nombre,
                'apellido_p' => $apellidoP,
                'apellido_m' => $apellidoM,
                'fecha_nac' => $fecha,
                'edad' => $edad,
                'sexo' => $sexo,
                'telefono' => $telefono,
                'direccion' => $direccion,
                'correo' => $correo,
                'contra' => md5($contra),
                'cedula' => 'N/A',
                'especialidad' => 'N/A'
            ]);

            $stmt2 = $pdo->prepare('INSERT INTO roles (curp, correo, contraseña, rol) VALUES (:curp, :correo, :contra, :rol)');
            $stmt2->execute([
                'curp' => $curp,
                'correo' => $correo,
                'contra' => md5($contra),
                'rol' => 'paciente'
            ]);
        }

        // Confirmar la transacción
        $pdo->commit();

        header('Location: login.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
