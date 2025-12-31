<?php
/**
 * EJEMPLO 2: PDF CON TABLAS PROFESIONALES
 * Muestra cómo crear tablas como las de los reportes
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Crear PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Configuración
$pdf->SetCreator('Sistema de Citas');
$pdf->SetTitle('Ejemplo de Tablas en PDF');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

// TÍTULO
$pdf->SetFont('helvetica', 'B', 18);
$pdf->SetTextColor(30, 58, 95);
$pdf->Cell(0, 12, 'REPORTE DE EJEMPLO', 0, 1, 'C');
$pdf->Ln(3);

// Período
$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 6, 'Período: 01/01/2025 - 31/01/2025', 0, 1, 'C');
$pdf->Ln(8);

// ============================================
// EJEMPLO 1: TABLA SIMPLE
// ============================================
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetTextColor(107, 68, 35);
$pdf->Cell(0, 8, '1. Tabla Simple (con Cell)', 0, 1);
$pdf->Ln(2);

// Header de la tabla
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(30, 58, 95);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(60, 8, 'Empleado', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Total Citas', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Completadas', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Ingresos', 1, 1, 'C', true);

// Datos
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

$datos = [
    ['Juan Pérez', 25, 23, 1250.00],
    ['María García', 30, 28, 1500.00],
    ['Pedro López', 20, 18, 950.00],
];

foreach ($datos as $i => $fila) {
    $fill = ($i % 2 == 0) ? true : false;
    $pdf->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255);

    $pdf->Cell(60, 7, $fila[0], 1, 0, 'L', true);
    $pdf->Cell(40, 7, $fila[1], 1, 0, 'C', true);
    $pdf->Cell(40, 7, $fila[2], 1, 0, 'C', true);
    $pdf->Cell(40, 7, '$' . number_format($fila[3], 2), 1, 1, 'R', true);
}

// Total
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(245, 230, 211);
$pdf->Cell(100, 7, 'TOTAL:', 1, 0, 'R', true);
$pdf->Cell(40, 7, '69', 1, 0, 'C', true);
$pdf->Cell(40, 7, '$3,700.00', 1, 1, 'R', true);

$pdf->Ln(10);

// ============================================
// EJEMPLO 2: TABLA CON HTML (más fácil)
// ============================================
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetTextColor(107, 68, 35);
$pdf->Cell(0, 8, '2. Tabla con HTML (writeHTML)', 0, 1);
$pdf->Ln(2);

$html = '
<style>
    table {
        border-collapse: collapse;
    }
    th {
        background-color: #1e3a5f;
        color: white;
        font-weight: bold;
        padding: 6px;
        text-align: center;
        border: 1px solid #1e3a5f;
    }
    td {
        border: 1px solid #cccccc;
        padding: 5px;
    }
    .zebra-1 {
        background-color: #ffffff;
    }
    .zebra-2 {
        background-color: #f5f5f5;
    }
    .total {
        background-color: #f5e6d3;
        font-weight: bold;
    }
</style>

<table border="1" cellpadding="5" style="width: 100%;">
    <thead>
        <tr>
            <th width="15%">Código</th>
            <th width="35%">Servicio</th>
            <th width="15%">Duración</th>
            <th width="15%">Cantidad</th>
            <th width="20%">Precio</th>
        </tr>
    </thead>
    <tbody>
        <tr class="zebra-1">
            <td align="center">S001</td>
            <td>Corte de Cabello Clásico</td>
            <td align="center">30 min</td>
            <td align="center">15</td>
            <td align="right">$50.00</td>
        </tr>
        <tr class="zebra-2">
            <td align="center">S002</td>
            <td>Afeitado con Navaja</td>
            <td align="center">20 min</td>
            <td align="center">10</td>
            <td align="right">$30.00</td>
        </tr>
        <tr class="zebra-1">
            <td align="center">S003</td>
            <td>Arreglo de Barba</td>
            <td align="center">15 min</td>
            <td align="center">20</td>
            <td align="right">$25.00</td>
        </tr>
        <tr class="zebra-2">
            <td align="center">S004</td>
            <td>Tinte de Barba</td>
            <td align="center">45 min</td>
            <td align="center">5</td>
            <td align="right">$75.00</td>
        </tr>
        <tr class="total">
            <td colspan="4" align="right">INGRESOS TOTALES:</td>
            <td align="right">$1,625.00</td>
        </tr>
    </tbody>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(10);

// ============================================
// EJEMPLO 3: TABLA CON DATOS DINÁMICOS
// ============================================
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetTextColor(107, 68, 35);
$pdf->Cell(0, 8, '3. Tabla con Datos Dinámicos (foreach)', 0, 1);
$pdf->Ln(2);

// Simular datos de base de datos
$citas = [
    ['2025-01-15', '10:00', 'Juan Pérez', 'Carlos Gómez', 'Corte', 'Completada', 50],
    ['2025-01-15', '11:00', 'Ana López', 'Carlos Gómez', 'Tinte', 'Completada', 75],
    ['2025-01-15', '12:00', 'Luis Martín', 'María Ruiz', 'Afeitado', 'Pendiente', 30],
    ['2025-01-15', '14:00', 'Sofia Cruz', 'María Ruiz', 'Corte', 'Confirmada', 50],
];

// Construir HTML dinámicamente
$html_dinamico = '
<table border="1" cellpadding="4" style="width: 100%;">
    <tr style="background-color: #1e3a5f; color: white; font-weight: bold;">
        <th width="12%">Fecha</th>
        <th width="10%">Hora</th>
        <th width="22%">Cliente</th>
        <th width="22%">Empleado</th>
        <th width="15%">Servicio</th>
        <th width="12%">Estado</th>
        <th width="7%">$</th>
    </tr>
';

$total = 0;
foreach ($citas as $cita) {
    // Color según estado
    $colorEstado = '';
    switch ($cita[5]) {
        case 'Completada':
            $colorEstado = 'background-color: #d4edda; color: #155724;';
            break;
        case 'Pendiente':
            $colorEstado = 'background-color: #fff3cd; color: #856404;';
            break;
        case 'Confirmada':
            $colorEstado = 'background-color: #d1ecf1; color: #0c5460;';
            break;
    }

    $html_dinamico .= '<tr>
        <td align="center">' . date('d/m/Y', strtotime($cita[0])) . '</td>
        <td align="center">' . $cita[1] . '</td>
        <td>' . $cita[2] . '</td>
        <td>' . $cita[3] . '</td>
        <td>' . $cita[4] . '</td>
        <td align="center" style="' . $colorEstado . '"><b>' . $cita[5] . '</b></td>
        <td align="right">$' . $cita[6] . '</td>
    </tr>';

    $total += $cita[6];
}

$html_dinamico .= '
    <tr style="background-color: #6b4423; color: white; font-weight: bold;">
        <td colspan="6" align="right">TOTAL:</td>
        <td align="right">$' . $total . '</td>
    </tr>
</table>
';

$pdf->writeHTML($html_dinamico, true, false, true, false, '');
$pdf->Ln(10);

// ============================================
// RESUMEN DE ESTADÍSTICAS
// ============================================
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(30, 58, 95);
$pdf->Cell(0, 10, 'ESTADÍSTICAS DEL PERÍODO', 0, 1, 'C');
$pdf->Ln(5);

// KPIs en recuadros
$kpis = [
    ['Total de Citas', '125', '#1e3a5f'],
    ['Completadas', '98', '#198754'],
    ['Canceladas', '15', '#dc3545'],
    ['Ingresos', '$6,250', '#6b4423'],
];

$x = 15;
foreach ($kpis as $kpi) {
    // Convertir hex a RGB
    list($r, $g, $b) = sscanf($kpi[2], "#%02x%02x%02x");

    // Recuadro
    $pdf->SetFillColor($r, $g, $b);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Rect($x, $pdf->GetY(), 42, 25, 'F');

    // Título
    $pdf->SetXY($x, $pdf->GetY() + 4);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(42, 5, $kpi[0], 0, 0, 'C');

    // Valor
    $pdf->SetXY($x, $pdf->GetY() + 10);
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->Cell(42, 5, $kpi[1], 0, 0, 'C');

    $x += 47;
}

$pdf->Ln(30);

// Información adicional
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, 'Este es un ejemplo de cómo crear reportes profesionales con TCPDF.', 0, 1, 'C');
$pdf->Cell(0, 5, 'Puedes combinar tablas, gráficos, textos y más.', 0, 1, 'C');

// Footer
$pdf->SetY(-20);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, 'Generado el ' . date('d/m/Y H:i:s'), 0, 1, 'C');

// GENERAR PDF
$pdf->Output('ejemplo_tablas.pdf', 'I');
?>
