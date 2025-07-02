<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
?>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/dueño/menuDueño.php");
    $id = $_SESSION["id"];
    $dueño = new Dueño($id);
    $dueño->consultar();
    
    $perro = new Perro("", "", "", "", $id);

    ?>
    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">¡Hola, <?php echo $dueño->getNombre() ?>!</h1>
            <?php echo "<p style='color: yellow;'>Ruta de la imagen: " . $dueño->getFoto() . "</p>"; ?>

            <img src="<?php echo $dueño->getFoto(); ?>"
                class="rounded-circle"
                style="width: 100%; max-width: 150px;"
                alt="Foto de <?php echo $dueño->getNombre(); ?>">


            <div class="card m-3 mx-auto" style="max-width: 40rem; background-color: transparent; border: 3px solid black;">
                <div style="border-bottom: 2px dashed black;" class="card-header display-6 text-light">
                    Información
                </div>
                <div class="card-body text-light">
                    <p class="card-text lead"><strong>Rol: </strong>Dueño</p>
                    <p class="lead"><strong>Telefono: </strong><?php echo $dueño->getTelefono() ?></p>
                    <p class="lead"><strong>Correo: </strong><?php echo $dueño->getCorreo() ?></p>
                    <p class="lead "><strong>Perritos registrados: </strong><?php echo $perro->consultarCantidad() ?></p>
                    <p class="lead" style="color: black">Para mas informacion consulta en la opción <i>Paseos</i></p>
                </div>
            </div>
        </div>
    </div>
</body>