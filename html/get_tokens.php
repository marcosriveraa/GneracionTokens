<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';
$pdo = getDB();

// Consulta para obtener todos los tokens y verificar la entidad en las tablas de usuario, grupo, empresa o aplicacion
$sql = "
    SELECT t.id, t.tipo_token, t.token, t.fecha_creacion, t.fecha_expiracion, t.usos_maximos, t.usos_restantes,
    CASE 
        WHEN u.id IS NOT NULL THEN 'Usuario: ' || u.nombre
        WHEN g.id IS NOT NULL THEN 'Grupo: ' || g.nombre
        WHEN em.id IS NOT NULL THEN 'Empresa: ' || em.nombre
        WHEN a.uuid IS NOT NULL THEN 'Aplicación: ' || a.nombre
        ELSE 'Entidad no encontrada'
    END AS entidad_nombre
    FROM token t
    LEFT JOIN usuario u ON t.id_entidad = u.id
    LEFT JOIN grupo g ON t.id_entidad = g.id
    LEFT JOIN empresa em ON t.id_entidad = em.id
    LEFT JOIN aplicacion a ON t.id_entidad = a.uuid
";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tokens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    echo json_encode(['success' => true, 'tokens' => $tokens]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener los tokens: ' . $e->getMessage()]);
}
?>
