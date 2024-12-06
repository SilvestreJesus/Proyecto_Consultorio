<?php include_once 'inc/header.php'; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/Css/sesión.css">
  <script src="assets/Js/sesión.js"></script>
</head>
<body>

  <!-- Contenedor principal -->
  <div class="login-form" id="loginContainer">
    <form id="form-usuario" action="view_login.php" method="POST" class="form active">
      <h2>Inicio de Sesión</h2>
      <div class="input-group">
        <label for="correo">Correo</label>
        <input type="text" id="correo" name="correo" placeholder="Ingrese su correo" required>
      </div>
      <div class="input-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="contra" placeholder="Ingrese su contraseña" required>
      </div>
      <div class="options">
        <label>
          <input type="checkbox"> Recordar Contraseña
        </label>
        <a href="recupera_contra.php">¿Olvidaste tu Contraseña?</a>
      </div>
      <button type="submit">Iniciar Sesión</button>
      <p>¿No tienes una cuenta? <a href="registrarte.php">Crear Una</a></p>
    </form>
  </div>
<!-- Modal para mostrar errores -->
<div class="modalOverlay" id="modalOverlay"></div>
<div class="modalBox" id="modalBox">
  <p class="modalMessage" id="modalMessage"></p>
  <button style="background-color: #ff8b2c;" onclick="closeModal()">Cerrar</button>
</div>

<script>
  // Mostrar el modal si hay un error
  <?php if (isset($_SESSION['error'])): ?>
    document.querySelector('#modalOverlay').style.display = 'block';
    document.querySelector('#modalBox').style.display = 'block';
    document.querySelector('#modalMessage').textContent = "<?php echo $_SESSION['error']; ?>";
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  // Función para cerrar el modal
  function closeModal() {
    document.querySelector('#modalOverlay').style.display = 'none';
    document.querySelector('#modalBox').style.display = 'none';
  }
</script>


</body>
</html>
