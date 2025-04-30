<?php
include 'conexion.php';
$pdo = getDB();

// Obtener los datos de la solicitud JSON
$data = json_decode(file_get_contents('php://input'), true);
$uuid = $data['uuid'] ?? '';  // Obtener uuid
$nombre = $data['nombre'] ?? '';  // Obtener nombre

// Verificar si se recibieron los parámetros necesarios
if ($uuid && $nombre) {
    // Actualizar el grupo en la base de datos
    $sql = "UPDATE grupo SET nombre = :nombre WHERE id = :uuid";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':nombre' => $nombre, ':uuid' => $uuid])) {
        // Responder con éxito
        echo json_encode(['success' => true]);
    } else {
        // Responder con error
        echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el grupo.']);
    }
} else {
    // Responder con error si faltan parámetros
    echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
}
?>
