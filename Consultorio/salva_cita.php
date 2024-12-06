<?php
require_once 'inc/db.php';
session_start();

// Verificar que el usuario haya iniciado sesión como paciente
if (!isset($_SESSION['usuario']['curp']) || $_SESSION['usuario']['rol'] !== 'paciente') {
    $_SESSION['error'] = "Debe iniciar sesión como paciente para agendar una cita.";
    header('Location: login.php');
    exit;
}

// CURP del paciente desde la sesión
$curp_pac = $_SESSION['usuario']['curp'];

// Obtener datos del médico seleccionado
$curp_med = isset($_GET['curp_med']) ? $_GET['curp_med'] : '';
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$apellido_p = isset($_GET['apellido_p']) ? $_GET['apellido_p'] : '';
$apellido_m = isset($_GET['apellido_m']) ? $_GET['apellido_m'] : '';

// Inicializar el array de horas ocupadas
$horas_ocupadas = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_cita = $_POST['fecha_cita'];
    // Consultar las horas ya ocupadas para esa fecha y médico
    $stmt = $pdo->prepare('
        SELECT hora_cita 
        FROM cita 
        WHERE curp_med = :curp_med AND fecha_cita = :fecha_cita
    ');
    $stmt->execute(['curp_med' => $curp_med, 'fecha_cita' => $fecha_cita]);
    $horas_ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);  // Obtener las horas ocupadas
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/Css/salva_cita.css">

</head>
<body>
    <header>
        <div class="background">
            <img src="inc/Imagenes/fondo.jpeg" alt="Background">
        </div>
        <nav id="menu">
            <a href="paciente_index.php"><img src="inc/Imagenes/casa.jpeg" class="entrar-icon" alt="Ir al inicio"></a>
        </nav>
    </header>

    <main class="container">
        <div class="form-container">
            <h2>Agendar Cita</h2>
            <form action="guardar_cita.php" method="POST" onsubmit="return verificarHora()">
            <div class="form-group">
                <label for="curp_med">Curp del Médico:</label>
                <!-- Mantener el valor de curp_med que proviene del GET o POST -->
                <input type="text" name="curp_med" value="<?= isset($_GET['curp_med']) ? htmlspecialchars($_GET['curp_med']) : (isset($_POST['curp_med']) ? htmlspecialchars($_POST['curp_med']) : ''); ?>" readonly>
             </div>

            <div class="form-group">
                <label for="nombre_med">Nombre del Médico:</label>
                <!-- Mantener el nombre completo del médico usando los valores de GET -->
                <input type="text" name="nombre_med" value="<?= isset($_GET['nombre']) && isset($_GET['apellido_p']) && isset($_GET['apellido_m']) ? htmlspecialchars(trim("{$_GET['nombre']} {$_GET['apellido_p']} {$_GET['apellido_m']}")) : (isset($_POST['nombre_med']) ? htmlspecialchars($_POST['nombre_med']) : ''); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="curp_pac">Curp del Paciente:</label>
                <input type="text" name="curp_pac" value="<?= htmlspecialchars($curp_pac); ?>" readonly>
            </div>

            <div class="form-group-grouped">
                <div class="form-group left">
                    <label for="fecha_cita">Fecha de la Cita:</label>
                    <input type="date" name="fecha_cita" value="<?= isset($_POST['fecha_cita']) ? htmlspecialchars($_POST['fecha_cita']) : ''; ?>" required>
                </div>
                <div class="form-group right">
                    <label for="hora_cita">Hora de la Cita:</label>
                    <select name="hora_cita" id="hora_cita" required>
                        <option value="" disabled selected>Seleccione una hora</option>
                        <?php 
                            for ($hour = 8; $hour <= 19; $hour++) {
                                for ($minute = 0; $minute < 60; $minute += 20) {
                                    $time = sprintf("%02d:%02d", $hour, $minute);
                                    // Deshabilitar horas ya ocupadas
                                    $disabled = in_array($time, $horas_ocupadas) ? 'disabled' : '';
                                    echo "<option value=\"$time\" $disabled>$time</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="sintomas">Síntomas:</label>
                <textarea name="sintomas" rows="5" cols="40" style="font-size: 18px; padding: 10px;"><?= isset($_POST['sintomas']) ? htmlspecialchars($_POST['sintomas']) : ''; ?></textarea>
            </div>
    <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="error-message" style="background-color: red; color: white; text-align: center; padding: 10px;">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
    ?>


    <br><br>
    <button type="submit">Agendar Cita</button>
</form>








 



        </div>
    </main>
</body>
</html>
