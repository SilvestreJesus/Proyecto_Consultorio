<?php
session_start();
include_once 'inc/db.php'; // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Encriptar la nueva contraseña utilizando MD5
    $hashed_password = md5($new_password);

    // Preparar consulta para verificar si el correo existe en la tabla roles
    $sql = "SELECT correo FROM roles WHERE correo = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Verificar si el correo está registrado
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        // Si el correo está registrado, actualizar la contraseña en roles
        $update_sql = "UPDATE roles SET contraseña = :new_password WHERE correo = :email";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':new_password', $hashed_password);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->execute();

        // Redirigir con mensaje de éxito
        $_SESSION['success_message'] = 'Contraseña cambiada con éxito.';
        header("Location: index.php");
        exit;
    } else {
        // Si el correo no está registrado, mostrar mensaje de error
        $_SESSION['error_message'] = 'Correo electrónico no registrado.';
        header("Location: procesar_restablecimiento.php");
        exit;
    }
}
?>
