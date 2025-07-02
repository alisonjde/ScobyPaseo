<?php
require("logica/Perro.php");

$filtro = $_GET["filtro"];
$filtros = explode(" ", $filtro);
$perro = new Perro();
$perros = $perro->buscar($filtros);

if (count($perros) > 0) {
    $patron = '/(' . implode('|', $filtros) . ')/i';  // Patrón para resaltar

    foreach ($perros as $per) {

        $idPerro = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, strval($per->getIdPerro()));

        $nombrePerro = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $per->getNombre());

        $nombreDueño = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $per->getIdDueño()->getNombre());

        $apellidoDueño = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $per->getIdDueño()->getApellido());

        $idDueño = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $per->getIdDueño()->getId());

        $tamaño = htmlspecialchars($per->getIdTamaño());

        echo '
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card card-perro h-100">
                <img src="' . htmlspecialchars($per->getFoto()) . '" class="perro-img" alt="' . htmlspecialchars($per->getNombre()) . '" onerror="this.onerror=null; this.src=\'img/dog.jpg\';">
                <div class="card-body">
                    <h6>ID: ' . $idPerro . '</h6>
                    <h5 class="card-title">' . $nombrePerro . '</h5>
                    <span class="raza-badge">' . $tamaño . '</span>
                    <div class="dueño-info">
                        Dueño: ' . $idDueño . ' - ' . $nombreDueño . ' ' . $apellidoDueño . '
                    </div>
                </div>
            </div>
        </div>';
    }
} else {
    echo "<div class='alert alert-danger mt-3' role='alert'>No hay resultados</div>";
}
?>