<?php
require_once("logica/Paseador.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['modificarPaseador'])) {
    
    $id = $_POST['id'] ?? "";
    $nombre = $_POST['nombre'] ?? "";
    $apellido = $_POST['apellido'] ?? "";
    $telefono = $_POST['telefono'] ?? "";
    $descripcion = $_POST['descripcion'] ?? "";

    $paseador = new Paseador($id);
    if (!$paseador->buscar()) {
        header("Location: ?pid=" . base64_encode("presentacion/paseador/actualizarPaseador.php") . "&error=notfound");
        exit();
    }

    $fotoActual = $paseador->getFoto();
    $nuevaFoto = $fotoActual;

    if (!empty($_FILES["foto"]["name"])) {
        $rutaLocal = $_FILES["foto"]["tmp_name"];
        $nuevoNombre = time() . "_" . str_replace(' ', '_', basename($_FILES["foto"]["name"]));

        $rutaServidor = "imagenes/Paseadores/" . $nuevoNombre;

        $infoImagen = getimagesize($_FILES['foto']['tmp_name']);
            if($infoImagen === false) {
                header("Location: ?pid=" . base64_encode("presentacion/paseador/actualizarPaseador.php") . "&error=ImagenNoValida");
                exit();
            }


        if (move_uploaded_file($rutaLocal, $rutaServidor)) {
            if (!empty($fotoActual) && file_exists($fotoActual)) {
                unlink($fotoActual);
            }
            $nuevaFoto = $rutaServidor;
        }
    }

    $paseadorActualizado = new Paseador(
        $id,
        $nombre,
        $apellido,
        $nuevaFoto,
        "",             // correo no se actualiza
        $telefono,
        "",             // clave no se actualiza
        $descripcion,
        "",             // disponibilidad no se actualiza
        ""              // estadoPaseador no se actualiza
    );

    if ($paseadorActualizado->actualizar()) {
        header("Location: ?pid=" . base64_encode("presentacion/paseador/actualizarPaseador.php") . "&actualizado");
    } else {
        header("Location: ?pid=" . base64_encode("presentacion/paseador/actualizarPaseador.php") . "&error=update");
    }
}
?>
