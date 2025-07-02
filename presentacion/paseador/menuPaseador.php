<?php
  include("presentacion/fondo.php");
?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fs-4" href="?pid=<?php echo base64_encode("presentacion/sesionPaseador.php") ?>">🐾 Perritours</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPaseadorContent"
            aria-controls="navbarPaseadorContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPaseadorContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!--Menú Perritos-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Perros
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/perro/consultarPerro.php") ?>">Consultar</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/perro/crearPerro.php") ?>">Agregar</a></li>
                    </ul>
                </li>

                <!--Menú paseos-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Paseos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-light" href="#">Oferta de paseos</a></li>
                        <a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseito/consultarPaseo.php") ?>">Consultar mis paseos</a>
                    </ul>
                </li>
            </ul>
            <a href="?pid=<?php echo base64_encode("presentacion/autenticar.php") ?>&sesion=false"
                class="btn btn-light text-success fw-bold" type="submit">Cerrar Sesión</a>
        </div>
    </div>
</nav>
