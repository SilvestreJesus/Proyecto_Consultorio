<?php
session_start(); // Start the session to get the error message
include_once 'inc/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/Css/rec_contra.css">
</head>
<body>

    <div class="form-container">
        <div class="success-message" id="successMessage" style="display:none;">
            ¡Correo de recuperación enviado con éxito!
        </div>
        <header>
        <h2>Recuperar Contraseña</h2>
        </header>
        <form action="contra.php" method="POST">
            <div class="form-group">
                <label for="email">Correo electrónico <span class="required">*</span></label>
                <input type="email" id="email" name="email" required>
                <div class="error" id="emailError" style="display:none;">Por favor ingresa un correo electrónico válido</div>
            </div>
        <!-- Mostrar el error si está disponible en la sesión -->
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="error-message" style="background-color: red; color: white; text-align: center; padding: 10px;">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']); // Clear the error message after displaying it
            
        }
        
        ?>
        <br>
            <button type="submit">Enviar</button>
        </form>



    </div>

</body>
</html>
