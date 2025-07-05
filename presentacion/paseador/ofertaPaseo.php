<?php
include("presentacion/fondo.php");
include("presentacion/paseador/menuPaseador.php");
?>

<body>
    <?php
    $id = $_SESSION["id"];
    $rol = $_SESSION["rol"];
    if ($rol == "admin") {
        header("Location: ?pid=" . base64_encode("presentacion/paseito/consultarPaseoAdmin.php"));
        exit();
    }

    $paseo = new Paseo();
    $estados = $paseo->obtenerEstados();

    ?>

    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Ofertas de Paseos</h1>

            <div class="mb-3 text-start">
                <label for="filtroEstado" class="form-label text-light">Filtrar por Estado:</label>
                <select id="filtroEstado" class="form-select w-auto d-inline-block">
                    <option value="todos">Todos</option>
                    <?php foreach ($estados as $estado) { ?>
                        <option value="<?php echo $estado->getIdEstadoPaseo(); ?>">
                            <?php echo $estado->getEstado(); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

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
                            <th>Elecion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php


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
                                    <td><?php echo $pas->getPaseador()->getNombre() . " " . $pas->getPaseador()->getApellido(); ?></td>

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

                                <td data-estado-id="<?php echo $estadoId; ?>">
                                    <div class="rounded-pill" style="background:rgba(0, 0, 0, 0.1);">
                                        <span class="<?php echo $claseEstado; ?> fw-bold"><?php echo $estadoNombre; ?></span>
                                    </div>
                                </td>

                                <td>
                                    <button
                                        class="btn btn-sm btn-success aceptar-btn"
                                        data-id="<?php echo $pas->getIdPaseo(); ?>"
                                        style="<?php echo ($estadoId != 1 && $estadoId != 4) ? 'display: none;' : ''; ?>">
                                        Aceptar
                                    </button>
                                    <button
                                        class="btn btn-sm btn-danger rechazar-btn"
                                        data-id="<?php echo $pas->getIdPaseo(); ?>"
                                        style="<?php echo ($estadoId != 1 && $estadoId != 2) ? 'display: none;' : ''; ?>">
                                        Rechazar
                                    </button>
                                    <button
                                        class="btn btn-sm btn-warning finalizar-btn"
                                        data-id="<?php echo $pas->getIdPaseo(); ?>"
                                        style="<?php echo ($estadoId != 2) ? 'display: none;' : ''; ?>">
                                        Finalizar
                                    </button>
                                </td>


                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".aceptar-btn, .rechazar-btn, .finalizar-btn").click(function() {
                const idPaseo = $(this).data("id");
                let accion = "";

                if ($(this).hasClass("aceptar-btn")) {
                    accion = "aceptar";
                } else if ($(this).hasClass("rechazar-btn")) {
                    accion = "rechazar";
                } else if ($(this).hasClass("finalizar-btn")) {
                    accion = "finalizar";
                }

                $.ajax({
                    url: "cambiarEstadoPaseo.php",
                    type: "POST",
                    data: {
                        idPaseo,
                        accion
                    },
                    success: function(respuesta) {
                        alert(respuesta);
                        location.reload();
                    },
                    error: function() {
                        alert("Ocurrió un error");
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById("filtroEstado").addEventListener("change", function() {
            const filtro = this.value;
            const filas = document.querySelectorAll("tbody tr");
            let visibles = 0;

            filas.forEach(fila => {
                const tdEstado = fila.querySelector('td[data-estado-id]');
                if (!tdEstado) return;

                const estadoId = tdEstado.getAttribute('data-estado-id');

                if (filtro === "todos" || estadoId === filtro) {
                    fila.style.display = "";
                    visibles++;
                } else {
                    fila.style.display = "none";
                }
            });

            let noResultRow = document.getElementById("no-result");

            if (visibles === 0) {
                if (!noResultRow) {
                    const row = document.createElement("tr");
                    row.id = "no-result";
                    row.innerHTML = '<td colspan="9" class="text-center text-light">No hay paseos con este estado.</td>';
                    document.querySelector("tbody").appendChild(row);
                }
            } else if (noResultRow) {
                noResultRow.remove();
            }
        });
    </script>


</body>