<?php
if($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

if (isset($_POST["confirmarAceptar"])) {

    $estado = 1;
    $idMensaje = $_POST["idMensaje"];
    $mensaje = new Mensaje($idMensaje, "", "", "", "", $estado);
    $mensaje->modificarEstado();
    
    $idPaseo = $_POST["idPaseo"];
    $nuevaTarifa = $_POST["nuevaTarifa"];
    $mensaje = new Mensaje("", "", $idPaseo, "",$nuevaTarifa, "");
    $mensaje->modificarTarifa();
        

        
    
    
} elseif (isset($_POST["confirmarRechazar"])) {
    $estado = 0;
    $idMensaje = $_POST["idMensaje"];
    $mensaje = new Mensaje($idMensaje, "", "", "", "", $estado);
    $mensaje->modificarEstado();

    
    $idPaseo = $_POST["idPaseo"];
    $paseo = new Paseo($idPaseo);
    $resultadoTarifa = $paseo->cancelarPaseo();     
}

header("Location: ?pid=" . base64_encode("presentacion/mensaje/consultarMensaje.php"));
            exit();
?>