<?php session_start(); 
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestión de Tokens</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
    }

    #integrantesTable_wrapper {
  overflow-x: auto;
}


    .navbar {
      background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco semi-transparente */
      backdrop-filter: blur(10px); /* Efecto de desenfoque */
      border-bottom: 1px solid #e0e0e0; /* Línea de separación sutil */
    }

    .navbar-nav .nav-item {
      margin-right: 20px;
    }

    .navbar-nav .nav-link {
      color: #333; /* Color oscuro para el texto */
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
      color: #007bff; /* Cambia el color a azul al pasar el mouse */
      background-color: transparent; /* Sin fondo de color */
    }

    .navbar-nav .nav-link.active {
      color: #0056b3; /* Color para el enlace activo */
    }

    .user-info {
      display: flex;
      align-items: center;
      color: #333; /* Color oscuro */
    }

    .user-info img {
      width: 35px;
      height: 35px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #fff;
      margin-right: 10px;
    }

    .container {
      margin-top: 70px; /* Ajuste para el navbar */
      padding: 20px;
    }

    /* Estilo para el título */
    h1 {
      color: #333;
      font-weight: 600;
    }

    .btn-add {
      margin-bottom: 20px; /* Espacio entre el botón y la tabla */
    }

    

    .fade-out {
  opacity: 0; /* Establece la opacidad a 0 cuando se desvaneció */
  transition: opacity 1s ease-out; /* Desvanecimiento gradual */
}
  </style>
</head>
<body>

  <!-- Navbar superior -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top">
  <div class="container-fluid">
    <!-- Logo / Nombre de la Aplicación -->
    <a class="navbar-brand" href="#">Gestión de Tokens</a>

    <!-- Usuario logueado -->
    <div class="user-info ms-auto">
      <img src="admin_avatar.png" alt="Usuario">
      <div>
        <h6><?php echo isset($_SESSION['usuario']['username']) ? $_SESSION['usuario']['username'] : 'Usuario'; ?></h6>
        <small><?php echo isset($_SESSION['usuario']['email']) ? $_SESSION['usuario']['email'] : 'correo@ejemplo.com'; ?></small>
      </div>
    </div>

    <!-- Botón del menú desplegable -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Elementos del menú -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" href="#" id="dashboardLink">
            <i class="bi bi-house-door-fill me-2"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" id="aplicacionesLink">
            <i class="bi bi-app-indicator me-2"></i> Aplicaciones
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" id="grupousuLink">
            <i class="bi bi-people-fill me-2"></i> Grupo de Usuarios
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" id="usuariosLink">
            <i class="bi bi-person-circle me-2"></i> Usuarios
          </a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="#" id="empresasLink">
        <i class="bi bi-building me-2"></i> Empresas
        </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">
            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

  <div class="container d-none" id="appContainer">
    <h2 class="text-center">Lista de Aplicaciones</h2>
    <p class="text-center">Aquí puedes gestionar las aplicaciones.</p>
    <div id="alertContainer"></div>
    <!-- Botón para añadir una nueva aplicación -->
    <button class="btn" style="background-color: #791317; color: white;" data-bs-toggle="modal" data-bs-target="#addAppModal">
  <i class="bi bi-plus-circle me-2 padding: 5px; border-radius: 50%;"></i> Añadir Aplicación
</button>

    <!-- Tabla para DataTable -->
    <table id="appTable" class="display" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>Uuid</th>
          <th>Nombre</th>
          <th>Fecha de Creación</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Aquí se cargarán los datos de las aplicaciones dinámicamente -->
      </tbody>
    </table>
  </div>
  <div id="dashboardContainer" class="d-none">
    <div class="container">
        <h2 class="text-center">Dashboard</h2>
        <p class="text-center">Bienvenido al Dashboard. Aquí puedes ver estadísticas y datos relevantes.</p>
        <!--
        <div class="mb-3">
            <label for="filtroGrupo" class="form-label">Filtrar por Grupo</label>
            <select id="filtroGrupo" class="form-select">
                <option value="" disabled selected>Seleccione un grupo</option>
                <option value="aplicaciones">Aplicaciones</option>
                <option value="grupos_usuarios">Grupos de Usuarios</option>
                <option value="usuarios">Usuarios</option>
                <option value="empresas">Empresas</option>
            </select>
        </div>
-->
<button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#buscadorModal" style="background-color: #791317; color: white;">
  <i class="bi bi-plus-circle me-2" padding: 5px; border-radius: 50%;></i> Generar Token
</button>


        <!-- DataTable para mostrar los tokens u otros datos -->
        <table id="tokenTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Token</th>
                    <th>Fecha Creación</th>
                    <th>Fecha Expiración</th>
                    <th>Usos Permitidos</th>
                    <th>Usos Restantes</th>
                </tr>
            </thead>
            <tbody>
                <!-- Las filas se cargarán dinámicamente -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Buscar Entidades -->
<div class="modal fade" id="buscadorModal" tabindex="-1" aria-labelledby="buscadorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4">
      <div class="modal-header text-white" style="background-color: #791317;">
        <h5 class="modal-title" id="buscadorModalLabel"><i class="bi bi-search"></i> Buscar Entidades</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Selector -->
        <div class="row g-3 align-items-end mb-3">
          <div class="col-md-6">
            <label for="tipoEntidad" class="form-label">Tipo de entidad</label>
            <select class="form-select" id="tipoEntidad" onchange="cargarTabla()">
              <option value="aplicaciones">Aplicaciones</option>
              <option value="grupos">Grupos</option>
              <option value="usuarios">Usuarios</option>
              <option value="empresas">Empresas</option>
            </select>
          </div>
        </div>

        <!-- Tabla con DataTable -->
        <div class="table-responsive">
          <table id="tablaEntidades" class="table table-striped table-bordered w-100">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <!-- Se llenará dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>





<!-- Contenedor para Grupo de Usuarios -->
<div id="grupousuContainer" class="d-none">
    <div class="container">
        <h2 class="text-center">Grupo de Usuarios</h2>
        <p class="text-center">Aquí puedes gestionar los grupos de usuarios.</p>
        <div id="alertContainer2"></div>
        <!-- Botón para añadir un nuevo grupo -->
        <button class="btn btn-add" style="background-color: #791317; color: white;" data-bs-toggle="modal" data-bs-target="#addGroupModal">
  <i class="bi bi-plus-circle me-2"></i> Añadir Grupo
