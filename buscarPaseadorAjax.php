<?php
require("logica/Persona.php");
require("logica/Paseador.php");

$filtro = $_GET["filtro"];
$filtros = explode(" ", $filtro);

$paseador = new Paseador();
$paseadores = $paseador->modificar($filtros);

if (count($paseadores) > 0) {
  foreach ($paseadores as $pas) {

   
   

    $nombre = $pas->getNombre();
    $apellido = $pas->getApellido();
    $correo = $pas->getCorreo();
    $telefono = $pas->getTelefono();

    $patron = '/(' . implode('|', $filtros) . ')/i';

    $nombre = preg_replace_callback($patron, fn($c) => "<strong>{$c[0]}</strong>", $nombre);
    $apellido = preg_replace_callback($patron, fn($c) => "<strong>{$c[0]}</strong>", $apellido);
    $correo = preg_replace_callback($patron, fn($c) => "<strong>{$c[0]}</strong>", $correo);
    $telefono = preg_replace_callback($patron, fn($c) => "<strong>{$c[0]}</strong>", $telefono);

    echo '
        <div class="col-md-4 mb-4">
          <a href="#" class="text-decoration-none text-dark">
            <div class="card h-100 shadow">
              <img src="' . $pas->getFoto() . '" class="card-img-top" alt="Foto de ' . $pas->getNombre() . '" onerror="this.src=\'img/default-profile.png\'">
              <div class="card-body">
                <h5 class="card-title">' . $nombre . " " . $apellido . '</h5>
                <p class="card-text"><strong>Correo:</strong> ' . $correo . '</p>
                <p class="card-text"><strong>Teléfono:</strong> ' . $telefono . '</p>
                <p class="card-text"><strong>Descripción:</strong> ' . $pas->getDescripcion() . '</p>
              </div>
            </div>
          </a>
        </div>';
  }
} else {
  echo "<div class='alert alert-warning text-center w-100' role='alert'>No se encontraron paseadores.</div>";
}
