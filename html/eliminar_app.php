<?php
require_once 'conexion.php'; // Incluye la conexión a la base de datos

// Obtener los datos enviados en formato JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['uuid'])) {
    echo json_encode(['success' => false, 'message' => 'UUID no proporcionado']);
    exit;
}

$uuid = $data['uuid'];

try {
    $db = getDB(); // Obtener conexión

    // Preparar la sentencia SQL para eliminar la aplicación
    $stmt = $db->prepare("DELETE FROM aplicacion WHERE uuid = :uuid");
    $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
