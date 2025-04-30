<?php
require_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['id']) || 
    !isset($data['nombre']) || 
    !isset($data['email']) || 
    !isset($data['poblacion']) || 
    !isset($data['telefono']) || 
    !isset($data['grupo'])
) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$id = $data['id']; // UUID del usuario
$nombre = $data['nombre'];
$email = $data['email'];
$poblacion = $data['poblacion'];
$telefono = $data['telefono'];
$grupo = $data['grupo'];

try {
    $db = getDB();
    $db->beginTransaction();

    // Actualizar los datos del usuario
    $stmtUsuario = $db->prepare("UPDATE usuario SET nombre = :nombre, email = :email, poblacion = :poblacion, telefono = :telefono WHERE id = :id");
    $stmtUsuario->execute([
        ':id' => $id,
        ':nombre' => $nombre,
        ':email' => $email,
        ':poblacion' => $poblacion,
        ':telefono' => $telefono
    ]);

    // Actualizar el grupo del usuario en usuario_grupo
    $stmtGrupo = $db->prepare("UPDATE usuario_grupo SET grupo_id = :id_grupo WHERE usuario_id = :id_usuario");
    $stmtGrupo->execute([
        ':id_grupo' => $grupo,
        ':id_usuario' => $id
    ]);

    $db->commit();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
