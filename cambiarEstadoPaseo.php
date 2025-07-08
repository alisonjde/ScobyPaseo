<?php
require_once("logica/paseo.php");

if (isset($_POST['idPaseo'], $_POST['accion'])) {
    $idPaseo = intval($_POST['idPaseo']);
    $accion = $_POST['accion'];

    
    $estadoNuevo = 0;

    switch ($accion) {
        case "aceptar":
            $estadoNuevo = 2; 
            break;
        case "rechazar":
            $estadoNuevo = 4; 
            break;
        case "finalizar":
            $estadoNuevo = 3;
            break;
        default:
            echo "Acción no válida";
            exit();
    }

    $paseo = new Paseo($idPaseo);
    $paseo->actualizarEstado($estadoNuevo);

    echo "Estado actualizado correctamente";
} else {
    echo "Datos incompletos";
}
