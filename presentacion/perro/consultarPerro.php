<?php


if (!isset($_SESSION["rol"])) {
    header("Location: ?pid=" . base64_encode("presentacion/inicio.php"));
    exit();
}
$rol = $_SESSION["rol"];



include("presentacion/fondo.php");
include("presentacion/" . $rol . "/menu" . ucfirst($rol) . ".php");
?>



<body>

    <div class="text-center py-3">
        <div class="container glass py-5">
            <h1 class="display-6">Nuestros Perros</h1>

            <div class="mb-4 text-center">
                <input type="text" id="filtro" class="form-control w-50 mx-auto"
                    placeholder="Buscar perro por nombres, ids...">
            </div>

            <div class="row" id="resultados">

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#filtro").keyup(function() {
                if ($("#filtro").val().length > 2) {
                    var ruta = "buscarPerroAjax.php?filtro=" + $("#filtro").val().replaceAll(" ", "%20");
                    $("#resultados").load(ruta);
                }
            });
        });
    </script>
</body>