<?php
header('Content-Type: application/json'); // ¡Importante!

include('conexion.php');

$conn = getDB();

$query = "SELECT id, nombre FROM grupo";
$smpt = $conn->prepare($query);
$smpt->execute();

$grupos = $smpt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['data' => $grupos]);
?>