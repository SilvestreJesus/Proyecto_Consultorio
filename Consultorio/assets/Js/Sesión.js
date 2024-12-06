function toggleMenu() {
  const sidebar = document.getElementById("sidebar");
  const loginContainer = document.getElementById("loginContainer");

  sidebar.classList.toggle("active");
  loginContainer.classList.toggle("shifted");
}

function showForm(formType) {
  // Remover la clase activa de todos los botones
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active');
  });

  // Remover la clase activa de todos los formularios
  document.querySelectorAll('.form').forEach(form => {
    form.classList.remove('active');
  });

  // Activar el botón y formulario seleccionados
  document.querySelector(`[onclick="showForm('${formType}')"]`).classList.add('active');
  document.getElementById(`form-${formType}`).classList.add('active');
}

