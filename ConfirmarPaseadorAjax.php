<?php
require("logica/Persona.php");
require("logica/Paseador.php");

$filtro = $_GET["filtro"];
$filtros = explode(" ", $filtro);
$paseador = new Paseador();
$paseadores = $paseador->modificarAceptar($filtros);

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

        echo "<tr>";

        $nombre = $pas->getNombre();
        $apellido = $pas->getApellido();
        $correo = $pas->getCorreo();
        $telefono = $pas->getTelefono();

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

        echo "<td><img src='" . htmlspecialchars($pas->getFoto()) . "' alt='Foto' style='width: 60px; height: 60px; object-fit: cover; border-radius: 8px;' onerror=\"this.src='img/default-profile.png'\"></td>";
        echo "<td>" . $pas->getId() . "</td>";
        echo "<td>" . $nombre . " " . $apellido . "</td>";
        echo "<td>" . $correo . "</td>";
        echo "<td>" . $telefono . "</td>";
        echo "<td>" . $pas->getEstadoPaseador() . "</td>";

        echo "<td>
        <a href='?pid=" . base64_encode('presentacion/paseador/editarPaseador.php') . "&idPaseador=" . $pas->getId() . "' 
           class='btn btn-sm btn-primary' 
           title='Editar paseador'>
            <i class='fas fa-check'></i>
        </a>
        <button class='btn btn-sm btn-danger btn-eliminar' 
                data-id='" . $pas->getId() . "' 
                data-nombre='" . htmlspecialchars($pas->getNombre()) . "' 
                title='Eliminar paseador'>
            <i class='fas fa-trash-alt'></i>
        </button>
      </td>";
        echo "</tr>";
    }
    echo "</tbody><table>";
} else {
    echo "<div class='alert alert-danger mt-3' role='alert'>No hay resultados</div>";
}

?>