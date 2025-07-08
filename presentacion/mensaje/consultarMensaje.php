<?php
if(($_SESSION["rol"]) != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}

include("presentacion/fondo.php");
include("presentacion/dueño/menuDueño.php");
$id = $_SESSION["id"];
$mensaje = new Mensaje("", "", "", $id, "", "");
$mensajes = $mensaje->consultarMensajes();


?>

<style>

        
    

        
        
        
    </style>
</head>
<body class="container-custom">
    <div class="container mt-4">
        <h3 class="mb-4 text-center" style="color: #28a745; font-weight: 700;"> Gestión de Mensajes </h3>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paseador</th>
                        <th>Paseo</th>
                        <th>Perro</th>
                        <th>Tarifa Actual</th>
                        <th>Tarifa Nueva</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($mensajes as $mens) {
                        echo "<tr>";
                        echo "<td>" . $mens->getIdMensaje() . "</td>";
                        echo "<td>" . $mens->getPaseador()->getNombre() . " " . $mens->getPaseador()->getApellido() . "</td>";
                        echo "<td>" . $mens->getPaseo()->getIdPaseo() . "</td>";
                        echo "<td>" . $mens->getPaseo()->getPerro()->getNombre() . "</td>";
                        echo "<td>$" . number_format($mens->getPaseo()->getTarifa(),2) . "</td>";
                        echo "<td>$" . number_format($mens->getTarifaNueva(), 2) . "</td>";
                        if(is_null($mens->getEstado())){
                        echo "<td>
                                <button title='Aceptar' class='btn btn-success btn-sm me-1 btnAceptar'
                                        data-idmensaje='" . $mens->getIdMensaje() . "'
                                        data-idpaseo='" . $mens->getPaseo()->getIdPaseo() . "'
                                        data-idpaseador='" . $mens->getPaseador()->getId() . "'
                                        data-idperro='" . $mens->getPaseo()->getPerro()->getIdPerro() . "'
                                        data-nuevatarifa='" . $mens->getTarifaNueva() . "'
                                        data-bs-toggle='modal' data-bs-target='#staticBackdropAceptar'>
                                    <i class='fas fa-check'></i>
                                </button>
                                
                                <button title='Rechazar' class='btn btn-danger btn-sm btnRechazar'
                                        data-idmensaje='" . $mens->getIdMensaje() . "'
                                        data-idpaseo='" . $mens->getPaseo()->getIdPaseo() . "'
                                        data-idpaseador='" . $mens->getPaseador()->getId() . "'
                                        data-idperro='" . $mens->getPaseo()->getPerro()->getIdPerro() . "'
                                        data-nuevatarifa='" . $mens->getTarifaNueva() . "'
                                        data-bs-toggle='modal' data-bs-target='#staticBackdropRechazar'>
                                    <i class='fas fa-times'></i>
                                </button>
                              </td>";
                        } else {
                            echo "<td><button title='Tarifa ya modificada' type='button' class='btn btn-sm btn-secondary'>
                                            <i class='fas fa-check'></i>
                                </button></td>";
                        }
                        echo "</tr>";
                    }
                    ?>   
                </tbody>
            </table>
        </div>
    </div>



    <div class="modal fade" id="staticBackdropAceptar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabelAceptar" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h1 class="modal-title text-white fs-4" id="staticBackdropLabelAceptar"><i class='fas fa-check'></i> Aceptar Nueva Tarifa</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert">
                        <h5 class="alert-heading">🎉 ¡Confirmación de Aceptación!</h5>
                        <p>¿Estás segur@ de <strong>aceptar</strong> esta nueva tarifa?</p>
                        <hr>
                        <p class="mb-0"><i class='fas fa-check'></i> <strong>Al aceptar:</strong> La tarifa del paseo se modificará automáticamente.</p>
                    </div>
                    
                    <form action="?pid=<?php echo base64_encode("presentacion/mensaje/respuestaMensaje.php") ?>" method="post" id="formAceptarTarifa">
                        <div class="mb-3">
                            <label for="idPaseadorAceptar" class="form-label"><i class="fas fa-user text-success"></i> Paseador:</label>
                            <input type="text" class="form-control" id="idPaseadorAceptar" name="idPaseador" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="idPaseoAceptar" class="form-label"><i class="fas fa-walking text-success"></i> Paseo:</label>
                            <input type="text" class="form-control" id="idPaseoAceptar" name="idPaseo" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="idPerroAceptar" class="form-label"><i class="fas fa-paw text-success"></i> Perrito:</label>
                            <input type="text" class="form-control" id="idPerroAceptar" name="idPerro" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nuevaTarifaAceptar" class="form-label"><i class="fas fa-dollar-sign text-success"></i> Nueva Tarifa:</label>
                            <input type="text" class="form-control" id="nuevaTarifaAceptar" name="nuevaTarifa" readonly>
                        </div>
                        <input type="hidden" id="idMensajeAceptar" name="idMensaje">
                        <input type="hidden" name="accion" value="aceptar">
                </div>
                <div class="modal-footer bg-success">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="confirmarAceptar" class="btn btn-success">
                        <i class="fas fa-check"></i> Aceptar Tarifa
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Rechazar -->
    <div class="modal fade" id="staticBackdropRechazar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabelRechazar" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h1 class="modal-title text-white fs-4" id="staticBackdropLabelRechazar"><i class='fas fa-times'></i> Rechazar Nueva Tarifa</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle fa-lg text-warning me-2"></i> ¡Atención!</h5>
                        <p>¿Estás segur@ de <strong>rechazar</strong> esta nueva tarifa?</p>
                        <hr>
                        <p class="mb-0"><i class='fas fa-times'></i> <strong>Al rechazar:</strong> El paseo se dará por <strong>cancelado</strong> automáticamente y se notificará al dueño de la mascota.</p>
                    </div>
                    
                    <form action="?pid=<?php echo base64_encode("presentacion/mensaje/respuestaMensaje.php") ?>" method="post" id="formRechazarTarifa">
                        <div class="mb-3">
                            <label for="idPaseadorRechazar" class="form-label"><i class="fas fa-user text-danger"></i> Paseador:</label>
                            <input type="text" class="form-control" id="idPaseadorRechazar" name="idPaseador" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="idPaseoRechazar" class="form-label"><i class="fas fa-walking text-danger"></i> Paseo:</label>
                            <input type="text" class="form-control" id="idPaseoRechazar" name="idPaseo" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="idPerroRechazar" class="form-label"><i class="fas fa-paw text-success"></i> Perrito:</label>
                            <input type="text" class="form-control" id="idPerroRechazar" name="idPerro" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nuevaTarifaAceptar" class="form-label"><i class="fas fa-dollar-sign text-success"></i> Nueva Tarifa:</label>
                            <label for="nuevaTarifaRechazar" class="form-label"></label>
                            <input type="text" class="form-control" id="nuevaTarifaRechazar" name="nuevaTarifa" readonly>
                        </div>
                        <input type="hidden" id="idMensajeRechazar" name="idMensaje">
                        <input type="hidden" name="accion" value="rechazar">
                </div>
                <div class="modal-footer bg-danger">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="confirmarRechazar" class="btn btn-danger">
                        <i class="fas fa-times"></i> Rechazar Tarifa
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function () {
        $(".btnAceptar").click(function () {
            var idMensaje = $(this).data("idmensaje");
            var idPaseo = $(this).data("idpaseo");
            var idPaseador = $(this).data("idpaseador");
            var idPerro = $(this).data("idperro");
            var nuevaTarifa = $(this).data("nuevatarifa");

            $("#idMensajeAceptar").val(idMensaje);
            $("#idPaseoAceptar").val(idPaseo);
            $("#idPaseadorAceptar").val(idPaseador);
            $("#idPerroAceptar").val(idPerro);
            $("#nuevaTarifaAceptar").val(nuevaTarifa);
        });

        $(".btnRechazar").click(function () {
            var idMensaje = $(this).data("idmensaje");
            var idPaseo = $(this).data("idpaseo");
            var idPaseador = $(this).data("idpaseador");
            var idPerro = $(this).data("idperro");
            var nuevaTarifa = $(this).data("nuevatarifa");

            $("#idMensajeRechazar").val(idMensaje);
            $("#idPaseoRechazar").val(idPaseo);
            $("#idPaseadorRechazar").val(idPaseador);
            $("#idPerroRechazar").val(idPerro);
            $("#nuevaTarifaRechazar").val(nuevaTarifa);
        });
    }); 
</script>

</body>