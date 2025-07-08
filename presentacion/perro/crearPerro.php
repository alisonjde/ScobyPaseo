<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

include("presentacion/dueño/menuDueño.php");
include("presentacion/fondo.php");
$id = $_SESSION["id"];
$tamaño = new Tamaño();
$tamaños = $tamaño->consultar();
$mensaje = "";
$error = "";

if(isset($_POST["crearPerro"])) {
    try {
        $nombre = $_POST["nombre"];
        $idTamaño = $_POST["idTamaño"];
        $id = $_SESSION["id"];
        
        $foto_url = "";
        
        if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto = $_FILES["foto"]["name"];
            $tam = $_FILES["foto"]["size"];
            $rutaLocal = $_FILES["foto"]["tmp_name"];
            $nuevoNombre = time() . "_" . str_replace(' ', '_', basename($_FILES["foto"]["name"]));

            $rutaServidor = "imagenes/Perros/" . $nuevoNombre;
            
            $infoImagen = getimagesize($_FILES['foto']['tmp_name']);
            if($infoImagen === false) {
                throw new Exception("El archivo no es una imagen válida");
            }

            if(copy($rutaLocal, $rutaServidor)) {
                $foto_url = $rutaServidor;
            } else {
                throw new Exception("Error al subir la imagen");
            }
        }
        
        $perro = new Perro(
            "",
            $nombre,
            $foto_url,
            $idTamaño,
            $id
        );
        
        if($perro->insertar()) {
            $mensaje = "Perro creado exitosamente";
        } else {
            $error = "Error al crear el perro en la base de datos";
        }
        
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if(!empty($mensaje)){ ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <strong>¡Éxito!</strong> <?php echo $mensaje; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php }; ?>
                
                <?php if(!empty($error)){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>¡Error!</strong> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php }; ?>
                
                <div class="form-container p-4">
                    <div class="text-center mb-4">
                        <h2 style="color:rgb(81, 178, 42);" class="fw-bold">Agregar Nuevo Perro 🐕</h2>
                    </div>
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label style="color:rgb(81, 178, 42);" for="nombre" class="form-label fw-semibold">Nombre del Perro <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej: Max, Luna, Rocky..." value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label style="color:rgb(81, 178, 42);" for="foto" class="form-label fw-semibold">Foto del Perro</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</div>
                        </div>

                        <div class="mb-4">
                            <label style="color:rgb(81, 178, 42);" for="idTamaño" class="form-label fw-semibold">Tamaño <span class="text-danger">*</span></label>
                            <select class="form-select" id="idTamaño" name="idTamaño" required>
                                <option value="">Selecciona el tamaño</option>
                                <?php foreach ($tamaños as $tam): ?>
                                    <option value="<?php echo $tam->getIdTamaño(); ?>" 
                                            <?php echo (isset($_POST['idTamaño']) && $_POST['idTamaño'] == $tam->getIdTamaño()) ? 'selected' : ''; ?>>
                                        <?php echo $tam->getTamaño(); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button name="crearPerro" type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-plus-circle"></i> Agregar Perro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>