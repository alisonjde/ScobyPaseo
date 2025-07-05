<?php
include("presentacion/dueño/menuDueño.php");
include("presentacion/fondo.php");

$id = $_GET["id"] ?? null;

if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}

if ($id) {
    $paseador = new Paseador($id);
    $paseador->consultar2();
} else {
    echo "<div class='alert alert-danger text-center'>ID del paseador no proporcionado.</div>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fechaHora = $_POST["fechaHora"];
    $fecha = date("Y-m-d", strtotime($fechaHora));
    $hora = date("H:i:s", strtotime($fechaHora));

    $tarifaPorHora = $_POST["tarifa"];
    $horas = intval($_POST["horas"]);
    $tarifa = $tarifaPorHora * $horas;

    $direccion = $_POST["direccion"];
    $perro1 = $_POST["perro1"];
    $perro2 = $_POST["perro2"];

    $perros = [$perro1];
    if (!empty($perro2) && $perro2 != $perro1) {
        $perros[] = $perro2;
    }

    $paseo = new Paseo("", $tarifa, $fecha, $hora, $id, 1, $perros, $direccion);
    $idPaseo = $paseo->buscarPaseoConCupo();

    if ($idPaseo !== null) {
        $paseoExistente = new Paseo($idPaseo);
        foreach ($perros as $idPerro) {
            $paseoExistente->asociarPerro($idPerro);
        }
        echo "<div class='alert alert-success text-center'>
        Tu perro fue unido a un paseo ya existente.<br>
        Valor total: <strong>$" . number_format($tarifa, 0, ',', '.') . "</strong>
    </div>";
    } else {
        $idPaseoNuevo = $paseo->registrar();
        foreach ($perros as $idPerro) {
            $paseo->asociarPerro($idPerro);
        }
        echo "<div class='alert alert-success text-center'>
        ¡Nuevo paseo registrado con tu perro!<br>
        Valor total: <strong>$" . number_format($tarifa, 0, ',', '.') . "</strong>
    </div>";
    }
}


$perro = new Perro();
$perros = $perro->consultarPorDueño($_SESSION["id"]);
$minDateTime = date("Y-m-d\TH:i");
?>

<div class="container-xl py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="alert alert-info text-center">
                        Estás pidiendo un paseo con <strong><?php echo $paseador->getNombre() . " " . $paseador->getApellido(); ?></strong>
                    </div>

                    <form class="mt-4" action="#" method="post">
                        <div class="mb-3">
                            <label for="fechaHora" class="form-label">Selecciona fecha y hora del paseo:</label>
                            <input
                                type="datetime-local"
                                id="fechaHora"
                                name="fechaHora"
                                class="form-control"
                                required
                                min="<?php echo $minDateTime; ?>">
                        </div>


                        <div class="mb-3">
                            <label for="tarifa" class="form-label">Ingrese la tarifa que ofrese por hora de paseo:</label>
                            <input type="number" id="tarifa" name="tarifa" class="form-control" min="15000" required placeholder="Ej: 15000">
                        </div>


                        <div class="mb-3">
                            <label for="horas" class="form-label">Cantidad de horas del paseo:</label>
                            <select name="horas" id="horas" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Selecciona hasta 2 perros para el paseo:</label>



                            <select name="perro1" id="perro1" class="form-select mb-2" required>
                                <option value="">Selecciona el primer perro</option>
                                <?php
                                foreach ($perros as $p) {
                                    echo "<option value='{$p->getIdPerro()}'>{$p->getNombre()}</option>";
                                }
                                ?>
                            </select>

                            <select name="perro2" id="perro2" class="form-select">
                                <option value="">Selecciona el segundo perro</option>
                                <?php
                                foreach ($perros as $p) {
                                    echo "<option value='{$p->getIdPerro()}'>{$p->getNombre()}</option>";
                                }
                                ?>
                            </select>

                        </div>


                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección donde se recogeran los perros:</label>
                            <input type="text" id="direccion" name="direccion" class="form-control" required placeholder="Ej: Calle 123 #45-67">
                        </div>



                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Confirmar paseo</button>
                            <a href="javascript:history.back()" class="btn btn-outline-success ms-2">← Volver</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function actualizarOpciones(selectA, selectB) {
        const seleccionado = selectA.value;

        for (let i = 0; i < selectB.options.length; i++) {
            const opcion = selectB.options[i];
            opcion.disabled = false;
            if (opcion.value === seleccionado && opcion.value !== "") {
                opcion.disabled = true;
            }
        }
    }

    document.getElementById("perro1").addEventListener("change", function() {
        actualizarOpciones(this, document.getElementById("perro2"));
    });

    document.getElementById("perro2").addEventListener("change", function() {
        actualizarOpciones(this, document.getElementById("perro1"));
    });
</script>