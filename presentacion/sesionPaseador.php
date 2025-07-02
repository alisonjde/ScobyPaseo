<?php
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
?>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/paseador/menuPaseador.php");
    $id = $_SESSION["id"];
    $paseador = new Paseador($id);
    $paseador->consultar();
    $paseo = new Paseo("", "", "", "", $id);

    ?>
    <div class="text-center py-3 hero-text">
        <div class="container glass">
            <h1 class="display-6">¡Hola, <?php echo $paseador->getNombre() ?>!</h1>
            <?php echo "<p style='color: yellow;'>Ruta de la imagen: " . $paseador->getFoto() . "</p>"; ?>

            <img src="<?php echo $paseador->getFoto(); ?>"
                class="rounded-circle"
                style="width: 100%; max-width: 150px;"
                alt="Foto de <?php echo $paseador->getNombre(); ?>">

            <div class="card m-3 mx-auto" style="max-width: 40rem; background-color: transparent; border: 3px solid black;">
                <div style="border-bottom: 2px dashed black;" class="card-header display-6 text-light">
                    Información
                </div>
                <div class="card-body text-light">
                    <p class="card-text lead"><strong>Rol: </strong>Paseador</p>
                    <p class="lead"><strong>Telefono: </strong><?php echo $paseador->getTelefono() ?></p>
                    <p class="lead"><strong>Correo: </strong><?php echo $paseador->getCorreo() ?></p>
                    <p class="lead "><strong>Paseos pendientes: </strong><?php echo $paseo->consultarPendiente() ?></p>
                    <p class="lead" >Para mas informacion consulta en la opción <i>Paseos</i>.</p>
                    <a href="#" class="btn btn-custom" type="submit">Modificar información</a>
                </div>
            </div>
        </div>
    </div>
</body>