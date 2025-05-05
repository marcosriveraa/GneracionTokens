<?php
include 'conexion.php';
$pdo = getDB();

// Establecer la zona horaria a Madrid
date_default_timezone_set('Europe/Madrid');

$data = json_decode(file_get_contents("php://input"), true);

$tipoToken = strtoupper($data['tipo']);  // Lo ponemos en mayúsculas por seguridad
$entidadId = $data['entidad_id'];
$token = $data['token'];
$numeroUsos = $data['numero_uso'] ?? null;
$fechaExpiracion = $data['fecha_expiracion'] ?? null;

if (empty($tipoToken) || empty($entidadId) || empty($token)) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

try {
    // Obtener la fecha y hora actuales en formato completo (YYYY-MM-DD HH:MM:SS)
    $fechaCreacion = date('Y-m-d H:i:s');

    $sql = "
        INSERT INTO token (tipo_token, id_entidad, token, fecha_creacion, fecha_expiracion, usos_maximos, usos_restantes)
        VALUES (:tipo_token, :id_entidad, :token, :fecha_creacion, :fecha_expiracion, :usos_maximos, :usos_restantes)
    ";

    // Solo guardamos usos y fecha si es finito
    $usosMaximos = null;
    $usosRestantes = null;
    $expiracion = null;

    if ($tipoToken === 'FINITO_USO' || $tipoToken === 'FINITO_MENSUAL') {
        $usosMaximos = (int)$numeroUsos;
        $usosRestantes = (int)$numeroUsos;
        $expiracion = !empty($fechaExpiracion) ? $fechaExpiracion : null;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':tipo_token' => $tipoToken,
        ':id_entidad' => $entidadId,
        ':token' => $token,
        ':fecha_creacion' => $fechaCreacion,  // Guardar fecha y hora completas
        ':fecha_expiracion' => $expiracion,
        ':usos_maximos' => $usosMaximos,
        ':usos_restantes' => $usosRestantes
    ]);

    echo json_encode(['success' => true, 'message' => 'Token generado y guardado exitosamente']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el token: ' . $e->getMessage()]);
}
?>
