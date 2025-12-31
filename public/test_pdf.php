<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// 1. Crear instancia
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// 2. Configurar
$pdf->SetCreator('Mi Nombre');
$pdf->SetTitle('Mi Primer PDF');
$pdf->SetMargins(15, 15, 15);

// 3. Agregar página
$pdf->AddPage();

// 4. Título
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'Mi Primer PDF con TCPDF', 0, 1, 'C');
$pdf->Ln(5);

// 5. Texto normal
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Este es un ejemplo de PDF generado con PHP', 0, 1, 'L');
$pdf->Ln(5);

// 6. Tabla HTML
$html = '
<table border="1" cellpadding="5" style="width:100%;">
    <tr style="background-color:#1e3a5f;color:white;">
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio</th>
    </tr>
    <tr>
        <td>Corte de cabello</td>
        <td align="center">3</td>
        <td align="right">$50.00</td>
    </tr>
    <tr>
        <td>Afeitado</td>
        <td align="center">2</td>
        <td align="right">$30.00</td>
    </tr>
    <tr style="background-color:#f0f0f0;">
        <td colspan="2" align="right"><b>TOTAL:</b></td>
        <td align="right"><b>$80.00</b></td>
    </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

// 7. Generar y descargar
$pdf->Output('mi_reporte.pdf', 'D');
?>