<?php
require_once 'inc/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? null;
    $contraseña = $_POST['contra'] ?? null;

    if ($correo && $contraseña) {
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Buscar en la tabla roles usando el correo y verificar la contraseña
            $stmt = $pdo->prepare('
                SELECT curp, rol, contraseña 
                FROM roles 
                WHERE correo = :correo
            ');
            $stmt->execute(['correo' => $correo]);
            $roleRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($roleRecord) {
                $curp = $roleRecord['curp'];
                $rol = $roleRecord['rol'];
                $hashedPassword = $roleRecord['contraseña'];

                // Verificar la contraseña
                if (md5($contraseña) === $hashedPassword) {
                    // Guardar los datos en la sesión
                    $_SESSION['usuario'] = [
                        'correo' => $correo,
                        'rol' => $rol,
                        'curp' => $curp // Guardar el CURP
                    ];

                    // Redirigir según el rol
                    switch ($rol) {
                        case 'paciente':
                            header('Location: paciente_index.php');
                            break;
                        case 'medico':
                            header('Location: medico_index.php');
                            break;
                        case 'administrador':
                            header('Location: administrador_index.php');
                            break;
                        default:
                            $_SESSION['error'] = "Rol no reconocido.";
                            header('Location: login.php');
                    }
                    exit;
                } else {
                    $_SESSION['error'] = "Contraseña incorrecta.";
                    header('Location: login.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Correo no encontrado.";
                header('Location: login.php');
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error de conexión: " . $e->getMessage();
            header('Location: login.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "Por favor, proporcione el correo y la contraseña.";
        header('Location: login.php');
        exit;
    }
}
?>
