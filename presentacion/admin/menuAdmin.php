<?php
$id = $_SESSION["id"];
include("presentacion/fondo.php");

?>



<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">

        <a class="navbar-brand fs-4" href="?pid=<?php echo base64_encode("presentacion/sesionAdmin.php") ?>">🐾 Perritours</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdminContent"
            aria-controls="navbarAdminContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdminContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Menú Paseadores -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Paseadores
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseador/crearPaseador.php") ?>">Crear Paseador</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseador/consultarPaseador.php") ?>">Consultar Paseadores</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseador/editarPaseador.php") ?>">Editar Paseadores</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseador/eliminarPaseador.php") ?>">Eliminar Paseadores</a></li>
                    </ul>
                </li>

                <!-- Menú Dueños -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dueños
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/dueño/consultarDueño.php") ?>">Consultar Dueños</a></li>
                    </ul>
                </li>

                <!-- Menú Perros -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Perros
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/perro/consultarPerro.php") ?>">Consultar Perros</a></li>
                    </ul>
                </li>

                <!-- Menú Paseos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Paseos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseito/consultarPaseo.php") ?>">Consultar Paseos</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="?pid=<?php echo base64_encode("presentacion/paseador/confirmarPaseador.php") ?>">
                        Nuevos Paseadores
                    </a>
                </li>



            </ul>

            <!-- Botón cerrar sesión alineado como en la navbar pública -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="?pid=<?php echo base64_encode("presentacion/autenticar.php") ?>&sesion=false"
                        class="btn btn-light text-success fw-bold">
                        Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>