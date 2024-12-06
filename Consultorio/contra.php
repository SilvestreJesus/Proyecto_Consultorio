<?php
include_once 'inc/db.php'; // Incluir la conexión a la base de datos
session_start(); // Iniciar la sesión para almacenar el mensaje de error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Preparar la consulta SQL para encontrar al usuario por correo
    $sql = "SELECT correo FROM roles WHERE correo = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Verificar si el correo existe
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generar un token aleatorio para restablecer la contraseña
        $token = bin2hex(random_bytes(16)); // Generar el token
        $token_url = "https://linen-lark-843801.hostingersite.com/procesar_restablecimiento.php?token=$token"; // Crear el enlace de restablecimiento

        // Preparar el contenido del correo
        $destinatario = $email;
        $asunto = "Recuperación de Contraseña";
        $mensaje = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                p { font-size: 16px; line-height: 1.5; }
                b { color: #007bff; }
                .password-box { background-color: #d1e7dd; padding: 10px; border-radius: 5px; text-align: center; }
            </style>
        </head>
        <body>
            <p>Estimado usuario,<br/><br/>
            Haga clic en el siguiente enlace para restablecer su contraseña:<br/>
            <a href='$token_url'>Restablecer contraseña</a><br/><br/>
            Este enlace caducará en una hora.<br/><br/>
            Si no solicitó un cambio de contraseña, ignore este correo.<br/><br/>
            ¡Gracias y que tenga un buen día!<br/><br/>Atentamente,<br/>Bienestar Médico
            </p>
        </body>
        </html>
        ";

        // Cabeceras para el correo
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: bienestar.medico2020@gmail.com" . "\r\n";

        // Enviar el correo
        if (mail($destinatario, $asunto, $mensaje, $headers)) {
            // Después de enviar el correo, redirigir al usuario a la página de inicio de sesión
            header('Location: login.php');
            exit;
        } else {
            // Si ocurre un error al enviar el correo
            echo "<script>alert('Hubo un error al enviar el correo');</script>";
            echo "<script>setTimeout(\"location.href='recupera_contra.php'\", 1000);</script>";
        }
    } else {
        // Si el correo no está registrado, almacenar el mensaje de error y redirigir
        $_SESSION['error_message'] = 'Este correo no está registrado.';
        header("Location: recupera_contra.php");
        exit; // Detener la ejecución del script
    }
}
?>
