<?php
require_once 'conexion.php'; // Incluye tu archivo de conexión

// Obtener el nombre del grupo desde la petición POST
$nombreGrupo = $_POST['nombreGrupo'] ?? '';

if (empty($nombreGrupo)) {
    echo json_encode(['success' => false, 'message' => 'El nombre del grupo es obligatorio']);
    exit;
}

try {
    // Obtener la conexión a la base de datos
    $pdo = getDB();

    // Insertar el grupo en la base de datos
    $stmt = $pdo->prepare("INSERT INTO grupo (nombre) VALUES (:nombre)");
    $stmt->execute(['nombre' => $nombreGrupo]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
}
?>
