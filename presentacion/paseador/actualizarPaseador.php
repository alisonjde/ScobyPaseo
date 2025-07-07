<?php

include("presentacion/admin/menuAdmin.php");
include("presentacion/fondo.php");

if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

require_once("logica/Paseador.php");

$mostrarForm = false;
$mensajeError = "";
$paseador = null;
$id = "";

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
    $paseador = new Paseador($id);
    if ($paseador->buscar()) {
        $mostrarForm = true;
    } else {
        $mensajeError = "<div class='alert alert-danger mt-2'>No se encontró el paseador con ID: $id </div>";
    }
} else if (isset($_POST['id'])) {
    $mensajeError = "<div class='alert alert-warning mt-2'>ID inválido.</div>";
}

?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0">
                <div style="background-color: rgb(90, 179, 90);" class="card-header text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit me-3 fs-4"></i>
                        <h4 class="mb-0 fw-bold">Modificar Paseador</h4>
                    </div>
                </div>
                
                <?php if (isset($_GET["actualizado"])){ ?>
                    <div class="alert alert-success" role="alert">
                        Paseador actualizado correctamente.
                    </div>
                <?php }elseif (isset($_GET['error']) && $_GET['error'] === 'ImagenNoValida') {
                            echo '<div class="alert alert-danger mt-2">';
                            echo '<i class="fas fa-exclamation-triangle me-1"></i> La imagen subida no es válida. Por favor sube un archivo JPG, PNG o GIF.';
                            echo '</div>';
                        }elseif(isset($_GET["error"])){ ?>
                    <div class="alert alert-danger" role="alert">
                        Hubo un error al actualizar el paseador.
                    </div>
                <?php } if(!$mostrarForm){?>

                    <form method="post" action="" class="p-3 bg-light rounded shadow-sm" style="max-width: 400px; margin: 0 auto;">
                        <div class="mb-3">
                            <label for="id" class="form-label text-dark">Buscar paseador por ID</label>
                            <input type="text" id="id" name="id" class="form-control border-primary"
                                placeholder="Ingrese el id" value="<?php echo htmlspecialchars($id); ?>" required>
                            <?php echo $mensajeError; ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>

                <?php } else { ?>
                    <form action="?pid=<?php echo base64_encode("presentacion/paseador/respuestaActualizarPaseador.php") ?>" id="formModificarPaseador" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $paseador->getId(); ?>">
                        <div class="card-body p-4">

                            <div class="form-section fade-in mb-4">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                                <i class="fas fa-user text-success"></i>
                                            </div>
                                            <h5 class="section-title mb-0 text-success fw-bold">
                                                Información Personal
                                            </h5>
                                        </div>
                                        <hr class="text-success">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="form-label fw-semibold">
                                            <i class="fas fa-user text-success me-2"></i>Nombre<span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control border-success-subtle" id="nombre"
                                            name="nombre" required placeholder="Ingrese el nombre" value="<?php echo $paseador->getNombre(); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="apellido" class="form-label fw-semibold">
                                            <i class="fas fa-user text-success me-2"></i>Apellido <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control border-success-subtle" id="apellido"
                                            name="apellido" required placeholder="Ingrese el apellido" value="<?php echo $paseador->getApellido(); ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="telefono" class="form-label fw-semibold">
                                            <i class="fas fa-phone text-success me-2"></i> <span class="text-danger">*</span>
                                        </label>
                                        <input type="tel" class="form-control border-success-subtle" id="telefono"
                                            name="telefono" required placeholder="Ej: 3001234567" value="<?php echo $paseador->getTelefono(); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="foto" class="form-label fw-semibold">
                                            <i class="fas fa-camera text-success me-2"></i>Foto de Perfil
                                        </label>
                                        <input type="file" class="form-control border-success-subtle" id="foto" name="foto"
                                            accept="image/*">
                                        <div class="form-text text-muted mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section fade-in">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                                <i class="fas fa-dog text-success"></i>
                                            </div>
                                            <h5 class="section-title mb-0 text-success fw-bold">
                                                Información del Paseador
                                            </h5>
                                        </div>
                                        <hr class="text-success">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="descripcion" class="form-label fw-semibold">
                                            <i class="fas fa-info-circle text-success me-2"></i>Descripción <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control border-success-subtle" id="descripcion"
                                            name="descripcion" rows="4" required
                                            placeholder="Describe la experiencia y características del paseador..."><?php echo $paseador->getDescripcion(); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-0 py-3">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-times me-2"></i>Limpiar
                                </button>
                                <button type="submit" name="modificarPaseador" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
