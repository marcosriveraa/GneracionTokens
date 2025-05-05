<?php
try {
    require_once 'conexion.php'; // tu archivo de conexión PDO

    $tipo = $_GET['tipo'] ?? '';
    $entidades = [];
    $pdo = getDB(); // Obtener la conexión a la base de datos

    switch ($tipo) {
        case 'usuarios':
            // Seleccionar usuarios que no tienen token
            $query = $pdo->prepare("SELECT id, nombre FROM usuario WHERE id NOT IN (SELECT id_entidad FROM token)");
            $query->execute();
            $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'empresas':
            // Seleccionar empresas que no tienen token
            $query = $pdo->prepare("SELECT id, nombre FROM empresa WHERE id NOT IN (SELECT id_entidad FROM token)");
            $query->execute();
            $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'grupos':
            // Seleccionar grupos que no tienen token
            $query = $pdo->prepare("SELECT id, nombre FROM grupo WHERE id NOT IN (SELECT id_entidad FROM token)");
            $query->execute();
            $entidades = $query->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'aplicaciones':
            // Seleccionar aplicaciones que no tienen token
            $query = $pdo->prepare("SELECT uuid as id, nombre FROM aplicacion WHERE uuid NOT IN (SELECT id_entidad FROM token)");
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
