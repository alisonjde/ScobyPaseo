<?php
require_once("phpqrcode/qrlib.php");
require("fpdf/fpdf.php");
require_once("logica/Paseo.php");
require_once("logica/Paseador.php");
require_once("logica/Perro.php");

session_start();
if (!isset($_SESSION["id"]) || $_SESSION["rol"] !== "dueño") {
    header("Location: index.php");
    exit;
}

$idPaseo = $_GET["id"] ?? null;
$horas = $_GET["horas"] ?? null;

if (!$idPaseo || !$horas) {
    die("Faltan parámetros.");
}

$url = "http://localhost/ScoobyPaseo/ScobyPaseo/descargarPaseo.php?id=$idPaseo&horas=$horas";
$qrPath = "imagenes/QR/temp_qr.png";
QRcode::png($url, $qrPath, QR_ECLEVEL_L, 4);

$paseo = new Paseo($idPaseo);
$paseo->consultar();
$paseador = $paseo->getPaseador();

if (empty($paseador->getNombre()) || empty($paseador->getApellido())) {
    die("No se encontró el paseador asociado a este paseo.");
}

$perrosDelDueño = $paseo->obtenerPerrosPorDueño($_SESSION["id"]);

$pdf = new FPDF();
$pdf->AddPage();

// Colores personalizados
$colorPrimario = [46, 204, 113]; // Verde principal
$colorSecundario = [39, 174, 96]; // Verde más oscuro
$colorTexto = [44, 62, 80]; // Gris oscuro
$colorFondo = [236, 240, 241]; // Gris claro

// Header con logo y título
$pdf->SetFillColor($colorPrimario[0], $colorPrimario[1], $colorPrimario[2]);
$pdf->Rect(0, 0, 210, 40, 'F');

// Logo
$pdf->Image('imagenes/logo.png', 15, 8, 25);

// Título principal
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(50, 15);
$pdf->Cell(0, 10, utf8_decode("Confirmación de Paseo"), 0, 1);

// Subtítulo
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(50, 25);
$pdf->Cell(0, 10, utf8_decode("Scooby Paseo - Tu compañero de confianza"), 0, 1);

// QR Code en la esquina superior derecha
$pdf->Image($qrPath, 165, 5, 30, 30);

// Espacio después del header
$pdf->Ln(25);

// Información del paseo en caja con fondo
$pdf->SetY(50);
$pdf->SetFillColor($colorFondo[0], $colorFondo[1], $colorFondo[2]);
$pdf->SetDrawColor($colorPrimario[0], $colorPrimario[1], $colorPrimario[2]);
$pdf->SetLineWidth(0.8);
$pdf->Rect(15, 48, 180, 42, 'DF');

$pdf->SetTextColor($colorTexto[0], $colorTexto[1], $colorTexto[2]);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetXY(20, 53);
$pdf->Cell(0, 6, utf8_decode("Detalles del Paseo"), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->SetX(25);
$pdf->Cell(50, 6, utf8_decode("Fecha:"), 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode($paseo->getFecha()), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->SetX(25);
$pdf->Cell(50, 6, utf8_decode("Hora:"), 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode($paseo->getHora()), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->SetX(25);
$pdf->Cell(50, 6, utf8_decode("Duración:"), 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode("$horas hora(s)"), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->SetX(25);
$pdf->Cell(50, 6, utf8_decode("Dirección:"), 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode($paseo->getDireccion()), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->SetX(25);
$pdf->Cell(50, 6, utf8_decode("Tarifa Total:"), 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor($colorSecundario[0], $colorSecundario[1], $colorSecundario[2]);
$pdf->Cell(0, 6, utf8_decode("$" . number_format($paseo->getTarifa(), 0, ',', '.')), 0, 1);

// Información del paseador
$pdf->SetY(95);
$pdf->SetFillColor($colorFondo[0], $colorFondo[1], $colorFondo[2]);
$pdf->Rect(15, 93, 180, 20, 'DF');

$pdf->SetTextColor($colorTexto[0], $colorTexto[1], $colorTexto[2]);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetXY(20, 98);
$pdf->Cell(0, 6, utf8_decode("Tu Paseador"), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetX(25);
$pdf->Cell(0, 6, utf8_decode($paseador->getNombre() . " " . $paseador->getApellido()), 0, 1);

// Lista de perros
$pdf->SetY(118);
$alturaPerros = 15 + (count($perrosDelDueño) * 6);
$pdf->SetFillColor($colorFondo[0], $colorFondo[1], $colorFondo[2]);
$pdf->Rect(15, 116, 180, $alturaPerros, 'DF');

$pdf->SetTextColor($colorTexto[0], $colorTexto[1], $colorTexto[2]);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetXY(20, 121);
$pdf->Cell(0, 6, utf8_decode("Perros en el Paseo"), 0, 1);

$pdf->SetFont('Arial', '', 10);
if (empty($perrosDelDueño)) {
    $pdf->SetX(25);
    $pdf->SetTextColor(231, 76, 60); // Rojo para advertencia
    $pdf->Cell(0, 6, utf8_decode("No se encontraron perros asociados a tu cuenta en este paseo."), 0, 1);
} else {
    foreach ($perrosDelDueño as $perro) {
        $pdf->SetX(25);
        $pdf->SetTextColor($colorTexto[0], $colorTexto[1], $colorTexto[2]);
        $pdf->Cell(0, 6, utf8_decode($perro->getNombre()), 0, 1);
    }
}

// Footer con información adicional
$pdf->SetY(245); // Posición absoluta en lugar de negativa
$pdf->SetFillColor($colorPrimario[0], $colorPrimario[1], $colorPrimario[2]);
$pdf->Rect(0, 245, 210, 25, 'F');

$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetY(248);
$pdf->Cell(0, 6, utf8_decode("¡Gracias por confiar en Scooby Paseo!"), 0, 1, 'C');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 5, utf8_decode("Escanea el código QR para acceder a más detalles del paseo"), 0, 1, 'C');

$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 5, utf8_decode("Documento generado el " . date('d/m/Y H:i')), 0, 1, 'C');

$pdf->Output("D", "confirmacion_paseo_" . date('Y-m-d') . ".pdf");
unlink($qrPath);
exit;
?>