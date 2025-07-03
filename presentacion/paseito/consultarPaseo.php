<body>
    <?php
    $id = $_SESSION["id"];
    $rol = $_SESSION["rol"];
    include("presentacion/fondo.php");
    include("presentacion/" . $rol . "/menu" . ucfirst($rol) . ".php");
    ?>

    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Listado de Paseos</h1>

            <div class="table-responsive">
                <table class="table table-custom table-hover text-light">
                    <thead>
                        <tr>
                            <th>Id Paseo</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Tarifa</th>
                            <?php
                            if ($rol != "paseador") {
                                ?>
                                <th>Paseador</th>
                                <?php
                            } ?>
                            <th>Perro</th>
                            <?php
                            if ($rol != "dueño") {
                                ?>
                                <th>Dueño</th>
                                <?php
                            } ?>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $paseo = new Paseo();
                        $paseos = $paseo->consultarTodos($id, $rol);
                        foreach ($paseos as $pas) {
                            $fechaFormateada = date("d/m/Y", strtotime($pas->getFecha()));
                            $horaFormateada = date("H:i", strtotime($pas->getHora()));
                            ?>
                            <tr>
                                <td><?php echo $pas->getIdPaseo() ?></td>
                                <td><?php echo htmlspecialchars($fechaFormateada) ?></td>
                                <td><?php echo htmlspecialchars($horaFormateada) ?></td>
                                <td><span class="tarifa-badge">$<?php echo number_format($pas->getTarifa(), 2) ?></span></td>
                                <?php
                                if ($rol != "paseador") {
                                    ?>
                                    <td><?php echo $pas->getIdPaseador()->getNombre() . " " . $pas->getIdPaseador()->getApellido() ?>
                                    </td>
                                    <?php
                                }
                                ?>
                                <td><?php echo $pas->getPerro()->getNombre() ?></td>
                                <?php
                                if ($rol != "dueño") {
                                    ?>
                                    <td><?php echo $pas->getPerro()->getIdDueño()->getNombre() . " " . $pas->getPerro()->getIdDueño()->getApellido() ?>
                                    </td>
                                    <?php
                                }
                                ?>
                                <?php
                                $estadoId = $pas->getEstadoPaseo()->getIdEstadoPaseo();
                                $estadoNombre = $pas->getEstadoPaseo()->getEstado();
                                $claseEstado = "";

                                if ($estadoId == 1) {
                                    $claseEstado = "text-warning";
                                } else if ($estadoId == 2) {
                                    $claseEstado = "text-info";
                                } else if ($estadoId == 3) {
                                    $claseEstado = "text-success";
                                } else if ($estadoId == 4) {
                                    $claseEstado = "text-danger";
                                } else {
                                    $claseEstado = "text-secondary";
                                }
                                ?>

                                <td><div class="rounded-pill" style="background:rgba(0, 0, 0, 0.1);"><span class="<?php echo $claseEstado; ?> fw-bold"><?php echo $estadoNombre; ?></span></div></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>




</body>