</button>


        <!-- Tabla para mostrar los grupos -->
        <table id="groupTable" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre del Grupo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se cargarán los datos dinámicamente -->
            </tbody>
        </table>
    </div>
</div>

<!-- Contenedor para Usuarios -->
<div id="usuariosContainer" class="d-none">
    <div class="container">
        <h2 class="text-center">Usuarios</h2>
        <p class="text-center">Aquí puedes gestionar los usuarios.</p>
        <div id="alertContainer3"></div>
        <button class="btn btn-add" style="background-color: #791317; color: white;" data-bs-toggle="modal" data-bs-target="#addUsuario">
  <i class="bi bi-plus-circle me-2"></i> Añadir Usuario
</button>

        <!-- Tabla para mostrar los usuarios -->
        <table id="usersTable" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Población</th>
                    <th>Telefono</th>
                    <th>Grupo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se cargarán los datos dinámicamente -->
            </tbody>
        </table>
    </div>
</div>

<!-- Contenedor para Empresas -->
<div id="empresasContainer" class="container d-none">
  <div class="container">
<h2 class="text-center">Empresas</h2>
<p class="text-center">Aquí puedes gestionar las empresas</p>
<button class="btn btn-add" style="background-color: #791317; color: white;" data-bs-toggle="modal" data-bs-target="#addEmpresaModal">
  <i class="bi bi-plus-circle me-2"></i> Añadir Empresa
</button>

<table id="empresatable" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se cargarán los datos dinámicamente -->
            </tbody>
        </table>
</div>

</div>

<div class="modal fade" id="addEmpresaModal" tabindex="-1" aria-labelledby="addEmpresaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formAddEmpresa" method="POST" action="insertar_empresa.php"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmpresaModalLabel">Añadir Nueva Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreEmpresa" class="form-label">Nombre de la Empresa</label>
                        <input type="text" class="form-control" id="nombreEmpresa" name="nombreEmpresa" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailEmpresa" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="emailEmpresa" name="emailEmpresa" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefonoEmpresa" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefonoEmpresa" name="telefonoEmpresa">
                    </div>
                    <div class="mb-3">
                        <label for="direccionEmpresa" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccionEmpresa" name="direccionEmpresa">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn" style="background-color: #791317; color: white;">Guardar Empresa</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editarEmpresaModal" tabindex="-1" aria-labelledby="editarEmpresaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditarEmpresa">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="editIdEmpresa" class="form-label">ID</label>
                    <input type="text" id="editIdEmpresa" disabled class="form-control mb-3">
                    <div class="mb-3">
                        <label for="editNombreEmpresa" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editNombreEmpresa" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmailEmpresa" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmailEmpresa" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTelefonoEmpresa" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="editTelefonoEmpresa">
                    </div>
                    <div class="mb-3">
                        <label for="editDireccionEmpresa" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="editDireccionEmpresa">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="guardarEdicionEmpresa()" >Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>





  <!-- Modal para añadir una aplicación -->
  <div class="modal fade" id="addAppModal" tabindex="-1" aria-labelledby="addAppModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAppModalLabel">Añadir Nueva Aplicación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Formulario para añadir la aplicación -->
          <form action="insertar_app.php" method="POST" id="addAppForm">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre de la Aplicación</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <button type="submit" class="btn" style="background-color: #791317; color: white;">Añadir Aplicación</button>

          </form>
        </div>
      </div>
    </div>
  </div>

<!-- Modal: Añadir Usuario -->
<div class="modal fade" id="addUsuario" tabindex="-1" aria-labelledby="addUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="addUsuarioLabel">Nuevo Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para añadir usuario -->
        <form id="formAddUsuario">
          <div class="mb-3">
            <label for="nombreCompleto" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" required>
          </div>
          <div class="mb-3">
            <label for="username" class="form-label">Nombre Usuario</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="provincia" class="form-label">Provincia</label>
            <input type="text" class="form-control" id="provincia" name="provincia" required>
          </div>
          <div class="mb-3">
            <label for="poblacion" class="form-label">Población</label>
            <input type="text" class="form-control" id="poblacion" name="poblacion" required>
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="mb-3">
            <label for="grupo" class="form-label">Grupo</label>
            <select class="form-select" id="grupo" name="grupo" required>
              <option value="" disabled selected>Seleccione un grupo</option>
              <!-- Opciones de grupos cargadas dinámicamente -->
            </select>
          </div>
          <button type="submit" class="btn" style="background-color: #791317; color: white;">Añadir Usuario</button>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>





  <!-- Modal para añadir grupo -->
<div class="modal fade" id="addGroupModal" tabindex="-1" aria-labelledby="addGroupModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addGroupForm">
        <div class="modal-header">
          <h5 class="modal-title" id="addGroupModalLabel">Añadir Nuevo Grupo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nombreGrupo" class="form-label">Nombre del Grupo</label>
            <input type="text" class="form-control" id="nombreGrupo" name="nombreGrupo" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn" style="background-color: #791317; color: white;">Guardar Grupo</button>          
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de integrantes -->
<div class="modal fade" id="modalIntegrantes" tabindex="-1" aria-labelledby="modalIntegrantesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalIntegrantesLabel">Integrantes del grupo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <ul id="listaIntegrantes" class="list-group">
          <!-- Aquí se cargarán los integrantes -->
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Modal para ver los integrantes -->
<div class="modal fade" id="integrantesModal" tabindex="-1" aria-labelledby="integrantesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="integrantesModalLabel">Integrantes del Grupo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Tabla para mostrar los integrantes -->
        <table class="table table-striped" id="integrantesTable">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Email</th>
              <th>Telefono</th>
            </tr>
          </thead>
          <tbody>
            <!-- Aquí se cargarán los integrantes -->
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal para editar usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditarUsuario">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="editIdUsuario" class="form-label">ID</label>
            <input type="text" id="editIdUsuario" class="form-control" disabled>
          </div>

          <div class="mb-3">
            <label for="editNombreUsuario" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="editNombreUsuario" required>
          </div>

          <div class="mb-3">
            <label for="editEmailUsuario" class="form-label">Email</label>
            <input type="email" class="form-control" id="editEmailUsuario" required>
          </div>

          <div class="mb-3">
            <label for="editPoblacionUsuario" class="form-label">Población</label>
            <input type="text" class="form-control" id="editPoblacionUsuario">
          </div>

          <div class="mb-3">
            <label for="editTelefonoUsuario" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="editTelefonoUsuario">
          </div>

          <div class="mb-3">
  <label for="editGrupoUsuario" class="form-label">Grupo</label>
  <select class="form-select" id="editGrupoUsuario" required>
    <option value="" disabled selected>Seleccione un grupo</option>
    
  </select>
