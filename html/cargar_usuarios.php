<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';
$pdo = getDB();

// Consulta SQL para obtener todos los usuarios con su grupo
$sql = "
    SELECT 
        u.id, u.nombre, u.email, u.poblacion, u.telefono, g.nombre AS grupo
    FROM 
        usuario u
    LEFT JOIN 
        usuario_grupo ug ON u.id = ug.usuario_id
    LEFT JOIN 
        grupo g ON ug.grupo_id = g.id
";

// Preparar la consulta y ejecutarla
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Recuperar los resultados de la consulta
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los resultados en formato JSON
echo json_encode([
    'data' => $usuarios
]);
?>
    