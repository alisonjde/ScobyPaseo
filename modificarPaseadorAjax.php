<?php
require("logica/Persona.php");
require("logica/Paseador.php");
require("logica/EstadoPaseador.php");

$filtro = $_GET["filtro"] ?? '';
$filtros = explode(" ", $filtro);

$paseador = new Paseador();
$paseadores = $paseador->modificar($filtros);

if (count($paseadores) > 0) {
    echo "<table class='table table-custom table-hover text-light'>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>IdPaseador</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";

    foreach ($paseadores as $pas) {
        $id = $pas->getId();
        $estadoObj = $pas->getEstadoPaseador();

        $estadoId = $estadoObj ? $estadoObj->getIdEstadoPaseador() : 0;
        $estadoNombre = $estadoObj ? $estadoObj->getEstado() : 'Desconocido';

        $foto = htmlspecialchars($pas->getFoto());

        $nombre = $pas->getNombre();
        $apellido = $pas->getApellido();
        $correo = $pas->getCorreo();
        $telefono = $pas->getTelefono();

        // Resaltar coincidencias
        $patron = '/' . implode('|', array_map('preg_quote', $filtros)) . '/i';
        $nombre = preg_replace_callback($patron, fn($c) => "<strong>{$c[0]}</strong>", $nombre);
        $apellido = preg_replace_callback($patron, fn($c) => "<strong>{$c[0]}</strong>", $apellido);
        $correo = preg_replace_callback($patron, fn($c) => "<strong>{$c[0]}</strong>", $correo);
        $telefono = preg_replace_callback($patron, fn($c) => "<strong>{$c[0]}</strong>", $telefono);

        // Clases según estado
        if ($estadoId == 1) {
            $claseEstado = "text-success";
        } elseif ($estadoId == 2) {
            $claseEstado = "text-danger";
        } else {
            $claseEstado = "text-secondary";
        }

        echo "<tr>
                <td>
                    <img src='$foto' class='rounded-circle'
                         style='width: 50px; height: 50px; object-fit: cover;' alt='Foto paseador'
                         onerror='this.src=\"img/default-profile.png\"'>
                </td>
                <td>{$id}</td>
                <td>{$nombre} {$apellido}</td>
                <td>{$correo}</td>
                <td>{$telefono}</td>
                <td>
                    <div class='rounded-pill' style='background:rgba(0,0,0,0.1);'>
                        <div class='{$claseEstado} fw-bold'>{$estadoNombre}</div>
                    </div>
                </td>
                <td>
                    <button title='Cambiar estado' type='button'
                        class='btn btn-sm btn-danger btnCambiar'
                        data-idcambiar='{$id}'
                        data-estado='{$estadoId}'
                        data-bs-toggle='modal'
                        data-bs-target='#staticBackdrop'>
                        <i class='fas fa-sync-alt'></i>
                    </button>
                </td>
            </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-danger mt-3' role='alert'>No hay resultados</div>";
}
?>
