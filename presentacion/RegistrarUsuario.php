<body>
    <?php
    include("presentacion/encabezado.php");
    include("presentacion/fondo.php");

    require_once("logica/Dueño.php");
    require_once("logica/Paseador.php");

    $mensaje = "";

    if (isset($_POST['guardar'])) {

        $id          = $_POST['id'];
        $nombre      = $_POST['nombre'];
        $apellido    = $_POST['apellido'];
        $correo      = $_POST['correo'];
        $telefono    = $_POST['telefono'];
        $clave       = md5($_POST['clave']);
        $tipoUsuario = $_POST['tipoUsuario'];

        $fotoNombre  = $_FILES['foto']['name'];
        $fotoTmp     = $_FILES['foto']['tmp_name'];

        if ($tipoUsuario == "dueño") {
            $fotoNombre = uniqid() . "_" . $_FILES['foto']['name'];
            $fotoDestino = "imagenes/Duenos/" . $fotoNombre;
        } elseif ($tipoUsuario == "paseador") {
            $fotoNombre = uniqid() . "_" . $_FILES['foto']['name'];
            $fotoDestino = "imagenes/Paseadores/" . $fotoNombre;
        } else {
            $fotoDestino = "imagenes/" . $fotoNombre;
        }

        move_uploaded_file($fotoTmp, $fotoDestino);

        if ($tipoUsuario == "dueño") {

            $dueno = new Dueño($id, $nombre, $apellido, $fotoDestino, $correo, $telefono, $clave);
            if ($dueno->insertar()) {
                $mensaje = "<div class='alert alert-success mt-3'>Dueño registrado correctamente.</div>";
            } else {
                $mensaje = "<div class='alert alert-danger mt-3'>El ID o correo ya está registrado. Intenta con otro.</div>";
            }
        } elseif ($tipoUsuario == "paseador") {

            $descripcion    = $_POST['descripcion'];
            $disponibilidad = $_POST['disponibilidad'];
            $estadoPaseador = 3;

            $paseador = new Paseador($id, $nombre, $apellido, $fotoDestino, $correo, $telefono, $clave, $descripcion, $disponibilidad, $estadoPaseador);


            if ($paseador->crear()) {
                $mensaje = "<div class='alert alert-success mt-3'>Paseador registrado correctamente.</div>";
            } else {
                $mensaje = "<div class='alert alert-danger mt-3'>El ID o correo ya está registrado. Intenta con otro.</div>";
            }
        }
    }
    ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <h3 class="text-center mb-4">Registrar Usuario</h3>

                <form method="POST" action="" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Tipo de usuario:</label><br>

                        <input type="radio" name="tipoUsuario" value="dueño"
                            <?php if (!isset($_POST['tipoUsuario']) || $_POST['tipoUsuario'] == 'dueño') echo 'checked'; ?>
                            onchange="this.form.submit();">
                        Dueño

                        <input type="radio" name="tipoUsuario" value="paseador"
                            <?php if (isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] == 'paseador') echo 'checked'; ?>
                            onchange="this.form.submit();">
                        Paseador
                    </div>

                    <div class="mb-3">
                        <label for="id" class="form-label">ID</label>
                        <input type="text" class="form-control" id="id" name="id" required>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                    </div>

                    <div class="mb-3">
                        <label for="clave" class="form-label">Clave</label>
                        <input type="password" class="form-control" id="clave" name="clave" required>
                    </div>

                    <?php if (isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] == 'paseador'): ?>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="disponibilidad" class="form-label">Disponibilidad</label>
                            <input type="text" class="form-control" id="disponibilidad" name="disponibilidad">
                        </div>
                    <?php endif; ?>

                    <button type="submit" name="guardar" class="btn btn-primary w-100">Registrar</button>
                </form>

                <?php
                if ($mensaje != "") {
                    echo $mensaje;
                }
                ?>

            </div>
        </div>
    </div>

</body>