<?php
require("logica/Paseo.php");
require("logica/Dueño.php");
require("logica/Perro.php");
require("logica/EstadoPaseo.php");

$filtro = $_GET["filtro"];
$filtros = explode(" ", $filtro);
$paseo = new Paseo();
$paseos = $paseo->buscar($filtros);

if (count($paseos) > 0) {
    echo "<table class='table table-custom table-hover text-light'>
        <thead>
            <tr>
                <th>Id Paseo</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Tarifa</th>
                <th>Paseador</th>
                <th>Perro</th>
                <th>Dueño</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>";

    foreach ($paseos as $pas) {
        $idPaseo =$pas->getIdPaseo();
        $tarifa = $pas->getTarifa();
        $fechaFormateada = date("d/m/Y", strtotime($pas->getFecha()));
        $horaFormateada = date("H:i", strtotime($pas->getHora()));
        $estadoId = $pas->getEstadoPaseo()->getIdEstadoPaseo();
        $estadoNombre = $pas->getEstadoPaseo()->getEstado();
        $claseEstado = "";

        if ($estadoId == 1) {
            $claseEstado = "text-warning";
        } else if ($estadoId == 2) {
            $claseEstado = "text-info";
        } else if ($estadoId == 3) {
            $claseEstado = "text-success";
        } else if ($estadoId == 4) {
            $claseEstado = "text-danger";
        } else {
            $claseEstado = "text-secondary";
        }

        $patron = '/(' . implode('|', $filtros) . ')/i';

        $fecha = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $fechaFormateada);

        $paseadorNombre = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $pas->getIdPaseador()->getNombre());

        $paseadorApellido = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $pas->getIdPaseador()->getApellido());

        $perroNombre = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $pas->getPerro()->getNombre());

        $dueñoNombre = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $pas->getPerro()->getIdDueño()->getNombre());

        $dueñoApellido = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $pas->getPerro()->getIdDueño()->getApellido());

        $estado = preg_replace_callback($patron, function ($coincidencias) {
            return "<strong>" . $coincidencias[0] . "</strong>";
        }, $estadoNombre);

        echo "<tr>";
        echo "<td>$idPaseo</td>";
        echo "<td>$fecha</td>";
        echo "<td>$horaFormateada</td>";
        echo "<td><span class='tarifa-badge'>$" . number_format($tarifa,2) . "</span></td>";
        echo "<td>$paseadorNombre $paseadorApellido</td>";
        echo "<td>$perroNombre</td>";
        echo "<td>$dueñoNombre $dueñoApellido</td>";
        echo "<td><div class='rounded-pill ' style='background: rgba(0, 0, 0, 0.1);'><span class='$claseEstado fw-bold'>$estado</span></div></td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-danger mt-3' role='alert'>No hay resultados</div>";
}
?>