</div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="guardarEdicionUsuario()">Guardar cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Modal para editar aplicación -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Aplicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="edit-nombre" class="form-label">Nombre</label>
                        <input type="text" id="edit-nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-uuid" class="form-label">UUID</label>
                        <input type="text" id="edit-uuid" class="form-control" disabled>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Botón de cerrar -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <!-- Botón de guardar -->
                <button type="button" class="btn btn-primary" onclick="guardarEdicion()">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar grupo -->
<div class="modal fade" id="editModalgroup" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editFormGroup">
                    <div class="mb-3">
                        <label for="edit-nombregrupo" class="form-label">Nombre del Grupo</label>
                        <input type="text" id="edit-nombregrupo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-idgrupo" class="form-label">ID del Grupo</label>
                        <input type="text" id="edit-idgrupo" class="form-control" disabled>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEdicionGrupo()">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para generar token -->
<div class="modal fade" id="modalGenerarToken" tabindex="-1" aria-labelledby="modalGenerarTokenLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalGenerarTokenLabel">Generar Token</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formGenerarToken">
          <div class="mb-3">
            <label for="tipoToken" class="form-label">Tipo de Token</label>
            <select class="form-select" id="tipoToken" required>
              <option value="infinito">Infinito</option>
              <option value="finito_uso">Finito con número de usos</option>
              <option value="finito_mensual">Finito mensual</option>
            </select>
          </div>
          <div id="finitoOptions" style="display:none;">
            <div class="mb-3">
              <label for="numeroUsos" class="form-label">Número de Usos</label>
              <input type="number" class="form-control" id="numeroUsos" placeholder="Ej. 5" min="1" />
            </div>
            <div class="mb-3">
              <label for="fechaExpiracion" class="form-label" id="labelfecha">Fecha de Expiración</label>
              <input type="date" class="form-control" id="fechaExpiracion" />
            </div>
          </div>
          <button type="submit" class="btn" style="background-color: #791317; color: white;">Generar Token</button>
        </form>
      </div>
    </div>
  </div>
</div>


  <!-- Scripts de Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.js"></script>


  <script>

