<?php
require_once("logica/Paseador.php");


if (!isset($_GET["idPaseador"])) {
    echo "error: sin id";
    exit();
}

$idPaseador = $_GET["idPaseador"];
$paseador = new Paseador($idPaseador);
$paseador->consultar();

$resultado = $paseador->actualizarEstado(1);

if ($resultado) {
    echo "Activo";
} else {
    echo "error: no se actualizó";
}
?>
