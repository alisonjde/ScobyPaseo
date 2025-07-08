<?php
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
if (isset($_POST["confirmarCambiarTarifa"])) {
    $id = $_SESSION["id"];
    $idDueño = $_POST["idDueño"];
    $idPaseo = $_POST["idPaseo"];
    $nuevaTarifa = $_POST["nuevaTarifa"];
    $mensaje = new Mensaje("", $id, $idPaseo, $idDueño, $nuevaTarifa);
    $mensaje->insertar();
    
        header("Location: ?pid=" . base64_encode("presentacion/paseito/modificarTarifa.php")."&estado=exito");
        exit();
    
}
?>