function cargarTokens() {
    fetch('get_tokens.php')  // Realizamos la petición al script PHP
        .then(response => response.json())  // Parseamos la respuesta JSON
        .then(data => {
            if (data.success) {  // Si la respuesta es exitosa
                const tokensData = [];

                // Iterar sobre los tokens obtenidos y agregar cada uno a los datos
                data.tokens.forEach(token => {
                    // Si existe fecha de expiración, la mostramos, si no, dejamos vacío
                    const fechaExp = token.fecha_expiracion ? token.fecha_expiracion : '';

                    // Si tiene usos restantes, los mostramos, si no, dejamos vacío
                    const usosRestantes = token.usos_restantes !== null ? token.usos_restantes : '';
                    
                    const usos = token.usos_maximos !== null ? token.usos_maximos : '';
                    // Crear una fila para cada token
                    const row = [
                        token.entidad_nombre,
                        token.tipo_token,
                        token.token,
                        token.fecha_creacion,
                        fechaExp,       // Nueva columna: fecha de expiración
                        usos,
                        usosRestantes   // Nueva columna: usos permitidos restantes
                    ];

                    // Añadir la fila al array de datos
                    tokensData.push(row);
                });

                // Obtener el DataTable existente
                const table = $('#tokenTable').DataTable();

                // Limpiar el DataTable antes de agregar nuevos datos
                table.clear();

                // Agregar las nuevas filas al DataTable
                table.rows.add(tokensData);

                // Redibujar la tabla para aplicar el buscador y otras funciones
                table.draw();
            } else {
                console.error('Error al cargar los tokens:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al obtener los tokens:', error);
        });
}

    document.addEventListener("DOMContentLoaded", function () {
    const empresasLink = document.querySelector('#empresasLink'); // Enlace de Empresas
    const empresasContainer = document.getElementById('empresasContainer'); // Contenedor de Empresas
    const aplicacionesLink = document.getElementById('aplicacionesLink');
    const appContainer = document.getElementById('appContainer');
    const dashboardLink = document.getElementById('dashboardLink');
    const dashboardContainer = document.getElementById('dashboardContainer');
    const grupousuLink = document.getElementById('grupousuLink');
    const grupousuContainer = document.getElementById('grupousuContainer');
    const usuariosLink = document.getElementById('usuariosLink');
    const usuariosContainer = document.getElementById('usuariosContainer');
    cargarTokens(); // Cargar los tokens al inicio
    // Función para activar el enlace correspondiente
    function activarEnlace(seccion) {
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        if (seccion === 'aplicaciones') {
            aplicacionesLink.classList.add('active');
        } else if (seccion === 'dashboard') {
            dashboardLink.classList.add('active');
        } else if (seccion === 'grupoUsuarios') {
            grupousuLink.classList.add('active');
        } else if (seccion === 'usuarios') {
            usuariosLink.classList.add('active');
        } else if (seccion === 'empresas') {
            empresasLink.classList.add('active');
        }
    }

    // Función para ocultar todos los contenedores
    function ocultarContenedores() {
        const contenedores = [appContainer, dashboardContainer, grupousuContainer, usuariosContainer, empresasContainer];
        contenedores.forEach(container => {
            container.classList.add('d-none');
        });
    }

    // Evento para mostrar la sección de Empresas
    empresasLink.addEventListener('click', function (event) {
        event.preventDefault();
        console.log("Cargando contenedor de empresas...");  // Añadido para depuración
        ocultarContenedores();
        recargarDataTableEmpresas(); // Llamar a la función para cargar los datos de empresas
        empresasContainer.classList.remove('d-none'); // Mostrar el contenedor de Empresas
        activarEnlace('empresas'); // Activar el enlace de Empresas
    });

    // Evento para mostrar la sección de grupo de usuarios
    grupousuLink.addEventListener('click', function (event) {
        event.preventDefault();
        ocultarContenedores();
        grupousuContainer.classList.remove('d-none');
        activarEnlace('grupoUsuarios');
    });

    // Evento para mostrar la sección de usuarios
    usuariosLink.addEventListener('click', function (event) {
        event.preventDefault();
        ocultarContenedores();
        usuariosContainer.classList.remove('d-none');
        cargarGruposCombo(); // Cargar grupos en el combo al abrir la sección de usuarios
        activarEnlace('usuarios');
    });

    // Evento para mostrar la sección de aplicaciones
    aplicacionesLink.addEventListener('click', function (event) {
        event.preventDefault();
        ocultarContenedores();
        appContainer.classList.remove('d-none');
        activarEnlace('aplicaciones');
    });

    // Evento para mostrar la sección de dashboard
    dashboardLink.addEventListener('click', function (event) {
        event.preventDefault();
        ocultarContenedores();
        dashboardContainer.classList.remove('d-none');
        activarEnlace('dashboard');
    });

    // Al cargar la página, mostrar Dashboard por defecto
    ocultarContenedores();
    dashboardContainer.classList.remove('d-none');
    activarEnlace('dashboard');

    // Función para ocultar todos los contenedores
function ocultarContenedores() {
    const contenedores = [appContainer, dashboardContainer, grupousuContainer, usuariosContainer, empresasContainer];
    contenedores.forEach(container => {
        if (container) {
            container.classList.add('d-none');
        } else {
            console.warn('Contenedor no encontrado:', container);
        }
    });
}
});



function recargarDataTable() {
    // Obtener los datos desde el servidor PHP
    fetch('cargar_app.php')
        .then(response => response.json())
        .then(data => {
            // Verificamos si hay error en la respuesta
            if (!data.data) {
                console.error('No se recibieron datos válidos.');
                return;
            }

            // Obtener la instancia de la tabla
            let table = $("#appTable").DataTable();
            table.clear(); // Limpiar los datos previos

            // Recorrer los datos obtenidos y agregarlos a la tabla
            data.data.forEach(aplicacion => {
                // Agregar fila con los botones de eliminar y editar
                table.row.add([
                    aplicacion.uuid,
                    aplicacion.nombre,
                    aplicacion.fecha_creacion,
                    // Columna de acciones con el botón de eliminar
                    `<button class="btn btn-danger btn-sm" onclick="eliminarAplicacion('${aplicacion.uuid}')">Eliminar</button>
                     <button class="btn btn-primary btn-sm" onclick="editarAplicacion('${aplicacion.uuid}', '${aplicacion.nombre}')">Editar</button>`
                ]).draw(false); // draw(false) para evitar el redibujado completo en cada fila
            });
        })
        .catch(error => console.error("Error al cargar los datos:", error));
}

function editarAplicacion(uuid, nombre) {
    // Rellenar los campos del modal con los valores actuales
    document.getElementById("edit-uuid").value = uuid;
    document.getElementById("edit-nombre").value = nombre;

    // Mostrar el modal
    $('#editModal').modal('show');
}

function guardarEdicion() {
    // Obtener los valores del formulario
    const uuid = document.getElementById("edit-uuid").value;
    const nombre = document.getElementById("edit-nombre").value;

    // Verificar que se ha ingresado un nombre
    if (!nombre) {
        alert("El nombre es obligatorio.");
        return;
    }

    // Enviar los datos al servidor para actualizar la aplicación
    fetch('editar_app.php', {
        method: 'POST',
        body: JSON.stringify({ uuid, nombre }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Aplicación actualizada exitosamente.");
            $('#editModal').modal('hide'); // Cerrar el modal
            recargarDataTable(); // Recargar la tabla con los datos actualizados
        } else {
            alert("Error al actualizar la aplicación.");
        }
    })
    .catch(error => console.error("Error al guardar la edición:", error));
}



  // Función para recargar la tabla de usuarios con DataTable usando row.add()
  function recargarDataTableUsuarios() {
    fetch('cargar_usuarios.php')  // Asegúrate de que el archivo PHP esté correctamente vinculado
        .then(response => response.json())
        .then(data => {
            console.log(data);  // Ver los datos recibidos

            // Obtener la instancia del DataTable
            let table = $('#usersTable').DataTable();

            // Limpiar las filas de la tabla antes de añadir nuevas
            table.clear();

            // Recorrer los usuarios y agregar filas a la tabla utilizando row.add()
            data.data.forEach(usuario => {
                // Crear una fila con los datos del usuario
                let rowData = [
                    usuario.id,            // Columna ID
                    usuario.nombre,        // Columna Nombre
                    usuario.email,         // Columna Email
                    usuario.poblacion,     // Columna Población
                    usuario.telefono,      // Columna Teléfono
                    usuario.grupo || "Sin Grupo",  // Columna Grupo
                    `<button class="btn btn-danger btn-sm" onclick="eliminarUsuario('${usuario.id}')">Eliminar</button>
                    <button class="btn btn-primary btn-sm me-2" onclick="modificarUsuario(
        '${usuario.id}', 
        '${usuario.nombre}', 
        '${usuario.email}', 
        '${usuario.poblacion}', 
        '${usuario.telefono}', 
        '${usuario.grupo || ''}'
    )">
        Modificar
    </button>`  // Asegúrate de que el ID esté entre comillas
                ];

                // Añadir la fila a la tabla
                table.row.add(rowData);
            });

            // Renderizar la tabla
            table.draw();
        })
        .catch(error => {
            console.error('Error al cargar los usuarios:', error);
        });
}

function modificarUsuario(id, nombre, email, poblacion, telefono, grupo) {
    // Rellenar los campos del formulario con los datos recibidos
    document.getElementById('editIdUsuario').value = id;
    document.getElementById('editNombreUsuario').value = nombre;
    document.getElementById('editEmailUsuario').value = email;
    document.getElementById('editPoblacionUsuario').value = poblacion;
    document.getElementById('editTelefonoUsuario').value = telefono;
    document.getElementById('editGrupoUsuario').value = grupo;

    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('editarUsuarioModal'));
    modal.show();
}



  function cargarGruposCombo() {
    fetch('cargar_grupo.php') // Petición a cargar_grupo.php
        .then(response => response.json())
        .then(data => {
            console.log(data); // Ver los datos recibidos

            let selectGrupo = document.getElementById('grupo');
            selectGrupo.innerHTML = '<option value="" disabled selected>Seleccione un grupo</option>'; // Limpiar el select

            // Comprobar si 'data' es un array
            if (Array.isArray(data.data)) {
                data.data.forEach(grupo => {
                    let option = document.createElement('option');
                    option.value = grupo.id;  // Valor del option es el ID del grupo
                    option.textContent = grupo.nombre;  // Texto del option es el nombre del grupo
                    selectGrupo.appendChild(option);
                });
            } else {
                console.error('Los datos recibidos no son un array');
            }
        })
        .catch(error => {
            console.error('Error al cargar los grupos:', error);
        });
}

