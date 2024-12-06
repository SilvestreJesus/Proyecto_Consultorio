<?php include_once 'inc/datos_medico.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/Css/datos.css">
    <title>Datos Personales</title>
</head>
<body>

<div class="container">
    <h1>Datos Personales</h1>
    <div class="icon-container">
        <img src="inc/Imagenes/icono.png" alt="Icono" class="icon">
    </div>
    <div id="user-data">
        <div class="data-item">
            <label>Curp:</label>
            <span id="curp">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Nombre:</label>
            <span id="nombre">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Apellido Paterno:</label>
            <span id="apellido_p">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Apellido Materno:</label>
            <span id="apellido_m">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Fecha de Nacimiento:</label>
            <span id="fecha_nac">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Edad:</label>
            <span id="edad">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Sexo:</label>
            <span id="sexo">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Teléfono:</label>
            <span id="telefono">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Dirección:</label>
            <span id="direccion">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Correo:</label>
            <span id="correo">Cargando...</span>
        </div>
        <div class="data-item">
            <label>Cédula:</label>
            <span id="cedula">Cargando...</span>
        </div>        
        <div class="data-item">
            <label>Especialidad:</label>
            <span id="especialidad">Cargando...</span>
        </div>
    </div>
</div>

<script>
    // Función para cargar los datos del usuario
    async function cargarDatos() {
        try {
            const response = await fetch('view_medico.php');
            const data = await response.json();

            if (data.error) {
                document.getElementById('user-data').innerHTML = `<p>${data.error}</p>`;
                return;
            }

            // Rellenar los campos con los datos del usuario
            document.getElementById('curp').textContent = data.curp || 'N/A';
            document.getElementById('nombre').textContent = data.nombre || 'N/A';
            document.getElementById('apellido_p').textContent = data.apellido_p || 'N/A';
            document.getElementById('apellido_m').textContent = data.apellido_m || 'N/A';
            document.getElementById('fecha_nac').textContent = data.fecha_nac || 'N/A';
            document.getElementById('edad').textContent = data.edad || 'N/A';
            document.getElementById('sexo').textContent = data.sexo || 'N/A';
            document.getElementById('telefono').textContent = data.telefono || 'N/A';
            document.getElementById('direccion').textContent = data.direccion || 'N/A';
            document.getElementById('correo').textContent = data.correo || 'N/A';
            document.getElementById('cedula').textContent = data.cedula || 'N/A';
            document.getElementById('especialidad').textContent = data.especialidad || 'N/A';
        } catch (error) {
            console.error("Error al cargar los datos:", error);
            document.getElementById('user-data').innerHTML = "<p>Error al cargar los datos.</p>";
        }
    }

    // Llamar a la función para cargar los datos
    cargarDatos();
</script>

</body>
</html>
