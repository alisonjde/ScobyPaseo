<?php

include("presentacion/fondo.php");
?>

<body>
    <?php
    $rol = $_SESSION["rol"];
    include("presentacion/fondo.php");
    include("presentacion/" . $rol . "/menu" . ucfirst($rol) . ".php");

    $idUsuario = $_SESSION["id"];

    ?>

    <div class="text-center py-3 ">



        <div class="container glass py-3">
            <h1 class="display-6">Listado de Paseos</h1>

            <div class="mb-4 text-center">
                <input type="text" id="filtro" class="form-control w-50 mx-auto"
                    placeholder="Buscar perro por nombres, ids...">
            </div>


            <div class="table-responsive">
                <table class="table table-custom table-hover text-light">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Tarifa</th>
                            <th>Perro</th>
                            <th>Dueño</th>
                            <?php if ($rol != "paseador") { ?>
                                <th>Paseador</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="tablaPaseos">
                        <?php
                        $paseo = new Paseo();

                        if ($rol == "paseador") {
                            $paseo = new Paseo();
                            $paseos = $paseo->consultarPorPaseador($_SESSION["id"]);
                        } else {
                            $paseo = new Paseo();
                            $paseos = $paseo->consultarTodos();
                        }

                        foreach ($paseos as $paseo) {
                            $fechaFormateada = date("d/m/Y", strtotime($paseo->getFecha()));
                            $horaFormateada = date("H:i", strtotime($paseo->getHora()));
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fechaFormateada) ?></td>
                                <td><?php echo htmlspecialchars($horaFormateada) ?></td>
                                <td><span class="tarifa-badge">$<?php echo number_format($paseo->getTarifa(), 2) ?></span></td>
                                <td><?php echo htmlspecialchars($paseo->getPerro()->getNombre()); ?></td>
                                <td><?php echo htmlspecialchars(
                                        $paseo->getPerro()->getIdDueño()->getNombre() . ' ' .
                                            $paseo->getPerro()->getIdDueño()->getApellido()
                                    ); ?></td>
                                <?php if ($rol != "paseador") { ?>
                                    <td><?php echo htmlspecialchars($paseo->getIdPaseador()->getNombre()) ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>



 <script>
    $(document).ready(function () {
        $("#filtro").keyup(function () {
            let texto = $("#filtro").val().trim();
            const idPaseador = <?php echo json_encode($_SESSION["id"]); ?>;

            
            if (texto.length === 0 || texto.length > 2) {
                const ruta = "ConfirmarPaseaAjax.php?filtro=" + encodeURIComponent(texto) + "&id=" + idPaseador;
                $("#tablaPaseos").load(ruta);
            }
        });
    });
</script>




</body>