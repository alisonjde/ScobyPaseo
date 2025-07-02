<body>

    <?php
    include("presentacion/encabezado.php");
    include("presentacion/fondo.php");


    $id = $_GET["id"] ?? null;
    $paseador = null;

    if ($id) {
        $paseador = new Paseador($id);
        $paseador->consultar(); 
    } else {
        echo "<div class='alert alert-danger text-center'>ID no proporcionado.</div>";
        exit;
    }
    ?>
    ?>



    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-5 text-center">
                            <img src="<?php echo $paseador->getFoto(); ?>"
                                onerror="this.src='img/default-profile.png'"
                                alt="Foto de <?php echo $paseador->getNombre(); ?>"
                                class="img-fluid rounded-start p-3"
                                style="max-height: 300px; object-fit: cover;">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $paseador->getNombre() . ' ' . $paseador->getApellido(); ?></h3>
                                <p class="card-text"><strong>Correo:</strong> <?php echo $paseador->getCorreo(); ?></p>
                                <p class="card-text"><strong>Teléfono:</strong> <?php echo $paseador->getTelefono(); ?></p>
                                <p class="card-text"><strong>Descripción:</strong><br><?php echo nl2br($paseador->getDescripcion()); ?></p>
                                <a href="javascript:history.back()" class="btn btn-outline-success mt-3">← Volver</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>