<?php
require('Libraries/pdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, 'Biblioteca Antonio Jose Pacheco', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, 'Reporte de Estudiantes - ' . date('d/m/Y'), 0, 1, 'C');
        $this->Ln(3);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Función para corregir caracteres especiales
    function fixText($text)
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
    }
}

// Conexión a la base de datos
try {
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "biblioteca_db";

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM estudiante");
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Configuración del PDF
$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetLeftMargin(10);
$pdf->SetAutoPageBreak(true, 15);

// Anchuras optimizadas para A4 vertical
$anchuras = [8, 10, 18, 45, 30, 22, 45, 15];
$header = ['N°', 'ID', 'CODIGO', 'NOMBRE', 'CARRERA', 'TELÉFONO', 'DIRECCIÓN', 'EST.'];

// Encabezados de tabla
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 220, 255);
foreach ($header as $key => $col) {
    $pdf->Cell($anchuras[$key], 7, $pdf->fixText($col), 1, 0, 'C', true);
}
$pdf->Ln();

// Datos de estudiantes
$pdf->SetFont('Arial', '', 9);
$contador = 1;

foreach ($estudiantes as $est) {
    // Procesar datos
    $nombre = $pdf->fixText($est['nombre']);
    $carrera = $est['carrera'] ? $pdf->fixText($est['carrera']) : '-';
    $direccion = $pdf->fixText($est['direccion']);
    
    // Estado con color
    $estado = ($est['estado'] == 1) ? 'Activo' : 'Inactivo';
    $color = ($est['estado'] == 1) ? [0, 153, 0] : [204, 0, 0];
    
    // Fila
    $pdf->Cell($anchuras[0], 7, $contador, 1, 0, 'C');
    $pdf->Cell($anchuras[1], 7, $est['id'], 1, 0, 'C');
    $pdf->Cell($anchuras[2], 7, $est['codigo'], 1, 0, 'C');
    $pdf->Cell($anchuras[3], 7, $nombre, 1, 0, 'L');
    $pdf->Cell($anchuras[4], 7, $carrera, 1, 0, 'L');
    $pdf->Cell($anchuras[5], 7, $est['telefono'], 1, 0, 'C');
    $pdf->Cell($anchuras[6], 7, $direccion, 1, 0, 'L');
    
    // Celda de estado
    $pdf->SetFillColor($color[0], $color[1], $color[2]);
    $pdf->Cell($anchuras[7], 7, $pdf->fixText($estado), 1, 1, 'C', true);
    
    // Resetear color
    $pdf->SetFillColor(200, 220, 255);
    $contador++;
}

$pdf->Output('I', 'Reporte_Estudiantes_' . date('Ymd') . '.pdf');
?>