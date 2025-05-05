<?php
try {
    require_once 'conexion.php'; // tu archivo de conexión PDO

    $tipo = $_GET['tipo'] ?? '';
    $entidades = [];
    $pdo = getDB(); // Obtener la conexión a la base de datos

    switch ($tipo) {
        case 'usuarios':
            // Seleccionar usuarios
            $query = $pdo->prepare("SELECT id, nombre FROM usuario");
            $query->execute();
            $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'empresas':
            // Seleccionar empresas
            $query = $pdo->prepare("SELECT id, nombre FROM empresa");
            $query->execute();
            $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'grupos':
            // Seleccionar grupos
            $query = $pdo->prepare("SELECT id, nombre FROM grupo");
            $query->execute();
            $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'aplicaciones':
            // Seleccionar aplicaciones
            $query = $pdo->prepare("SELECT uuid as id, nombre FROM aplicacion");
            $query->execute();
            $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($entidades);
} catch (Exception $e) {
    // Mostrar el error si ocurre un problema con la base de datos
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
