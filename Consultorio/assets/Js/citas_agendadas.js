// Seleccionar el contenedor del calendario y los botones
const calendar = document.getElementById('calendar');
const title = document.getElementById('calendar-title');
const prevBtn = document.getElementById('prev-month');
const nextBtn = document.getElementById('next-month');

// Variables de mes y año actuales
let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth();

// Días de la semana
const daysOfWeek = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];

// Función para renderizar el calendario
function renderCalendar(year, month) {
    // Limpiar el calendario anterior
    calendar.innerHTML = '';

    // Configurar el título con el mes y año actual
    const monthNames = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    title.textContent = `${monthNames[month]} ${year}`;

    // Crear los encabezados de los días de la semana
    daysOfWeek.forEach(day => {
        const dayHeader = document.createElement('div');
        dayHeader.textContent = day;
        dayHeader.classList.add('day', 'inactive');
        calendar.appendChild(dayHeader);
    });

    // Obtener el primer día y el último día del mes
    const firstDay = new Date(year, month, 1).getDay(); // Primer día del mes
    const lastDate = new Date(year, month + 1, 0).getDate(); // Último día del mes

    // Ajustar el inicio del calendario (lunes como primer día)
    let startDay = firstDay === 0 ? 6 : firstDay - 1;

    // Crear celdas vacías antes del primer día del mes
    for (let i = 0; i < startDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.classList.add('day', 'inactive');
        calendar.appendChild(emptyCell);
    }
        
    // Crear los días del mes
    for (let date = 1; date <= lastDate; date++) {
        const dayCell = document.createElement('div');
        dayCell.textContent = date;
        dayCell.classList.add('day');

        // Resaltar el día actual
        const today = new Date();
        if (year === today.getFullYear() && month === today.getMonth() && date === today.getDate()) {
            dayCell.classList.add('today');
        }

        // Agregar evento al hacer clic en un día
        dayCell.addEventListener('click', () => {
            // Redirigir a atender_citas.php con los parámetros de fecha seleccionada
            const formattedMonth = (month + 1).toString().padStart(2, '0'); // Mes en formato "MM"
            const formattedDay = date.toString().padStart(2, '0'); // Día en formato "DD"
            window.location.href = `atender_citas.php?day=${formattedDay}&month=${formattedMonth}&year=${year}`;
        });
        
        calendar.appendChild(dayCell);
    }

}

// Función para cambiar el mes
function changeMonth(direction) {
    currentMonth += direction;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar(currentYear, currentMonth);
}

// Eventos de los botones de navegación
prevBtn.addEventListener('click', () => changeMonth(-1));
nextBtn.addEventListener('click', () => changeMonth(1));

// Renderizar el calendario inicial
renderCalendar(currentYear, currentMonth);
