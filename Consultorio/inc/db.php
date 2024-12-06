<?php
$host = '127.0.0.1:3306';
$port = '3306'; 
$dbname = 'u570776304_consultorio'; 
$user = 'u570776304_consultorio'; 
$pass = 'Consultori@2024'; 

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Configurar el modo de errores para mostrar excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si ocurre un error, mostrar el mensaje
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>
