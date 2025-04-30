<?php
require_once 'conexion.php'; // tu archivo de conexión PDO

$tipo = $_GET['tipo'] ?? '';
$entidades = [];
$pdo = getDB(); // Obtener la conexión a la base de datos
switch ($tipo) {
    case 'usuarios':
        $query = $pdo->query("SELECT id, nombre FROM usuario");
        $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'empresas':
        $query = $pdo->query("SELECT id, nombre FROM empresa");
        $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'grupos':
        $query = $pdo->query("SELECT id, nombre FROM grupo");
        $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'aplicaciones':
        $query = $pdo->query("SELECT uuid as id, nombre FROM aplicacion");
        $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
        break;
}

header('Content-Type: application/json');
echo json_encode($entidades);
