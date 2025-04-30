<?php
require_once 'conexion.php'; // Incluye la conexión a la base de datos

// Obtener los datos enviados en formato JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validar que se haya recibido el ID
if (!isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    exit;
}

$id = $data['id'];

try {
    $db = getDB(); // Obtener conexión

    // Preparar la sentencia SQL para eliminar el grupo
    $stmt = $db->prepare("DELETE FROM grupo WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el grupo']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
