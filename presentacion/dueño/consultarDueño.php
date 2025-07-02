<?php

include("presentacion/fondo.php");
include("presentacion/admin/menuAdmin.php");



if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}


?>

<body>


    <div class="text-center py-3">
        <div class="container glass py-3">
            <h1 class="display-6">Listado de Dueños</h1>

            <div class="mb-4 text-center">
                <input type="text" id="filtro" class="form-control w-50 mx-auto"
                    placeholder="Buscar dueño por nombre, correo o teléfono...">
            </div>

            <div class="table-responsive" id="resultados">

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#filtro").keyup(function() {
                if ($("#filtro").val().length > 2) {
                    var ruta = "buscarDueñoAjax.php?filtro=" + $("#filtro").val().replaceAll(" ", "%20");
                    $("#resultados").load(ruta);
                }
            });
        });
    </script>
</body>