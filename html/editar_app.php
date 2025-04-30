<?php
include 'conexion.php';
$pdo = getDB();

$data = json_decode(file_get_contents('php://input'), true);
$uuid = $data['uuid'] ?? '';
$nombre = $data['nombre'] ?? '';

if ($uuid && $nombre) {
    $sql = "UPDATE aplicacion SET nombre = :nombre WHERE uuid = :uuid";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':nombre' => $nombre, ':uuid' => $uuid])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo actualizar.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
}
?>
