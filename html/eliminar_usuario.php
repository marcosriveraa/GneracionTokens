<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';
$pdo = getDB();

// Obtener el ID del usuario a eliminar
$usuarioId = $_POST['id'] ?? null;

if ($usuarioId) {
    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Eliminar al usuario del grupo si está asignado a alguno
        $sql = "DELETE FROM usuario_grupo WHERE usuario_id = :usuarioId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':usuarioId' => $usuarioId]);

        // Eliminar al usuario de la tabla 'usuario'
        $sql = "DELETE FROM usuario WHERE id = :usuarioId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':usuarioId' => $usuarioId]);

        // Confirmar la transacción
        $pdo->commit();

        // Respuesta exitosa
        echo json_encode([
            'success' => true,
            'message' => 'Usuario eliminado correctamente.'
        ]);
    } catch (Exception $e) {
        // Si ocurre un error, revertir transacción y devolver mensaje de error
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ID de usuario no proporcionado.'
    ]);
}
?>