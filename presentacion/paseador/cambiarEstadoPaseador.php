    <?php

    if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
        echo "error";
        exit();
    }

    if (isset($_GET["idPaseador"])) {
        $idPaseador = $_GET["idPaseador"];
        $paseador = new Paseador($idPaseador);
        $paseador->consultar();
        if ($paseador->actualizarEstado(1)) {
            echo "Activo"; 
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
    ?>
