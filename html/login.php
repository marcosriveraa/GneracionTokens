<?php session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Login</title>
  <!-- Enlazar a Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Enlazar a la fuente Montserrat desde Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    /* Aplicar la fuente Montserrat a todo el cuerpo */
    body {
      font-family: 'Montserrat', sans-serif;
    }
    
    .login-container {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f4f7fc;
    }

    .login-form {
      background: #fff;
      padding: 35px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
    }

    .login-form img {
      max-width: 220px;
      margin-bottom: 30px;
    }

    .btn-primary {
      background-color: #b31e1c;
      border-color: #b31e1c;
    }

    .btn-primary:hover {
      background-color: #791317;
      border-color: #791317;
    }

    h2 {
      font-weight: 600;
    }

    label {
      font-weight: 400; /* Peso normal para las etiquetas */
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-form">
      <!-- Logotipo -->
      <img src="atalaya.jpg" alt="Logo" class="img-fluid d-block mx-auto">
      
      <!-- Formulario de Login -->
      <h2 class="text-center mb-4">Iniciar Sesión</h2>
      <form action="logindash.php" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Usuario</label>
          <input type="text" class="form-control" id="username" name="username" required placeholder="Introduce tu correo electrónico o usuario">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" required placeholder="Introduce tu contraseña">
        </div>
        <button type="submit" class="btn btn-primary w-100">Acceder</button>
      </form>
    </div>
  </div>

  <!-- Enlazar a los scripts de Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
