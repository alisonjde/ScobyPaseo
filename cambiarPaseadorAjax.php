<?php
require_once("logica/Paseador.php");

$id = $_GET['idPaseador'] ?? null;
$estadoActual = $_GET['idEstado'] ?? null;

if ($id && $estadoActual !== null) {
    $nuevoEstado = ($estadoActual == 1) ? 2 : 1;

    $paseador = new Paseador($id);
    $paseador->cambiarEstado($nuevoEstado);
    echo "Estado cambiado correctamente";
} else {
    echo "Error: datos incompletos";
}
?>
