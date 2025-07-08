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
QRcode::png($url, $qrPath, QR_ECLEVEL_L, 3);

$paseo = new Paseo($idPaseo);
$paseo->consultar();
$paseador = $paseo->getPaseador();

if (empty($paseador->getNombre()) || empty($paseador->getApellido())) {
    die("No se encontró el paseador asociado a este paseo.");
}

$perrosDelDueño = $paseo->obtenerPerrosPorDueño($_SESSION["id"]);

$pdf = new FPDF();
$pdf->AddPage();

$pdf->Image('imagenes/logo.png', 80, 10, 50);
$pdf->Ln(55);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, utf8_decode("Confirmación de Paseo"), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetDrawColor(200, 200, 200);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode("Fecha: " . $paseo->getFecha()), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Hora: " . $paseo->getHora()), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Duración: $horas hora(s)"), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Dirección: " . $paseo->getDireccion()), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Tarifa Total: $" . number_format($paseo->getTarifa(), 0, ',', '.')), 0, 1);
$pdf->Ln(5);

$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("Paseador:"), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode($paseador->getNombre() . " " . $paseador->getApellido()), 0, 1);
$pdf->Ln(5);

$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("Perros en el paseo:"), 0, 1);
$pdf->SetFont('Arial', '', 12);

if (empty($perrosDelDueño)) {
    $pdf->Cell(0, 10, utf8_decode("⚠ No se encontraron perros asociados a tu cuenta en este paseo."), 0, 1);
} else {
    foreach ($perrosDelDueño as $perro) {
        $pdf->Cell(0, 10, utf8_decode($perro->getNombre()), 0, 1);
    }
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, utf8_decode("Gracias por usar Scooby Paseo"), 0, 1, 'C');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Image($qrPath, ($pdf->GetPageWidth() - 50) / 2, null, 50, 50); 


$pdf->Output("D", "confirmacion_paseo.pdf");
unlink($qrPath);
exit;
