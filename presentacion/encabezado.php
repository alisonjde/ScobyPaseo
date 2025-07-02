<?php
include("presentacion/fondo.php");
?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">

        <a class="navbar-brand fs-4" href="?pid=<?php echo base64_encode("presentacion/inicio.php") ?>">
            <img src="imagenes/logo.png" alt="Logo" style="height: 50px; width: auto; margin-right: 10px;">
            Scooby-Paseo
        </a>



        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarPerritours"
            aria-controls="navbarPerritours"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarPerritours">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#about">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#stats">Estadísticas</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="btn btn-light text-success fw-bold"
                        href="?pid=<?php echo base64_encode("presentacion/autenticar.php") ?>">
                        Ingresar
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn btn-light text-success fw-bold"
                        href="?pid=<?php echo base64_encode("presentacion/RegistrarUsuario.php"); ?>">
                        Registrar Usuario
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>