function cargarGruposComboEdit(grupoActualNombre) {
    // Asegúrate de que el valor de grupoActualNombre se pasa correctamente
    console.log("Nombre del grupo actual:", grupoActualNombre);  // Depuración para verificar el grupo actual
    
    // Hacer la solicitud para obtener los grupos
    fetch('cargar_grupo.php')
        .then(response => response.json())
        .then(data => {
            const selectGrupo = document.getElementById('editGrupoUsuario');
            selectGrupo.innerHTML = '<option value="" disabled>Seleccione un grupo</option>'; // Primer opción deshabilitada

            if (Array.isArray(data.data)) {
                data.data.forEach(grupo => {
                    const option = document.createElement('option');
                    option.value = grupo.id; // ID del grupo es el valor del option
                    option.textContent = grupo.nombre; // Nombre del grupo es el texto visible

                    // Marcar como seleccionado si el nombre del grupo coincide con el nombre actual
                    if (grupo.nombre === grupoActualNombre) {
                        option.selected = true; // Si el nombre del grupo coincide con el grupo actual, marcarlo como seleccionado
                    }

                    selectGrupo.appendChild(option); // Añadir la opción al select
                });
            } else {
                console.error('Los datos recibidos no son un array');
            }
        })
        .catch(error => {
            console.error('Error al cargar los grupos:', error);
        });
}




function modificarUsuario(id, nombre, email, poblacion, telefono, grupoId) {
    document.getElementById('editIdUsuario').value = id;
    document.getElementById('editNombreUsuario').value = nombre;
    document.getElementById('editEmailUsuario').value = email;
    document.getElementById('editPoblacionUsuario').value = poblacion;
    document.getElementById('editTelefonoUsuario').value = telefono;

    cargarGruposComboEdit(grupoId); // Cargar los grupos con el grupo actual seleccionado

    const modal = new bootstrap.Modal(document.getElementById('editarUsuarioModal'));
    modal.show();
}

function guardarEdicionUsuario() {
    const id = document.getElementById('editIdUsuario').value;
    const nombre = document.getElementById('editNombreUsuario').value;
    const email = document.getElementById('editEmailUsuario').value;
    const poblacion = document.getElementById('editPoblacionUsuario').value;
    const telefono = document.getElementById('editTelefonoUsuario').value;
    const grupo = document.getElementById('editGrupoUsuario').value;

    fetch('editar_usuario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, nombre, email, poblacion, telefono, grupo })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editarUsuarioModal'));
            modal.hide(); // Cerrar el modal
            recargarDataTableUsuarios(); // Recargar la tabla de usuarios
        } else {
            console.error("Error del servidor:", data.message);
            alert("Error al guardar los cambios.");
        }
    })
    .catch(error => {
        console.error("Error al guardar la edición:", error);
    });
}



function recargarDataTableGrupo() {
    fetch('cargar_grupo.php')  // Asegúrate de que el archivo PHP esté correctamente vinculado
        .then(response => response.json())
        .then(data => {
            let table = $("#groupTable").DataTable();
            table.clear(); // Limpiar los datos previos

            data.data.forEach(grupo => {
                // Agregar fila con los botones para editar, eliminar y ver integrantes
                table.row.add([
                    grupo.id,                              // Columna ID
                    grupo.nombre,                          // Columna Nombre
                    `<button class="btn btn-primary btn-sm" onclick="editarGrupo('${grupo.id}', '${grupo.nombre}')">Editar</button>
                     <button class="btn btn-danger btn-sm" onclick="eliminarGrupo('${grupo.id}')">Eliminar</button>
                     <button class="btn btn-info btn-sm" onclick="VerIntegrantes('${grupo.id}')">Ver Integrantes</button>`
                ]);
            });

            table.draw();
        })
        .catch(error => {
            console.error("Error al cargar los datos del grupo:", error);
        });
}

function editarGrupo(grupoId, grupoNombre) {
    // Rellenar los valores en el formulario del modal
    document.getElementById("edit-idgrupo").value = grupoId;  // Asumiendo que el campo UUID es el ID del grupo
    document.getElementById("edit-nombregrupo").value = grupoNombre; // El nombre será el nombre actual del grupo

    // Abrir el modal para editar
    $('#editModalgroup').modal('show');
}

function guardarEdicionGrupo() {
    // Obtener los valores del formulario
    const uuid = document.getElementById("edit-idgrupo").value;  // Usar el ID correcto para el grupo
    const nombre = document.getElementById("edit-nombregrupo").value;  // Usar el nombre correcto

    // Verificar que se ha ingresado un nombre
    if (!nombre) {
        alert("El nombre es obligatorio.");
        return;
    }

    // Enviar los datos al servidor para actualizar el grupo
    fetch('editar_grupo.php', {
        method: 'POST',
        body: JSON.stringify({ uuid, nombre }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editModalgroup').modal('hide');  // Cerrar el modal
            recargarDataTableGrupo();  // Recargar la tabla con los datos actualizados
        } else {
            alert("Error al actualizar el grupo.");
        }
    })
    .catch(error => console.error("Error al guardar la edición:", error));
}

function recargarDataTableGrupoPrincipal() {
    fetch('cargar_grupo.php')
        .then(response => response.json())
        .then(data => {
            let table = $("#groupsTable2").DataTable();
            table.clear(); // Limpiar los datos previos

            data.data.forEach(grupo => {
                table.row.add([
                    grupo.id,
                    grupo.nombre,
                    `<button class="btn btn-info btn-sm" onclick="VerIntegrantes('${grupo.id}')">Ver Integrantes</button>`
                ]);
            });

            table.draw(); // ¡Solo un draw al final!
        })
        .catch(error => {
            console.error("Error al cargar los datos del grupo:", error);
        });
}

