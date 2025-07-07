<body>
    <?php
    if (!isset($_SESSION["rol"])) {
    header("Location: ?pid=" . base64_encode("presentacion/inicio.php"));
    exit();
}
    $id = $_SESSION["id"];
    $rol = $_SESSION["rol"];
    include("presentacion/fondo.php");
    include("presentacion/" . $rol . "/menu" . ucfirst($rol) . ".php");
    ?>

    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Listado de Paseos</h1>
            <div class="mb-4 text-center">
                <input type="text" id="filtro" class="form-control w-50 mx-auto"
                    placeholder="Buscar paseo por fecha(DD_MM_AAAA), hora, nombre, apellido...">
            </div>
            <div id="resultados" class="table-responsive">
                
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#filtro").keyup(function() {
                if ($("#filtro").val().length > 2) {
                    var ruta = "buscarPaseoAjax.php?filtro=" + $("#filtro").val().replaceAll(" ", "%20") + "&id=<?php echo $id; ?>&rol=<?php echo $rol;?>";
                    $("#resultados").load(ruta);
                }
            });
        });
    </script>
</body>
