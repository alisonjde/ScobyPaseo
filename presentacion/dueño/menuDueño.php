<?php
  include("presentacion/fondo.php");
  $id = $_SESSION["id"];
  $mensaje = new Mensaje("", "", "", $id, "", "");
  $notificaciones = $mensaje->notificacion();
?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a title="sesion de usuario" class="navbar-brand fs-4" href="?pid=<?php echo base64_encode("presentacion/sesionDueño.php") ?>">
            <img src="imagenes/logo.png" alt="Logo" style="height: 50px; width: auto; margin-right: 10px;">
            Scooby-Paseo
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDueñoContent"
            aria-controls="navbarDueñoContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarDueñoContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!--Menú mis perritos-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Mis Perritos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/perro/consultarPorDueño.php") ?>">Consultar</a></li>
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/perro/crearPerro.php") ?>">Agregar</a></li>
                    </ul>
                </li>

                <!--Menú paseos-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Paseos
                    </a>
                    <ul class="dropdown-menu" >
                        <li><a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseito/agendarPaseo.php") ?>">Agendar Paseo</a></li>
                        <a class="dropdown-item text-light" href="?pid=<?php echo base64_encode("presentacion/paseito/consultarPaseo.php") ?>">Consultar paseos</a>
                    </ul>
                </li>
            </ul>
            <?php if($notificaciones > 0){
                echo '<a href="?pid=' . base64_encode("presentacion/mensaje/consultarMensaje.php") . '" class="btn btn-light text-success fw-bold me-2">
                        <i class="fas fa-bell"></i> Mensajes <span class="badge bg-danger">' . $notificaciones . '</span>
                      </a>';
            } else {
                echo '<a href="?pid=' . base64_encode("presentacion/mensaje/consultarMensaje.php") . '" class="btn btn-light text-success fw-bold me-2">
                        <i class="fas fa-bell"></i> Mensajes
                      </a>';
            }?>

            <a href="?pid=<?php echo base64_encode("presentacion/autenticar.php") ?>&sesion=false"
                class="btn btn-light text-success fw-bold" type="submit">Cerrar Sesión</a>
        </div>
    </div>
</nav>
