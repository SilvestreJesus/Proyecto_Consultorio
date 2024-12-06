document.addEventListener('DOMContentLoaded', () => {
    const videoBackground = document.getElementById('videoBackground');
    videoBackground.addEventListener('error', () => {
        console.error('Error al cargar el video. Verifica la ruta y el formato.');
    });
    console.log('Video de fondo cargado correctamente');
});
