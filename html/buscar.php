<?php
include('conexion.php');

// Verifica si el parámetro 'search' se recibe correctamente
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if (!$searchTerm) {
    // Si no se proporciona el término de búsqueda, devuelve un error 400
    http_response_code(400);
    echo json_encode(["error" => "No se proporcionó el término de búsqueda."]);
    exit;
}

try {
    // Conectar a la base de datos
    $conn = getDB();

    // Consulta SQL para buscar en las tablas (usuarios, grupos, aplicaciones, empresas)
    $query = "
        (SELECT 'usuario' AS tipo, id, nombre FROM usuario WHERE nombre LIKE :searchTerm)
        UNION
        (SELECT 'grupo' AS tipo, id, nombre FROM grupo WHERE nombre LIKE :searchTerm)
        UNION
        (SELECT 'aplicacion' AS tipo, uuid AS id, nombre FROM aplicacion WHERE nombre LIKE :searchTerm)
        UNION
        (SELECT 'empresa' AS tipo, id, nombre FROM empresa WHERE nombre LIKE :searchTerm)
    ";

    // Prepara la consulta
    $smpt = $conn->prepare($query);
    $smpt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);

    // Ejecutar la consulta
    $smpt->execute();

    // Obtener los resultados
    $results = $smpt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados como JSON
    echo json_encode($results);
} catch (PDOException $e) {
    // Si ocurre un error con la base de datos, devuelve un error 500
    http_response_code(500);
    echo json_encode(["error" => "Error en la base de datos: " . $e->getMessage()]);
} catch (Exception $e) {
    // Si ocurre un error general, devuelve un error 500
    http_response_code(500);
    echo json_encode(["error" => "Error general: " . $e->getMessage()]);
}
?>
