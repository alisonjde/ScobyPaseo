<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
$id = $_SESSION["id"];

$mensaje = "";
$error = "";

if(isset($_POST['crear'])) {
    try {
        $foto_url = 'img/default-dog.png';
        
        if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $directorio = "img/perros/";
            if(!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }
            
            $nombreArchivo = uniqid() . '_' . basename($_FILES['foto']['name']);
            $rutaCompleta = $directorio . $nombreArchivo;

            $tipoArchivo = strtolower(pathinfo($rutaCompleta, PATHINFO_EXTENSION));
            $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'gif');
            
            if(in_array($tipoArchivo, $extensionesPermitidas)) {
                if(move_uploaded_file($_FILES['foto']['tmp_name'], $rutaCompleta)) {
                    $foto_url = $rutaCompleta;
                } else {
                    throw new Exception("Error al subir la imagen");
                }
            } else {
                throw new Exception("Solo se permiten archivos JPG, JPEG, PNG o GIF");
            }
        }
        
        $perro = new Perro(
            $_POST['id'],
            $_POST['nombre'],
            $_POST['raza'],
            $foto_url,
            $id
            );
        
        if($perro->crear()) {
            $mensaje = "Perro creado exitosamente";
        } else {
            $error = "Error al crear el perro";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<body>
    <?php
    include ("presentacion/fondo.php");
    include("presentacion/dueño/menuDueño.php");
    ?>
    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Agregar Nuevo Perro</h1>
            
            <?php if($mensaje != ""): ?>
                <div class="alert alert-success"><?php echo $mensaje ?></div>
            <?php endif; ?>
            
            <?php if($error != ""): ?>
                <div class="alert alert-danger"><?php echo $error ?></div>
            <?php endif; ?>
            
            <div class="card mx-auto" style="max-width: 40rem; background-color: transparent; border: 3px solid black;">
                <div style="border-bottom: 2px dashed black;" class="card-header display-6 text-light">
                    Datos del Perro
                </div>
                <div class="card-body text-light">
                    <form method="post" action="?pid=<?php echo base64_encode("presentacion/perro/crearPerro.php") ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="id" class="form-label">ID del Perro</label>
                            <input type="text" class="form-control" id="id" name="id" required>
                            <div class="form-text">El ID será asignado manualmente</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="raza" class="form-label">raza</label>
                            <input type="text" class="form-control" id="raza" name="raza" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto del Perro</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="form-text">Formatos aceptados: JPG, PNG, GIF</div>
                        </div>
                        
                        <button type="submit" name="crear" class="btn btn-primary">Crear Perro</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>