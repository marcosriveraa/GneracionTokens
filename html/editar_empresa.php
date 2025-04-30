<?php
include 'conexion.php';
$pdo = getDB();

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';
$nombre = $data['nombre'] ?? '';
$email = $data['email'] ?? '';
$telefono = $data['telefono'] ?? '';
$direccion = $data['direccion'] ?? '';

if ($id && $nombre && $email && $telefono && $direccion) {
    $sql = "UPDATE empresa SET nombre = :nombre, email = :email, telefono = :telefono, direccion = :direccion WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':nombre' => $nombre, ':email' => $email, ':telefono' => $telefono, ':direccion' => $direccion, ':id' => $id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo actualizar.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
}
