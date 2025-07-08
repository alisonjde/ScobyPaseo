<?php
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
include("presentacion/fondo.php");
include("presentacion/paseador/menuPaseador.php");

$id = $_SESSION["id"];
$paseo = new Paseo("", "", "", "", $id, "", "");
$paseos = $paseo->consultarTarifa();

?>


<div class="text-center py-3 hero-text">
    <div class="container glass py-3">
        <?php
        if (isset($_GET["estado"])) {
            if ($_GET["estado"] == "exito") {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Éxito:</strong> La tarifa fue modificada correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            
            }
        }?>
        <h1 class="display-6">Listado de Paseos En Proceso</h1>
        <div class="table-responsive">
            <table class="table table-custom table-hover text-light">
                <thead>
                    <tr>
                        <th>Id Paseo</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tarifa</th>
                        <th>Perro</th>
                        <th>Dueño</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paseos as $pas) {
                        echo "<tr>
                            <td>" . $pas->getIdPaseo() . "</td>
                            <td>" . $pas->getFecha() . "</td>
                            <td>" . $pas->getHora() . "</td>
                            <td>" . $pas->getTarifa() . "</td>
                            <td>" . $pas->getPerro()->getNombre() . "</td>
                            <td>" . $pas->getPerro()->getIdDueño()->getNombre() . "</td>
                            <td>";
                            $mensaje = new Mensaje("", $id, $pas->getIdPaseo());
                            if(!$mensaje->existe()){
                                echo "<button title='Cambiar tarifa' type='button' class='btn btn-sm btn-success btnCambiarTarifa'
                                            data-iddueño='" . $pas->getPerro()->getIdDueño()->getId() . "'
                                            data-idcambiar='" . $pas->getIdPaseo() . "'
                                            data-tarifaactual='" . $pas->getTarifa() . "' 
                                            data-bs-toggle='modal' data-bs-target='#staticBackdrop'>
                                            <i class='fas fa-sync-alt' ></i>
                                </button>";
                            }else {
                                echo "<button title='Tarifa ya modificada' type='button' class='btn btn-sm btn-secondary'>
                                            <i class='fas fa-check'></i>
                                </button>";
                            }
                                
                            echo "</td>
                        </tr>";
                    } ?>
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
                <h1 class="modal-title text-white fs-4" id="staticBackdropLabel">Modificar tarifa del paseo</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="?pid=<?php echo base64_encode("presentacion/paseito/respuestaTarifa.php") ?>" method="post" id="formCambiarTarifa">
                    <div class="mb-3">
                        <label for="idDueño" class="form-label">ID Dueño:</label>
                        <input type="text" class="form-control" id="idDueño" name="idDueño" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="idPaseo" class="form-label">ID Paseo:</label>
                        <input type="text" class="form-control" id="idPaseo" name="idPaseo" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tarifaActual" class="form-label">Tarifa Actual:</label>
                        <input type="text" class="form-control" id="tarifaActual" name="tarifaActual" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nuevaTarifa" class="form-label">Nueva Tarifa:</label>
                        <input type="number" class="form-control" id="nuevaTarifa" name="nuevaTarifa"
                            placeholder="Ingrese la nueva tarifa" required min="0" step="1000">
                    </div>
            </div>
            <div class="modal-footer bg-success">
                <button type="submit" name="confirmarCambiarTarifa" class="btn btn-outline-light"
                    data-bs-dismiss="modal">Cambiar Tarifa</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".btnCambiarTarifa").click(function () {
            var idDueño = $(this).data("iddueño");
            var idPaseo = $(this).data("idcambiar");
            var tarifaActual = $(this).data("tarifaactual");
            $("#idDueño").val(idDueño);
            $("#idPaseo").val(idPaseo);
            $("#tarifaActual").val(tarifaActual);
        });


    });
</script>

</body>

</html>