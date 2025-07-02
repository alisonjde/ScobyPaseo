<?php
require("logica/Persona.php");
require("logica/Dueño.php");

$filtro = $_GET["filtro"];
$filtros = explode(" ", $filtro);
$dueño = new Dueño();
$dueños = $dueño->buscar($filtros);

if (count($dueños) > 0) {
    echo "<table class='table table-custom table-hover text-light'>
                <thead>
                    <tr>
                        <th>IdDueño</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>";

    foreach ($dueños as $due) {

        echo "<tr>";
        echo "<td>" . $due->getId() . "</td>";

        $nombre = $due->getNombre();
        $apellido = $due->getApellido();
        $correo = $due->getCorreo();
        $telefono = $due->getTelefono();

        $patron = '/(' . implode('|', $filtros) . ')/i';

        $nombre = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $nombre);

        $apellido = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $apellido);

        $correo = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $correo);

        $telefono = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $telefono);

        echo "<td>" . $nombre . " " . $apellido . "</td>";
        echo "<td>" . $correo . "</td>";
        echo "<td>" . $telefono . "</td>";
        echo "</tr>";
    }
    echo "</tbody><table>";
}else{
    echo "<div class='alert alert-danger mt-3' role='alert'>No hay resultados</div>";
}

?>