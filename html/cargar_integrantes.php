<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';
$pdo = getDB();

// Obtener el ID del grupo
$grupoId = isset($_GET['grupoId']) ? $_GET['grupoId'] : '';

// Validar si el grupoId no está vacío y parece un UUID (36 caracteres típicos)
if (empty($grupoId) || strlen($grupoId) !== 36) {
    echo json_encode(['error' => 'ID de grupo no válido o no proporcionado.']);
    exit;
}

// Consulta SQL para obtener los integrantes del grupo
$sql = "
    SELECT u.nombre, u.email, u.telefono
    FROM usuario u
    JOIN usuario_grupo ug ON u.id = ug.usuario_id
    WHERE ug.grupo_id = :grupoId
";

// Preparar la consulta y ejecutarla
$stmt = $pdo->prepare($sql);
$stmt->execute([':grupoId' => $grupoId]);

// Recuperar los resultados de la consulta
$integrantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si hay resultados
if (empty($integrantes)) {
    echo json_encode(['error' => 'No se encontraron integrantes para este grupo.']);
} else {
    // Devolver los resultados en formato JSON
    echo json_encode([
        'data' => $integrantes
    ]);
}
?>
