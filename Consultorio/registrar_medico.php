<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bienestar Médico</title>  
  
    <link rel="icon" type="image/x-icon" href="inc/Imagenes/logo.png" />
    <link rel="stylesheet" href="assets/Css/registrar_medico.css">
  </head>
  <body>
    <header>
    <div class="background">
        <img src="inc/Imagenes/fondo.jpeg" alt="Background">
      </div>

      <nav id="menu">
        <a href="administrador_index.php"><img src="inc/Imagenes/casa.jpeg"  class="entrar-icon" ></a>
      </nav>

    </header>
    <main class="container">
        
<body>
    
    <main class="container">
        <div class="form-container">
         <h2>Crear Cuenta Médico</h2>
        <form id="registrationForm" action="save_medico.php" method="POST" onsubmit="return validateForm(event)">
          
        <div class="form-group-grouped">
            <div class="form-group left">
                <label for="curp">CURP <span class="required">*</span></label>
                <input type="text" id="curp" name="curp" required style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
                <div class="error" id="curpError">Por favor ingresa la CURP</div>
            </div>

            <div class="form-group right">
                <label for="nombre">Nombre <span class="required">*</span></label>
                <input type="text" id="nombre" name="nombre" required>
                <div class="error" id="nombreError">Por favor ingresa el nombre</div>
            </div>
        </div>

        <div class="form-group-grouped">
            <div class="form-group left">
                <label for="apellidoP">Apellido Paterno <span class="required">*</span></label>
                <input type="text" id="apellidoP" name="apellidoP" required>
                <div class="error" id="apellidoPaternoError">Por favor ingresa el apellido paterno</div>
            </div>

            <div class="form-group right">
                <label for="apellidoM">Apellido Materno <span class="required">*</span></label>
                <input type="text" id="apellidoM" name="apellidoM" required>
                <div class="error" id="apellidoMaternoError">Por favor ingresa el apellido materno</div>
            </div>
        </div>
        <div class="form-group-grouped">
            <div class="form-group left">
                <label for="cedula">Cedula <span class="required">*</span></label>
                <input type="text" id="cedula" name="cedula" required>
                <div class="error" id="cedulaError">Por favor ingresa la cedula</div>
            </div>
            <div class="form-group right">
                <label for="especialidad">Especialidad<span class="required">*</span></label>
                <input type="text" id="especialidad" name="especialidad" required>
                <div class="error" id="especialidadError">Por favor ingresa la especialidad</div>
            </div>
        </div>

         
        <div class="form-group">
            <label for="fecha">Fecha de Nacimiento <span class="required">*</span></label>
            <input type="date" id="fecha" name="fecha" required onchange="calculateAge()">
            <div class="error" id="fechaNacimientoError">Por favor ingresa tu fecha de nacimiento</div>
        </div>

        <div class="form-group">
            <label for="edad">Edad <span class="required">*</span></label>
            <input type="number" id="edad" name="edad" min="18" required>
            <div class="error" id="edadError">Por favor ingresa una edad válida (mayor o igual a 18)</div>
        </div>

    <div class="form-group-grouped">
        <div class="form-group left">
            <label for="sexo">Sexo <span class="required">*</span></label>
            <select id="sexo" name="sexo" required>
                <option value="">Seleccione</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select>
            <div class="error" id="sexoError">Por favor selecciona el sexo</div>
        </div>
 
            <div class="form-group right">
                <label for="telefono">Teléfono <span class="required">*</span></label>
                <input type="tel" id="telefono" name="telefono" pattern="[0-9]{9,}" required>
                <div class="error" id="telefonoError">Por favor ingresa un número de teléfono válido</div>
            </div>
    </div>
    <script>
        // Evitar entrada de caracteres no numéricos
        document.getElementById('telefono').addEventListener('input', function (e) {
            // Elimina caracteres no numéricos
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>



        <div class="form-group">
            <label for="direccion">Dirección <span class="required">*</span></label>
            <input type="text" id="direccion" name="direccion" required>
            <div class="error" id="direccionError">Por favor ingresa la dirección</div>
        </div>

        <div class="form-group">
            <label for="correo">Correo electrónico <span class="required">*</span></label>
            <input type="email" id="correo" name="correo" required>
            <div class="error" id="emailError">Por favor ingresa un correo electrónico válido</div>
        </div>

            <!-- Campos de contraseña ya presentes -->
            <div class="form-group">
                <label for="contraseña">Contraseña <span class="required">*</span></label>
                <input type="password" id="contraseña" name="contra" required>

                <div class="password-requirements">
                    La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, un número y un carácter especial
                </div>
                <div class="error" id="passwordError">La contraseña no cumple con los requisitos</div>
            </div>
            <?php
                if (isset($_GET['error'])) {
                    echo '<div class="message-container">';
                    if ($_GET['error'] == 1) {
                        echo '<div class="error-message" style="background-color: red; color: white; text-align: center; padding: 10px;">¡Este médico ya está registrado!</div>';
                    } elseif ($_GET['error'] == 2) {
                        echo '<div class="error-message" style="background-color: red; color: white; text-align: center; padding: 10px;">Este correo ya esta registrado.</div>';
                    }
                    echo '</div>';
                }
            ?>

        </div>


            <fieldset>
                <button type="submit">Registrarse</button>
            </fieldset>
        </form>
    </div>

    <script>
    function validateForm(event) {
        // Resetear mensajes de error
        document.querySelectorAll('.error').forEach(error => error.style.display = 'none');
        
        let isValid = true;
        const form = document.getElementById('registrationForm');
        
        // Validar campos obligatorios
        const fields = [
            { id: 'nombre', errorId: 'nombreError', condition: value => value.trim().length >= 3 },
            { id: 'apellidoP', errorId: 'apellidoPaternoError', condition: value => value.trim() !== '' },
            { id: 'apellidoM', errorId: 'apellidoMaternoError', condition: value => value.trim() !== '' },
            { id: 'edad', errorId: 'edadError', condition: value => value >= 18 },
            { id: 'sexo', errorId: 'sexoError', condition: value => value !== '' },
            { id: 'direccion', errorId: 'direccionError', condition: value => value.trim() !== '' },
            { id: 'fecha', errorId: 'fechaNacimientoError', condition: value => value !== '' },
        ];
        
        fields.forEach(field => {
            const value = form[field.id].value;
            if (!field.condition(value)) {
                document.getElementById(field.errorId).style.display = 'block';
                isValid = false;
            }
        });

        // Validar email, teléfono y contraseñas
        const email = form.correo.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            document.getElementById('emailError').style.display = 'block';
            isValid = false;
        }

        const telefono = form.telefono.value.trim();
        const telefonoRegex = /^\d{9,}$/;
        if (!telefonoRegex.test(telefono)) {
            document.getElementById('telefonoError').style.display = 'block';
            isValid = false;
        }

   

        const password = form.contraseña.value;
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
        if (!passwordRegex.test(password)) {
            document.getElementById('passwordError').style.display = 'block';
            isValid = false;
        }

  
        
        // Si el formulario no es válido, prevenir el envío
        if (!isValid) {
            event.preventDefault();
        }

        // Si es válido, permitir el envío
        return isValid;
    }

    function calculateAge() {
    // Obtener la fecha de nacimiento del campo
    const birthDate = document.getElementById('fecha').value;
    
    if (birthDate) {
        // Convertir la fecha de nacimiento a un objeto Date
        const birthDateObj = new Date(birthDate);
        
        // Obtener la fecha actual
        const currentDate = new Date();
        
        // Calcular la diferencia en años
        let age = currentDate.getFullYear() - birthDateObj.getFullYear();
        
        // Verificar si ya ha pasado el cumpleaños este año
        const monthDiff = currentDate.getMonth() - birthDateObj.getMonth();
        const dayDiff = currentDate.getDate() - birthDateObj.getDate();
        
        // Si no ha pasado el cumpleaños aún este año, restamos un año
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
            age--;
        }

        // Asignar la edad calculada al campo de edad
        document.getElementById('edad').value = age;
    }
}

</script>
</main>
 

 </body>
</html>