function recargarDataTableEmpresas() {
    fetch('cargar_empresas.php')
        .then(response => response.json())
        .then(data => {
            let table = $("#empresatable").DataTable();
            table.clear(); // Limpiar los datos previos

            data.data.forEach(empresa => {
                table.row.add([
                    empresa.id,
                    empresa.nombre,
                    empresa.email,
                    empresa.telefono,
                    empresa.direccion,
                    `<button class="btn btn-danger btn-sm" onclick="eliminarEmpresa('${empresa.id}')">Eliminar</button>
                    <button class="btn btn-primary btn-sm" onclick="modificarempresa(${empresa.id}, '${empresa.nombre}', '${empresa.email}', '${empresa.telefono}', '${empresa.direccion}')">Editar</button>`
                ]);
            });

            table.draw(); // ¡Solo un draw al final!
        })
        .catch(error => {
            console.error("Error al cargar los datos de las empresas:", error);
        });
}

function modificarempresa(id, nombre, email, telefono, direccion) {
    // Cargar los valores en el modal
    document.getElementById('editIdEmpresa').value = id;
    document.getElementById('editNombreEmpresa').value = nombre;
    document.getElementById('editEmailEmpresa').value = email;
    document.getElementById('editTelefonoEmpresa').value = telefono;
    document.getElementById('editDireccionEmpresa').value = direccion;

    // Mostrar el modal
    let modal = new bootstrap.Modal(document.getElementById('editarEmpresaModal'));
    modal.show();
}

function guardarEdicionEmpresa() {
    const id = document.getElementById('editIdEmpresa').value;
    const nombre = document.getElementById('editNombreEmpresa').value;
    const email = document.getElementById('editEmailEmpresa').value;
    const telefono = document.getElementById('editTelefonoEmpresa').value;
    const direccion = document.getElementById('editDireccionEmpresa').value;

    // Enviar los datos al servidor para actualizar la empresa
    fetch('editar_empresa.php', {
        method: 'POST',
        body: JSON.stringify({ id, nombre, email, telefono, direccion }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editarEmpresaModal').modal('hide'); // Cerrar el modal
            recargarDataTableEmpresas(); // Recargar la tabla con los datos actualizados

        } else { 
        }
    })
    .catch(error => {
        console.error("Error al guardar la edición:", error);
    });
}

function eliminarEmpresa(id) {
    // Confirmar si el usuario está seguro de eliminar
    if (confirm("¿Estás seguro de eliminar esta empresa?")) {
        // Enviar la solicitud al servidor para eliminar el registro
        fetch('eliminar_empresa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id }) // Enviamos el ID de la empresa a eliminar
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Empresa eliminada correctamente', 'success'); // Mostrar alerta de éxito
                recargarDataTableEmpresas(); // Recargar la tabla de empresas
            } else {
                showAlert('Error al eliminar la empresa: ', 'danger'); // Mostrar alerta de error
            }
        })
        .catch(error => {
            console.error('Error al eliminar la empresa:', error);
        });
    }
}


// Función para eliminar una aplicación
function eliminarAplicacion(uuid) {
    // Confirmar si el usuario está seguro de eliminar
    if (confirm("¿Estás seguro de eliminar esta aplicación?")) {
        // Enviar la solicitud al servidor para eliminar el registro
        fetch('eliminar_app.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ uuid: uuid }) // Enviamos el UUID de la aplicación a eliminar
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Aplicación eliminada correctamente', 'success'); // Mostrar alerta de éxito
                recargarDataTable();
            } else {
                showAlert('Error al eliminar la aplicación: ', 'danger'); // Mostrar alerta de error
            }
        })
        .catch(error => {
            console.error('Error al eliminar la aplicación:', error);
        });
    }
}

function eliminarGrupo(id) {
    // Confirmar si el usuario está seguro de eliminar
    if (confirm("¿Estás seguro de eliminar este grupo?, Todos los usuarios pertenecientes a este grupo no estarán asignados a ningun grupo.")) {
        // Enviar la solicitud al servidor para eliminar el registro
        fetch('eliminar_grupo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id }) // Enviamos el UUID de la aplicación a eliminar
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Grupo eliminado correctamente', 'success'); // Mostrar alerta de éxito
                recargarDataTableGrupo();
            } else {
                showAlert('Error al eliminar el grupo: ', 'danger'); // Mostrar alerta de error
            }
        })
        .catch(error => {
            console.error('Error al eliminar el grupo:', error);
        });
    }
}


$(document).ready(function() {
    // Inicializar DataTable, pero sin cargar datos aún
    $('#appTable').DataTable();
    $('#groupTable').DataTable();
    $('#usersTable').DataTable();
    $('#applicationsTable2').DataTable();
    $('#usersTable2').DataTable();
    $('#groupsTable2').DataTable();
    $('#empresatable').DataTable();
    $('#tokenTable').DataTable();
    // Llamar a la función para cargar los datos de la base de datos
    recargarDataTable();
    recargarDataTableGrupo();
    recargarDataTableUsuarios();
    recargarDataTableEmpresas();
    cargarTabla();
});

document.getElementById('formAddEmpresa').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir el envío del formulario de forma tradicional

    // Obtenemos los datos del formulario
    const formData = new FormData(this);

    // Enviar los datos al archivo PHP usando fetch
    fetch('insertar_empresa.php', {
        method: 'POST',
        body: formData // Los datos del formulario se pasan en el body
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Si la inserción fue exitosa, recargar la tabla
            recargarDataTableEmpresas(); // Llamamos a la función que recarga los datos en la tabla
            showAlert('Empresa añadida correctamente', 'success'); // Mostrar alerta de éxito
            // Cerrar el modal después de insertar
            $('#addEmpresaModal').modal('hide');
            
            // Reiniciar el formulario
            document.getElementById('formAddEmpresa').reset();
        } else {
            alert('Hubo un problema al añadir la empresa');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
  });

