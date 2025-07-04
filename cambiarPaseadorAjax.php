<?php
require_once("logica/Paseador.php");
$id = $_POST['idPaseador'] ?? $_GET['idPaseador'] ?? null;
$idEstado = $_GET['idEstado'];
$estadoNuevo = ($idEstado == 1) ? 2 : 1;

?>
    <script>console.log(<?php echo json_encode($estadoNuevo); ?>);</script>
    <?php

if ($id) {
   
    $paseador = new Paseador($id);
    $paseador->cambiarEstado($estadoNuevo);
    echo "hola";
} 
?>
