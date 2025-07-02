<?php

include("presentacion/fondo.php");
?>

<body>
    <?php
    $rol=$_SESSION["rol"];
    include("presentacion/fondo.php");
    include("presentacion/".$rol."/menu". ucfirst($rol) .".php");
    ?>

    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Listado de Paseos</h1>

            <div class="table-responsive">
                <table class="table table-custom table-hover text-light">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Tarifa</th>
                            <?php
                            if($rol!="paseador"){
                            ?>
                            <th>Paseador</th>
                            <?php
                            }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $paseo = new Paseo();
                        $paseos = $paseo->consultarTodos();
                        foreach($paseos as $paseo) {
                            $fechaFormateada = date("d/m/Y", strtotime($paseo->getFecha()));
                            $horaFormateada = date("H:i", strtotime($paseo->getHora()));
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fechaFormateada) ?></td>
                            <td><?php echo htmlspecialchars($horaFormateada) ?></td>
                            <td><span class="tarifa-badge">$<?php echo number_format($paseo->getTarifa(), 2) ?></span></td>
                            <?php
                            if($rol!="paseador"){
                            ?>
                            <td><?php echo htmlspecialchars($paseo->getIdPaseador()->getNombre()) ?></td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>




</body>