<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';

$pdo = getDB();

// Mostrar los datos recibidos para depuración
var_dump($_POST);

// Recoger datos del formulario
$nombre = $_POST['nombreCompleto'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$poblacion = $_POST['poblacion'] ?? '';
$provincia = $_POST['provincia'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$password = $_POST['password'] ?? '';
$grupo = $_POST['grupo'] ?? '';  // El grupo seleccionado (UUID)

// Verificar que todos los campos estén completos
if ($nombre && $username && $email && $poblacion && $provincia && $telefono && $password && $grupo) {
    // Hashear la contraseña con SHA-256
    $hashedPassword = hash('sha256', $password);

    // ✅ Insertar usuario y que la BD genere automáticamente el UUID
    $sql = "
        INSERT INTO usuario (
            username, email, password, nombre, provincia, poblacion, telefono
        ) VALUES (
            :username, :email, :password, :nombre, :provincia, :poblacion, :telefono
        )
        RETURNING id  -- ✅ Devuelve el UUID generado por Postgres
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username'  => $username,
        ':email'     => $email,
        ':password'  => $hashedPassword,
        ':nombre'    => $nombre,
        ':provincia' => $provincia,
        ':poblacion' => $poblacion,
        ':telefono'  => $telefono,
    ]);

    // ✅ Recoger el UUID generado
    $usuario_id = $stmt->fetchColumn();

    // Insertar la relación entre usuario y grupo
    $sqlGrupoUsuario = "
        INSERT INTO usuario_grupo (usuario_id, grupo_id)
        VALUES (:usuario_id, :grupo_id)
    ";

    $stmtGrupo = $pdo->prepare($sqlGrupoUsuario);
    $stmtGrupo->execute([
        ':usuario_id' => $usuario_id,
        ':grupo_id'   => $grupo,  // El ID del grupo (UUID)
    ]);

    echo "Usuario y grupo añadidos correctamente. ID usuario: $usuario_id";
} else {
    echo "Faltan datos";
}
?>
