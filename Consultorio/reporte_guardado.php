<?php
require_once 'inc/db.php';
session_start();

// Verificar si el usuario inició sesión como médico
if (!isset($_SESSION['usuario']['curp']) || $_SESSION['usuario']['rol'] !== 'medico') {
    $_SESSION['error'] = "Debe iniciar sesión como médico para acceder.";
    header('Location: login.php');
    exit;
}

$curp_med = $_SESSION['usuario']['curp'];
$curp_pac = $_GET['curp_pac'] ?? '';
$fecha_cita = $_GET['fecha_cita'] ?? '';
$hora_cita = $_GET['hora_cita'] ?? '';

$nombre_pac = '';
$apellido_p_pac = '';
$apellido_m_pac = '';
$hora_fin = '';
$diagnostico = '';
$medicamentos = '';
$sintomas = ''; 

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos del paciente y la cita si existen
    if ($curp_pac && $fecha_cita && $hora_cita) {
        $stmt = $pdo->prepare("
            SELECT 
                u.nombre, u.apellido_p, u.apellido_m, 
                c.hora_fin,c.sintomas
            FROM usuario u
            JOIN cita c ON u.curp = c.curp_pac
            WHERE c.curp_pac = :curp_pac 
            AND c.curp_med = :curp_med 
            AND c.fecha_cita = :fecha_cita 
            AND c.hora_cita = :hora_cita
        ");
        $stmt->execute([
            'curp_pac' => $curp_pac,
            'curp_med' => $curp_med,
            'fecha_cita' => $fecha_cita,
            'hora_cita' => $hora_cita
            
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $nombre_pac = $data['nombre'];
            $apellido_p_pac = $data['apellido_p'];
            $apellido_m_pac = $data['apellido_m'];
            $hora_fin = $data['hora_fin'];
            $sintomas = $data['sintomas'];
            
        }
    }
    // Limpiar valores para observaciones y medicamentos
    $diagnostico = '';
    $medicamentos = '';
} catch (PDOException $e) {
    echo "Error al cargar los datos: " . htmlspecialchars($e->getMessage());
    exit;
}

?>
<?php
if (isset($_SESSION['mensaje'])) {
    $tipo_mensaje = $_SESSION['tipo_mensaje'] === "success" ? "alert-success" : "alert-error";
    echo "<div class='alert $tipo_mensaje'>{$_SESSION['mensaje']}</div>";
    unset($_SESSION['mensaje']);
    unset($_SESSION['tipo_mensaje']);
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bienestar Médico</title>  
  
    <link rel="icon" type="image/x-icon" href="inc/Imagenes/logo.png" />
    <link rel="stylesheet" href="assets/Css/guadar_reporte.css">
    <header>
        <div class="background">
            <img src="inc/Imagenes/fondo.jpeg" alt="Background">
        </div>
        <nav id="menu">
            <a href="medico_index.php"><img src="inc/Imagenes/casa.jpeg" class="entrar-icon" alt="Ir al inicio"></a>
        </nav>
    </header>
</head>
<body>
    <main class="container">
        <div class="form-container">
            <h2>Detalles de la Cita</h2>
            <form action="guadar_reporte.php" method="POST">
                <div class="form-group">
                    <label for="curp_pac">CURP del Paciente:</label>
                    <input type="text" name="curp_pac" value="<?= htmlspecialchars($curp_pac); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="nombre_pac">Nombre del Paciente:</label>
                    <input type="text" name="nombre_pac" 
                        value="<?= htmlspecialchars("$nombre_pac $apellido_p_pac $apellido_m_pac"); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="curp_med">CURP del Médico:</label>
                    <input type="text" name="curp_med" value="<?= htmlspecialchars($curp_med); ?>" readonly>
                </div>

                <div class="form-group-grouped">
                    <div class="form-group left">
                        <label for="fecha_cita">Fecha de la Cita:</label>
                        <input type="date" name="fecha_cita" value="<?= htmlspecialchars($fecha_cita); ?>" readonly>
                    </div>
                    <div class="form-group right">
                        <label for="hora_cita">Hora de la Cita:</label>
                        <input type="time" name="hora_cita" value="<?= htmlspecialchars($hora_cita); ?>" readonly>
                    </div>
                    <div class="form-group right">
                        <label for="hora_fin">Hora Final de la Cita:</label>
                        <input type="time" name="hora_fin" value="<?= htmlspecialchars($hora_fin); ?>" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label for="sintomas">Sintomas:</label>
                    <textarea name="sintomas" rows="5" cols="61" 
                        style="font-size: 16px; padding: 15px;"  required readonly><?= htmlspecialchars($sintomas);?></textarea>
                </div>

                <div class="form-group">
                    <label for="diagnostico">Observaciones:</label>
                    <textarea name="diagnostico" rows="5" cols="61" 
                        style="font-size: 16px; padding: 15px;"><?= htmlspecialchars($diagnostico); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="medicamentos">Medicamentos:</label>
                    <textarea name="medicamentos" rows="5" cols="61" 
                        style="font-size: 16px; padding: 15px;"><?= htmlspecialchars($medicamentos); ?></textarea>
                </div>
                <div class="form-group-grouped">
                    <div class="form-group left">
                        <a href="expediente.php?curp_pac=<?= urlencode($curp_pac); ?>&nombre=<?= urlencode($nombre_pac); ?>&apellido_p=<?= urlencode($apellido_p_pac); ?>&apellido_m=<?= urlencode($apellido_m_pac); ?>&fecha_cita=<?= urlencode($fecha_cita); ?>&hora_cita=<?= urlencode($hora_cita); ?>" class="select-btn">
                            <button type="button">Expediente</button> 
                        </a>
                    </div>

                    <div class="form-group right">
                        <button type="submit">Guardar Cambios</button>
                     </div>
                </div>
            </form>
            <div id="error-message" style="color: red;"></div>
            <script>
                document.querySelector('form').addEventListener('submit', function (event) {
                    const diagnostico = document.querySelector('[name="diagnostico"]').value.trim();
                    const medicamentos = document.querySelector('[name="medicamentos"]').value.trim();
                    const errorMessage = document.getElementById('error-message');

                    // Solo mostrar el error si el botón que se ha presionado es "Guardar Cambios"
                    const isSaveButtonClicked = event.submitter && event.submitter.type === 'submit' && event.submitter.textContent === 'Guardar Cambios';

                    if (isSaveButtonClicked) {
                        if (!diagnostico || !medicamentos) {
                            errorMessage.textContent = 'Por favor, complete todos los campos antes de guardar.';
                            event.preventDefault(); // Prevenir el envío del formulario
                        } else {
                            errorMessage.textContent = ''; // Limpiar mensajes anteriores si la validación es correcta
                        }
                    }
                });
            </script>


        </div>
    </main>
</body>
</html>
