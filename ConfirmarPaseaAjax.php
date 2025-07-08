<?php
session_start();
require("logica/Paseo.php");

$filtro = $_GET["filtro"] ?? "";
$filtros = explode(" ", $filtro);
$idPaseador = $_GET["id"];

$paseo = new Paseo();

if (empty(trim($filtro))) {
    $paseos = $paseo->consultarPorPaseador($idPaseador);
} else {
    $paseos = $paseo->buscarPorPaseador($filtros, $idPaseador);
}

$patron = '/(' . implode('|', array_map('preg_quote', $filtros)) . ')/i';

if (count($paseos) > 0) {
    foreach ($paseos as $p) {
        $fecha = date("d/m/Y", strtotime($p->getFecha()));
        $hora = date("H:i", strtotime($p->getHora()));
        $tarifa = number_format($p->getTarifa(), 2);
        $perro = $p->getPerro()->getNombre();
        $dueñoNombre = $p->getPerro()->getIdDueño()->getNombre();
        $dueñoApellido = $p->getPerro()->getIdDueño()->getApellido();

       
        $fecha = preg_replace_callback($patron, fn($m) => "<strong>{$m[0]}</strong>", $fecha);
        $hora = preg_replace_callback($patron, fn($m) => "<strong>{$m[0]}</strong>", $hora);
        $tarifaStr = preg_replace_callback($patron, fn($m) => "<strong>{$m[0]}</strong>", $tarifa);
        $perro = preg_replace_callback($patron, fn($m) => "<strong>{$m[0]}</strong>", $perro);
        $dueñoCompleto = preg_replace_callback($patron, fn($m) => "<strong>{$m[0]}</strong>", "$dueñoNombre $dueñoApellido");

        echo "<tr>";
        echo "<td>$fecha</td>";
        echo "<td>$hora</td>";
        echo "<td><span class='tarifa-badge'>$$tarifaStr</span></td>";
        echo "<td>$perro</td>";
        echo "<td>$dueñoCompleto</td>";

        if ($_SESSION["rol"] != "paseador") {
            echo "<td>" . htmlspecialchars($p->getIdPaseador()->getNombre()) . "</td>";
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center text-warning'>No hay resultados</td></tr>";
}
?>
