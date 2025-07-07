<?php
if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
include("presentacion/fondo.php");
include("presentacion/admin/menuAdmin.php");

require_once("logica/Paseador.php");

$paseador = new Paseador();
$paseadores = $paseador->consultarTodos();
?>

<body>
    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Listado de Paseadores</h1>

            <div class="mb-4 text-center">
                <input type="text" id="filtro" class="form-control w-50 mx-auto"
                    placeholder="Buscar paseador por nombre, correo o teléfono...">
            </div>

            <div class="table-responsive" id="resultados">
                <table class="table table-custom table-hover text-light">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>IdPaseador</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paseadores as $pas):
                            if ($pas->getEstadoPaseador()->getIdEstadoPaseador() != 3):
                                $estadoId = $pas->getEstadoPaseador()->getIdEstadoPaseador();
                                $estadoNombre = $pas->getEstadoPaseador()->getEstado();
                                $claseEstado = ($estadoId == 1) ? "text-success" : (($estadoId == 2) ? "text-danger" : "text-secondary");
                                ?>
                                <tr id="paseador-<?php echo $pas->getId(); ?>">
                                    <td>
                                        <img src="<?php echo htmlspecialchars($pas->getFoto()); ?>" class="rounded-circle"
                                            style="width: 50px; height: 50px; object-fit: cover;" alt="Foto paseador"
                                            onerror="this.src='img/default-profile.png'">
                                    </td>
                                    <td><?php echo $pas->getId(); ?></td>
                                    <td><?php echo $pas->getNombre() . " " . $pas->getApellido(); ?></td>
                                    <td><?php echo $pas->getDescripcion(); ?></td>
                                    <td><?php echo $pas->getCorreo(); ?></td>
                                    <td><?php echo $pas->getTelefono(); ?></td>
                                    <td>
                                        <div class="rounded-pill" style="background:rgba(0, 0, 0, 0.1);">
                                            <div class="<?php echo $claseEstado; ?> fw-bold"><?php echo $estadoNombre; ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <button title="Cambiar estado" type="button" class="btn btn-sm btn-danger btnCambiar"
                                            data-idcambiar="<?php echo $pas->getId(); ?>" data-estado="<?php echo $estadoId; ?>"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                            
                                    </td>
                                </tr>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header bg-success">
                    <h1 class="modal-title text-white fs-4" id="staticBackdropLabel">Confirmar cambio</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está segur@ de cambiar el estado del paseador con ID <span id="paseador-id-span"
                            class="fw-bold text-success"></span>?</p>
                    <p>
                        <i class="fas fa-exclamation-triangle fa-lg text-warning me-2"></i>
                        El usuario <span class="text-danger fw-bold">NO</span> podrá acceder al sistema al cambiar el
                        estado a
                        <span class="text-danger fw-bold">Inactivo</span>.
                    </p>
                </div>
                <div class="modal-footer bg-success">
                    <button type="button" id="confirmarCambiar" class="btn btn-danger" data-bs-dismiss="modal">Cambiar
                        Estado</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#filtro").keyup(function () {
                if ($("#filtro").val().length > 2) {
                    var ruta = "modificarPaseadorAjax.php?filtro=" + encodeURIComponent($("#filtro").val());
                    $("#resultados").load(ruta);
                } else if ($("#filtro").val().length === 0) {
                    location.reload();
                }
            });

            let paseadorIdCambiar = null;
            let idEstado = null;

            $(document).on("click", ".btnCambiar", function () {
                paseadorIdCambiar = $(this).data("idcambiar");
                idEstado = $(this).data("estado");
                $("#paseador-id-span").text(paseadorIdCambiar);
            });

            $("#confirmarCambiar").click(function () {
                if (paseadorIdCambiar) {
                    var ruta = "cambiarPaseadorAjax.php?idPaseador=" + paseadorIdCambiar + "&idEstado=" + idEstado;
                    $.get(ruta, function () {
                        let filtroActual = $("#filtro").val();
                        let rutaRecarga = "modificarPaseadorAjax.php?filtro=" + encodeURIComponent(filtroActual);
                        $("#resultados").load(rutaRecarga);
                    });
                }
            });
        });
    </script>
</body>