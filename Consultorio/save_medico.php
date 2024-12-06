<?php
require_once 'inc/db.php'; // Asegúrate de que la conexión a PostgreSQL esté configurada aquí

// Mostrar mensaje de error si ya existe el médico con la misma CURP o correo
if (isset($_GET['error'])) {
    if ($_GET['error'] == 1) {
        echo '<div class="error-message" style="color: red;">¡Este médico ya está registrado con la misma CURP!</div>';
    } elseif ($_GET['error'] == 2) {
        echo '<div class="error-message" style="color: red;">¡Este correo ya está registrado!</div>';
    }
}

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
    $cedula = $_POST['cedula'];
    $especialidad = $_POST['especialidad'];

    try {
        // Verificar CURP existente
        $stmt = $pdo->prepare('SELECT * FROM usuario WHERE curp = :curp');
        $stmt->execute(['curp' => $curp]);
        if ($stmt->fetch()) {
            header('Location: registrar_medico.php?error=1');
            exit;
        }

        // Verificar correo existente
        $stmt = $pdo->prepare('SELECT * FROM usuario WHERE correo = :correo');
        $stmt->execute(['correo' => $correo]);
        if ($stmt->fetch()) {
            header('Location: registrar_medico.php?error=2');
            exit;
        }

        // Iniciar la transacción
        $pdo->beginTransaction();

        // Preparar la consulta SQL para insertar los datos en la tabla usuario
        $stmt = $pdo->prepare('
            INSERT INTO usuario (curp, nombre, apellido_p, apellido_m, fecha_nac, edad, sexo, telefono, direccion, correo, contraseña, cedula, especialidad)
            VALUES (:curp, :nombre, :apellido_p, :apellido_m, :fecha_nac, :edad, :sexo, :telefono, :direccion, :correo, :contra, :cedula, :especialidad)
        ');

        // Ejecutar la consulta con los parámetros proporcionados
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
            'contra' => md5($contra), // Usa bcrypt o password_hash en producción
            'cedula' => $cedula,
            'especialidad' => $especialidad
        ]);

        // Insertar en la tabla de roles
        $stmt2 = $pdo->prepare('
            INSERT INTO roles (curp, correo, contraseña, rol)
            VALUES (:curp, :correo, :contra, :rol)
        ');

        $stmt2->execute([
            'curp' => $curp,
            'correo' => $correo,
            'contra' => md5($contra), // Usa bcrypt o password_hash en producción
            'rol' => 'medico'
        ]);

        // Confirmar la transacción
        $pdo->commit();

        // Redirigir a la página principal con el parámetro success
        header('Location: administrador_index.php?success=1');
        exit;
    } catch (Exception $e) {
        // Revertir la transacción si hay un error
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
