<?php
if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
?>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/admin/menuAdmin.php");
    $id = $_SESSION["id"];
    $admin = new Admin($id);
    $admin->consultar();
    ?>
    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">¡Hola, <?php echo $admin->getNombre() ?>!</h1>
            <?php echo "<p style='color: yellow;'>Ruta de la imagen: " . $admin->getFoto() . "</p>"; ?>

            <img src="<?php echo $admin->getFoto(); ?>"
                class="rounded-circle"
                style="width: 100%; max-width: 150px;"
                alt="Foto de <?php echo $admin->getNombre(); ?>"
                onerror="this.onerror=null; this.src='img/default-profile.png';">


            <div class="card m-3 mx-auto" style="max-width: 40rem; background-color: transparent; border: 3px solid black;">
                <div style="border-bottom: 2px dashed black;" class="card-header display-6 text-light">
                    Información
                </div>
                <div class="card-body text-light">
                    <p class="card-text lead"><strong>Rol: </strong>Admin</p>
                    <p class="lead"><strong>Teléfono: </strong><?php echo $admin->getTelefono() ?></p>
                    <p class="lead"><strong>Correo: </strong><?php echo $admin->getCorreo() ?></p>
                    <p class="lead" style="color: black">Consulta los usuarios, paseos y estadisticas en el <i>Menú</i>.</p>
                </div>
            </div>
        </div>
    </div>
</body>