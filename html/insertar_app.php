<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include('conexion.php');

$conn = getDB();
$nombre = $_POST['nombre'];

// Intentamos ejecutar la inserción
try {
    $query = "INSERT INTO aplicacion (nombre) VALUES (:nombre)";
    $smtp = $conn->prepare($query);
    $smtp->bindParam(':nombre', $nombre);
    $smtp->execute();
    
    // Si la inserción es exitosa, respondemos con éxito
    $response = [
        'success' => true,
        'message' => 'Aplicación añadida correctamente'
    ];
} catch (Exception $e) {
    // Si ocurre algún error, respondemos con un mensaje de error
    $response = [
        'success' => false,
        'message' => 'Hubo un error al añadir la aplicación: ' . $e->getMessage()
    ];
}

// Establecemos el encabezado de la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
