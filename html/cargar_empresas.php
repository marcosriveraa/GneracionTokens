<?php
include 'conexion.php';
$pdo = getDB();

$query = "SELECT id, nombre, email, telefono, direccion from empresa";
$stmt = $pdo->prepare($query);
$stmt->execute();

$empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['data' => $empresas]);
?>