document.getElementById('addAppForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir el envío del formulario de forma tradicional

    // Obtenemos los datos del formulario
    const formData = new FormData(this);

    // Enviar los datos al archivo PHP usando fetch
    fetch('insertar_app.php', {
        method: 'POST',
        body: formData // Los datos del formulario se pasan en el body
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Si la inserción fue exitosa, recargar la tabla
            recargarDataTable(); // Llamamos a la función que recarga los datos en la tabla
            showAlert('Aplicación añadida correctamente', 'success'); // Mostrar alerta de éxito
            // Cerrar el modal después de insertar
            $('#addAppModal').modal('hide');
            
            // Reiniciar el formulario
            document.getElementById('addAppForm').reset();
        } else {
            alert('Hubo un problema al añadir la aplicación');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
  });

  function showAlert(message, type = 'success') {
  // El tipo puede ser: success, danger, warning, info
  const alertContainer = document.getElementById('alertContainer');
  
  // Crear el div de la alerta
  const alertDiv = document.createElement('div');
  alertDiv.classList.add('alert', `alert-${type}`, 'alert-dismissible', 'fade', 'show');
  alertDiv.setAttribute('role', 'alert');
  
  // Añadir el mensaje a la alerta
  alertDiv.innerHTML = message;
  
  // Botón para cerrar la alerta
  const closeButton = document.createElement('button');
  closeButton.type = 'button';
  closeButton.classList.add('btn-close');
  closeButton.setAttribute('data-bs-dismiss', 'alert');
  closeButton.setAttribute('aria-label', 'Close');
  
  // Añadir el botón de cierre a la alerta
  alertDiv.appendChild(closeButton);
  
  // Insertar la alerta en el contenedor
  alertContainer.appendChild(alertDiv);
  
  // Añadir un pequeño retraso antes de aplicar el desvanecimiento
  setTimeout(() => {
    // Agregar la clase fade-out para desvanecer la alerta
    alertDiv.classList.remove('show');
    alertDiv.classList.add('fade-out');
  }, 4000); // Esperar 4 segundos antes de comenzar el desvanecimiento
  
  // Eliminar la alerta después de 6 segundos (con el desvanecimiento en marcha)
  setTimeout(() => {
    alertDiv.remove();
  }, 6000); // 6000 ms = 6 segundos para dejar tiempo de desvanecimiento
}
function showAlert(message, type = 'success') {
  // El tipo puede ser: success, danger, warning, info
  const alertContainer = document.getElementById('alertContainer2');
  
  // Crear el div de la alerta
  const alertDiv = document.createElement('div');
  alertDiv.classList.add('alert', `alert-${type}`, 'alert-dismissible', 'fade', 'show');
  alertDiv.setAttribute('role', 'alert');
  
  // Añadir el mensaje a la alerta
  alertDiv.innerHTML = message;
  
  // Botón para cerrar la alerta
  const closeButton = document.createElement('button');
  closeButton.type = 'button';
  closeButton.classList.add('btn-close');
  closeButton.setAttribute('data-bs-dismiss', 'alert');
  closeButton.setAttribute('aria-label', 'Close');
  
  // Añadir el botón de cierre a la alerta
  alertDiv.appendChild(closeButton);
  
  // Insertar la alerta en el contenedor
  alertContainer.appendChild(alertDiv);
  
  // Añadir un pequeño retraso antes de aplicar el desvanecimiento
  setTimeout(() => {
    // Agregar la clase fade-out para desvanecer la alerta
    alertDiv.classList.remove('show');
    alertDiv.classList.add('fade-out');
  }, 4000); // Esperar 4 segundos antes de comenzar el desvanecimiento
  
  // Eliminar la alerta después de 6 segundos (con el desvanecimiento en marcha)
  setTimeout(() => {
    alertDiv.remove();
  }, 6000); // 6000 ms = 6 segundos para dejar tiempo de desvanecimiento
}
function showAlert(message, type = 'success') {
  // El tipo puede ser: success, danger, warning, info
  const alertContainer = document.getElementById('alertContainer3');
  
  // Crear el div de la alerta
  const alertDiv = document.createElement('div');
  alertDiv.classList.add('alert', `alert-${type}`, 'alert-dismissible', 'fade', 'show');
  alertDiv.setAttribute('role', 'alert');
  
  // Añadir el mensaje a la alerta
  alertDiv.innerHTML = message;
  
  // Botón para cerrar la alerta
  const closeButton = document.createElement('button');
  closeButton.type = 'button';
  closeButton.classList.add('btn-close');
  closeButton.setAttribute('data-bs-dismiss', 'alert');
  closeButton.setAttribute('aria-label', 'Close');
  
  // Añadir el botón de cierre a la alerta
  alertDiv.appendChild(closeButton);
  
  // Insertar la alerta en el contenedor
  alertContainer.appendChild(alertDiv);
  
  // Añadir un pequeño retraso antes de aplicar el desvanecimiento
  setTimeout(() => {
    // Agregar la clase fade-out para desvanecer la alerta
    alertDiv.classList.remove('show');
    alertDiv.classList.add('fade-out');
  }, 4000); // Esperar 4 segundos antes de comenzar el desvanecimiento
  
  // Eliminar la alerta después de 6 segundos (con el desvanecimiento en marcha)
  setTimeout(() => {
    alertDiv.remove();
  }, 6000); // 6000 ms = 6 segundos para dejar tiempo de desvanecimiento
}

document.getElementById('addGroupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nombreGrupo = document.getElementById('nombreGrupo').value;

    fetch('insertar_grupo.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'nombreGrupo=' + encodeURIComponent(nombreGrupo)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Grupo guardado correctamente');
            document.getElementById('addGroupForm').reset();
            let modal = bootstrap.Modal.getInstance(document.getElementById('addGroupModal'));
            modal.hide();
            recargarDataTableGrupo(); // Recargar la tabla de grupos
        } else {
            alert('Error al guardar el grupo: ' + data.message);
        }
    });
});

