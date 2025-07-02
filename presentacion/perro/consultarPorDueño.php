<?php
if ($_SESSION["rol"] != "dueño") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>
<style>
.glass {
    background: rgba(50, 30, 80, 0.85);
    border-radius: 1rem;
    box-shadow: 0 8px 24px rgba(125, 91, 172, 0.3);
    backdrop-filter: blur(10px);
    color: #f0e6ff;
}

.table-custom {
    background-color: #2A1A40;
    border-collapse: collapse;
    color: #f5f0ff;
}

.table-custom th {
    background-color: #6A0DAD; 
    color: #ffffff;
    border-bottom: 2px solid #B388EB;
    text-align: center;
}

.table-custom td {
    background-color: #3D2B56; 
    color: #f5f0ff;
    border-top: 1px solid #6A0DAD;
    vertical-align: middle;
}

.table-custom tr:hover {
    background-color: #5C4B89; 
    transition: background-color 0.3s ease;
}

.tarifa-badge {
    background-color: #4CAF50;
    border-radius: 12px;
    padding: 3px 10px;
    font-size: 0.85em;
}

.btn-action {
    margin: 0 3px;
    transition: all 0.2s ease;
}

.btn-action:hover {
    transform: scale(1.1);
}
</style>

<body>
    <?php
    include("presentacion/fondo.php");
    include("presentacion/dueño/menuDueño.php");
    ?>

    <div class="text-center py-3 hero-text">
        <div class="container glass py-3">
            <h1 class="display-6">Listado de mis Perritos</h1>

            <div class="table-responsive">
                <table class="table table-custom table-hover text-light">
                    <thead>
                        <tr>
                            <th>Mascota</th>
                            <th>Tamaño</th>
                            <th>Foto</th>
                            <th>Acciones</th>             
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $perro = new Perro("","","","",$id);
                        $perros = $perro -> consultarPorDueño();
                        foreach($perros as $perro) {
                        ?>
                        <tr>
                            <td><?php echo $perro -> getNombre() ?></td>
                            <td><?php echo $perro -> getIdTamaño() ?></td>
                            <td><?php echo $perro -> getFoto() ?></td>
                             <td>
                                <a href="?pid=<?php echo base64_encode("presentacion/perro/editarPerro.php") ?>&idPerro=<?php echo $perro->getIdPerro() ?>" 
                                   class="btn btn-sm btn-primary" 
                                   title="Editar perro">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger btn-eliminar" 
                                        data-id="<?php echo $perro->getIdPerro()?>"
                                        data-nombre="<?php echo htmlspecialchars($perro->getNombre()) ?>"
                                        title="Eliminar perro">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmarEliminacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al perro <strong id="nombrePerro"></strong>?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formEliminar" method="post" action="?pid=<?php echo base64_encode("presentacion/perro/eliminarPerro.php") ?>">
                        <input type="hidden" name="idPerro" id="idPerro">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const botonesEliminar = document.querySelectorAll('.btn-eliminar');
        const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacion'));
        const nombrePerro = document.getElementById('nombrePerro');
        const id = document.getElementById('idPerro');
        const formEliminar = document.getElementById('formEliminar');
        
        botonesEliminar.forEach(boton => {
            boton.addEventListener('click', function() {
                nombrePerro.textContent = this.getAttribute('data-nombre');
                id.value = this.getAttribute('data-id');
                modal.show();
            });
        });

        formEliminar.addEventListener('submit', function(e) {
        });
    });
    </script>
</body>