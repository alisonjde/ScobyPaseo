<?php
include("presentacion/fondo.php");
include("presentacion/admin/menuAdmin.php");



if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
?>


<body>


    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Paseadores a confirmar</h1>

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
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $paseador = new Paseador();
                        $paseadores = $paseador->consultar_estado();
                        foreach ($paseadores as $pas) {
                        ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($pas->getFoto()) ?>"
                                        class="rounded-circle"
                                        style="width: 50px; height: 50px; object-fit: cover;"
                                        alt="Foto paseador"
                                        onerror="this.src='img/default-profile.png'">
                                </td>
                                <td><?php echo $pas->getId() ?></td>
                                <td><?php echo $pas->getNombre() . " " . $pas->getApellido() ?></td>
                                <td><?php echo $pas->getCorreo() ?></td>
                                <td><?php echo $pas->getTelefono() ?></td>
                                <td id="estadoPaseador<?php echo $pas->getId() ?>">
                                    <?php echo $pas->getEstadoPaseador() ?>
                                </td>

                                <td>

                                    <button class="btn btn-sm btn-primary btn-confirmar"
                                        id="btnConfirmar<?php echo $pas->getId() ?>"
                                        data-id="<?php echo $pas->getId() ?>"
                                        title="Confirmar paseador">
                                        <i class="fas fa-check"></i>
                                    </button>


                                    <button class="btn btn-sm btn-danger btn-eliminar"
                                        data-id="<?php echo $pas->getId() ?>"
                                        data-nombre="<?php echo htmlspecialchars($pas->getNombre()) ?>"
                                        title="Eliminar paseador">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmarEliminacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al paseador <strong id="nombrePaseador"></strong>?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formEliminar" method="post" action="?pid=<?php echo base64_encode("presentacion/paseador/eliminarEstado.php") ?>">
                        <input type="hidden" name="idPaseador" id="idPaseador">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#filtro").keyup(function() {
                var texto = $("#filtro").val().trim();
                if (texto.length === 0 || texto.length > 2) {
                    const ruta = "ConfirmarPaseadorAjax.php?filtro=" + encodeURIComponent(texto);
                    $("#resultados").load(ruta);
                }
            });
        });




        document.addEventListener('DOMContentLoaded', function() {
            const botonesEliminar = document.querySelectorAll('.btn-eliminar');
            const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacion'));
            const nombrePaseador = document.getElementById('nombrePaseador');
            const idPaseador = document.getElementById('idPaseador');
            const formEliminar = document.getElementById('formEliminar');

            botonesEliminar.forEach(boton => {
                boton.addEventListener('click', function() {
                    nombrePaseador.textContent = this.getAttribute('data-nombre');
                    idPaseador.value = this.getAttribute('data-id');
                    modal.show();
                });
            });

            formEliminar.addEventListener('submit', function(e) {});
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".btn-confirmar").click(function() {
                const id = $(this).data("id");
                const ruta = "cambiarEstadoPaseadorAjax.php?idPaseador=" + id;

                $.get(ruta, function(response) {
                    if (response.trim() === "Activo") {
                        location.reload(); 
                    } else {
                        alert("Error al actualizar el estado: " + response);
                    }
                });
            });
        });
    </script>



</body>