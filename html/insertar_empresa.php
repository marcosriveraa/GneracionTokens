<?php
include 'conexion.php';
$pdo = getDB();

$nombre = $_POST['nombreEmpresa'] ?? '';
$email = $_POST['emailEmpresa'] ?? '';
$Telefono = $_POST['telefonoEmpresa'] ?? '';
$Direccion = $_POST['direccionEmpresa'] ?? '';

if (empty($nombre) || empty($email) || empty($Telefono) || empty($Direccion)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

try {
    $smtp = $pdo->prepare("INSERT INTO empresa (nombre, email, telefono, direccion) VALUES (:nombre, :email, :telefono, :direccion)");
    $smtp->execute(['nombre' => $nombre, 'email' => $email, 'telefono' => $Telefono, 'direccion' => $Direccion]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
}
?>