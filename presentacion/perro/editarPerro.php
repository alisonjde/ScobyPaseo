<?php
include("presentacion/dueño/menuDueño.php");
include("presentacion/fondo.php");

if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}


$idPerro = isset($_GET["idPerro"]) ? intval($_GET["idPerro"]) : null;

echo "ID recibido: " . $idPerro; 

if (!$idPerro) {
    echo "<div class='alert alert-warning'>ID del perro no especificado.</div>";
    exit();
}

$perro = new Perro($idPerro);
if (!$perro->buscarPorId()) {
    echo "<div class='alert alert-danger'>Perro no encontrado.</div>";
    exit();
}
?>





<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0">
                <div style="background-color: rgb(90, 179, 90);" class="card-header text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-dog me-3 fs-4"></i>
                        <h4 class="mb-0 fw-bold">Modificar mi perro</h4>
                    </div>
                </div>

                <?php if (isset($_GET["actualizado"])) { ?>
                    <div class="alert alert-success" role="alert">
                        ¡Perro actualizado correctamente!
                    </div>
                <?php } elseif (isset($_GET["error"])) { ?>
                    <div class="alert alert-danger" role="alert">
                        Hubo un error al actualizar el perro.
                    </div>
                <?php } ?>

                <form action="?pid=<?php echo base64_encode("presentacion/perro/respuestaActualizarPerro.php") ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $perro->getIdPerro(); ?>">
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label">Nombre del perro</label>
                            <input type="text" name="nombre" class="form-control" required value="<?php echo $perro->getNombre(); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tamaño</label>
                            <select name="idTamaño" class="form-select" required>
                                <?php
                                $tamaños = (new Tamaño())->consultar();
                                $idTamañoPerro = $perro->getIdTamaño();
                                if ($idTamañoPerro instanceof Tamaño) {
                                    $idTamañoPerro = $idTamañoPerro->getIdTamaño();
                                }

                                foreach ($tamaños as $t) {
                                    $selected = "";
                                    if ($idTamañoPerro == $t->getIdTamaño()) {
                                        $selected = "selected";
                                    }
                                    echo "<option value='{$t->getIdTamaño()}' $selected>{$t->getTamaño()}</option>";
                                }
                                ?>

                            </select>
                        </div>

                    </div>

                    <div class="card-footer bg-light border-0 py-3">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Limpiar
                            </button>
                            <button type="submit" name="modificarPerro" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>