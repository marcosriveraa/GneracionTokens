<?php
session_start();

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $pdo = getDB();

    $username = $_POST['username'];
    $password = $_POST['password'];

    $smtp = $pdo->prepare("SELECT * FROM login_dashboard WHERE email = :username OR username = :username");
    $smtp->execute(['username' => $username]);
    $user = $smtp->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $hashedPassword = hash('sha256', $password);

        if ($hashedPassword === $user['password']) {
            // Guardamos los datos como 'usuario' para que funcione en index.php
            $_SESSION['usuario'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email']
            ];

            header('Location: index.php');
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    // Si se accede directamente sin enviar el formulario
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
