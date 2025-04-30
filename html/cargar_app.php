<?php
include('conexion.php');

$conn = getDB();

$query = "SELECT uuid, nombre, fecha_creacion FROM aplicacion";
$smpt = $conn->prepare($query);
$smpt->execute();

$aplicaciones = $smpt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['data' => $aplicaciones]);
?>