document.getElementById("formAddUsuario").addEventListener("submit", function(e) {
  e.preventDefault(); // Evitar que se envíe el formulario de manera tradicional

  const formData = new FormData(this);
  console.log(formData); // Ver los datos que se están enviando
  fetch("nuevo_usuario.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.text())
  .then(response => {
    showAlert('Usuario añadido correctamente', 'success'); // Mostrar alerta de éxito
    recargarDataTableUsuarios(); // Recargar la tabla de usuarios
    document.getElementById("formAddUsuario").reset(); // Reiniciar el formulario
    $('#addUsuario').modal('hide'); // Cerrar modal con Bootstrap

  })
  .catch(error => {
    showAlert('Error al añadir el usuario: ', 'danger'); // Mostrar alerta de error
  });
});

// Función para ver los integrantes de un grupo
function VerIntegrantes(grupoId) {
    console.log("ID de grupo seleccionado:", grupoId);  // Depuración para verificar el grupoId
    
    // Mostrar el modal
    $('#integrantesModal').modal('show');
    
    // Hacer la solicitud para obtener los integrantes del grupo
    fetch(`cargar_integrantes.php?grupoId=${grupoId}`)
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos:", data);  // Depuración para verificar los datos que llegan

            // Inicializar el DataTable
            let table = $('#integrantesTable').DataTable({
                destroy: true,  // Destruir el DataTable anterior si existe
                paging: true,   // Habilitar la paginación
                searching: true, // Habilitar la búsqueda
                info: true,     // Mostrar la información de la tabla
                language: {     // Ajustar idioma (si es necesario)
                    "search": "Buscar:",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    },
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros"
                }
            });

            // Limpiar los datos previos antes de agregar los nuevos
            table.clear();

            // Verifica si hay datos en 'data'
            if (data.data && data.data.length > 0) {
                // Recorrer los integrantes y agregar filas a la tabla usando `row.add()`
                data.data.forEach(integrante => {
                    table.row.add([
                        integrante.nombre,
                        integrante.email,
                        integrante.telefono
                    ]).draw(); // Agregar la fila y redibujar la tabla
                });
            } else {
                // Si no hay datos, agregar una fila con el mensaje correspondiente
                table.row.add([
                    'No hay integrantes en este grupo.',
                    '',
                    ''
                ]).draw(); // Agregar una fila de mensaje si no hay datos
            }
        })
        .catch(error => {
            console.error("Error al cargar los integrantes:", error);
        });
}





// Función para eliminar un usuario
function eliminarUsuario(usuarioId) {
    console.log("ID del usuario a eliminar:", usuarioId); // Verifica el valor antes de pasarlo

    if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
        fetch('eliminar_usuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${encodeURIComponent(usuarioId)}` // Aseguramos que el UUID esté correctamente codificado
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                recargarDataTableUsuarios();
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error al eliminar el usuario:', error);
            alert('Hubo un problema al intentar eliminar el usuario.');
        });
    }
}

let dataTable;

function cargarTabla() {
  const tipo = document.getElementById("tipoEntidad").value;

  fetch(`buscar_entidades.php?tipo=${tipo}`)
    .then(response => response.json())
    .then(entidades => {
      console.log('Respuesta del servidor:', entidades); // Imprimir la respuesta

      if (!Array.isArray(entidades)) {
        console.error('La respuesta no es un array:', entidades);
        return;
      }

      if (!dataTable) {
        dataTable = new DataTable("#tablaEntidades", {
          language: {
            url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json"
          },
          paging: true,
          searching: true
        });
      }

      dataTable.clear();
      entidades.forEach(e => {
        const button = `<button class="btn" style="background-color: #791317; color: white;" onclick="mostrarModalGenerarToken('${e.id}')">Generar Token</button>`;
        dataTable.row.add([e.id, e.nombre, button]);
      });
      dataTable.draw();
    })
    .catch(error => {
      console.error("Error cargando datos:", error);
    });
}


function mostrarModalGenerarToken(entidadId) {
  // Mostrar el modal
  const modal = new bootstrap.Modal(document.getElementById('modalGenerarToken'));
  modal.show();

  // Limpiar el formulario y esconder las opciones de finito
  document.getElementById("formGenerarToken").reset();
  document.getElementById("finitoOptions").style.display = "none";  // Escondemos el grupo de opciones finito
  document.getElementById("fechaExpiracion").style.display = "block"; // Aseguramos que el input de fecha se muestre inicialmente
  // Guardar el ID de la entidad en el formulario
  document.getElementById("formGenerarToken").onsubmit = function (event) {
    event.preventDefault();
    generarToken(entidadId);
  };

  // Mostrar opciones específicas dependiendo del tipo de token seleccionado
  document.getElementById("tipoToken").onchange = function () {
    const tipoToken = this.value;

    if (tipoToken === "finito_uso" || tipoToken === "finito_mensual") {
      document.getElementById("finitoOptions").style.display = "block";  // Mostrar las opciones finitas (número de usos y fecha)
      // Si el tipo de token es "finito_mensual", ocultamos el campo de fecha
      if (tipoToken === "finito_mensual") {
        document.getElementById("fechaExpiracion").style.display = "none";
        document.getElementById("labelfecha").style.display = "none";
      } else {
        document.getElementById("fechaExpiracion").style.display = "block"; // Aseguramos que el input de fecha se muestre en "finito_uso"
      }
    } else {
      document.getElementById("finitoOptions").style.display = "none";  // Ocultar las opciones finitas
    }
  };

  cargarTokens(); // Cargar los tokens existentes al abrir el modal
}




function generarToken(entidadId) {
  const tipoToken = document.getElementById("tipoToken").value.toUpperCase();  // Ahora es FINITO_USO, FINITO_MENSUAL, INFINITO
  const numeroUsos = document.getElementById("numeroUsos").value;
  let fechaExpiracion = document.getElementById("fechaExpiracion").value;

  // Si es "FINITO_MENSUAL", calculamos la fecha de expiración añadiendo 30 días
  if (tipoToken === "FINITO_MENSUAL") {
    const fechaHoy = new Date();
    fechaHoy.setDate(fechaHoy.getDate() + 30);  // Añadimos 30 días a la fecha actual
    fechaExpiracion = fechaHoy.toISOString().split('T')[0]; // Solo la parte de la fecha (YYYY-MM-DD)
  }

  const tokenAleatorio = Math.random().toString(36).substring(2) + Date.now().toString(36);
  const tokenSHA256 = CryptoJS.SHA256(tokenAleatorio).toString(CryptoJS.enc.Base64);

  const data = {
    tipo: tipoToken,
    entidad_id: entidadId,
    token: tokenSHA256,
    numero_uso: (tipoToken === "FINITO_USO" || tipoToken === "FINITO_MENSUAL") ? numeroUsos : null,
    fecha_expiracion: fechaExpiracion // Aquí se envía la fecha calculada si es "FINITO_MENSUAL"
  };

  fetch('generar_token.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(response => {
    alert(response.message);
    const modal =bootstrap.Modal.getInstance(document.getElementById('modalGenerarToken'));
    modal.hide();
    cargarTabla();
  })
  .catch(error => {
    console.error("Error generando el token:", error);
  });
  
  cargarTokens(); // Cargar los tokens después de generar uno nuevo
}


  </script>
</body>
</html>
