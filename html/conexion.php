<?php
// Archivo de configuración para la conexión a la base de datos

// Datos de conexión a la base de datos
define('DB_HOST', 'postgres'); // Cambia esto si tu base de datos no está en localhost
define('DB_NAME', 'dashboard'); // Nombre de tu base de datos
define('DB_USER', 'admin'); // Usuario de la base de datos
define('DB_PASS', 'admin'); // Contraseña de la base de datos

// Función para obtener la conexión PDO
function getDB() {
    $pdo = null;
    try {
        // Crear una nueva instancia de PDO para la conexión a la base de datos
        $pdo = new PDO("pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Modo de error
    } catch (PDOException $e) {
        // En caso de error en la conexión
        echo "Error al conectar a la base de datos: " . $e->getMessage();
        exit;
    }
    return $pdo;
}
?>
