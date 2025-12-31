<?php
/**
 * EJEMPLO 1: PDF SIMPLE CON TCPDF
 * Crea un PDF básico con título, texto y tabla
 */

// Cargar autoload de Composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// 1. CREAR INSTANCIA DE TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// 2. CONFIGURAR INFORMACIÓN DEL PDF
$pdf->SetCreator('Mi Sistema');
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Ejemplo Simple de PDF');
$pdf->SetSubject('Tutorial TCPDF');

// 3. CONFIGURAR MÁRGENES
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 15);

// 4. AGREGAR PRIMERA PÁGINA
$pdf->AddPage();

// 5. ESCRIBIR TÍTULO PRINCIPAL
$pdf->SetFont('helvetica', 'B', 24);
$pdf->SetTextColor(30, 58, 95); // Azul oscuro
$pdf->Cell(0, 15, 'MI PRIMER PDF CON TCPDF', 0, 1, 'C');
$pdf->Ln(5);

// 6. LÍNEA DIVISORIA
$pdf->SetDrawColor(107, 68, 35); // Café
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(8);

// 7. SUBTÍTULO
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(107, 68, 35); // Café
$pdf->Cell(0, 10, 'Ejemplo de Texto y Formatos', 0, 1, 'L');
$pdf->Ln(3);

// 8. TEXTO NORMAL
$pdf->SetFont('helvetica', '', 12);
$pdf->SetTextColor(0, 0, 0); // Negro
$pdf->MultiCell(0, 6,
    'Este es un ejemplo básico de cómo crear un PDF con TCPDF. ' .
    'Puedes cambiar fuentes, tamaños, colores y mucho más. ' .
    'TCPDF es una librería muy poderosa y flexible para generar documentos PDF en PHP.',
    0, 'J');
$pdf->Ln(5);

// 9. LISTA CON VIÑETAS
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Características de TCPDF:', 0, 1);
$pdf->SetFont('helvetica', '', 11);

$caracteristicas = [
    'No requiere librerías externas',
    'Soporta HTML y CSS básico',
    'Puede crear tablas complejas',
    'Soporta múltiples fuentes',
    'Permite agregar imágenes'
];

foreach ($caracteristicas as $i => $caracteristica) {
    $pdf->Cell(10, 6, '•', 0, 0);
    $pdf->Cell(0, 6, $caracteristica, 0, 1);
}
$pdf->Ln(5);

// 10. TABLA SIMPLE
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetTextColor(30, 58, 95);
$pdf->Cell(0, 10, 'Ejemplo de Tabla', 0, 1);
$pdf->Ln(2);

// Crear tabla con HTML
$html = '
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th {
        background-color: #1e3a5f;
        color: white;
        font-weight: bold;
        padding: 8px;
        text-align: left;
    }
    td {
        border: 1px solid #cccccc;
        padding: 6px;
    }
    .total {
        background-color: #f5e6d3;
        font-weight: bold;
    }
</style>

<table border="1" cellpadding="5">
    <tr>
        <th width="10%">#</th>
        <th width="40%">Servicio</th>
        <th width="20%">Duración</th>
        <th width="30%">Precio</th>
    </tr>
    <tr>
        <td align="center">1</td>
        <td>Corte de Cabello</td>
        <td align="center">30 min</td>
        <td align="right">$50.00</td>
    </tr>
    <tr>
        <td align="center">2</td>
        <td>Afeitado Clásico</td>
        <td align="center">20 min</td>
        <td align="right">$30.00</td>
    </tr>
    <tr>
        <td align="center">3</td>
        <td>Tinte de Barba</td>
        <td align="center">45 min</td>
        <td align="right">$75.00</td>
    </tr>
    <tr>
        <td align="center">4</td>
        <td>Masaje Capilar</td>
        <td align="center">15 min</td>
        <td align="right">$25.00</td>
    </tr>
    <tr class="total">
        <td colspan="3" align="right"><b>TOTAL:</b></td>
        <td align="right"><b>$180.00</b></td>
    </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(8);

// 11. CUADRO DE INFORMACIÓN
$pdf->SetFillColor(245, 230, 211); // Beige
$pdf->SetDrawColor(107, 68, 35); // Café
$pdf->SetLineWidth(0.5);
$pdf->Rect(15, $pdf->GetY(), 180, 20, 'DF');

$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 5, 'NOTA IMPORTANTE:', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Este PDF fue generado automáticamente por el Sistema de Gestión de Citas', 0, 1, 'C');
$pdf->Cell(0, 5, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

// 12. AGREGAR SEGUNDA PÁGINA
$pdf->AddPage();

// 13. TÍTULO DE SEGUNDA PÁGINA
$pdf->SetFont('helvetica', 'B', 20);
$pdf->SetTextColor(30, 58, 95);
$pdf->Cell(0, 12, 'Página 2 - Más Ejemplos', 0, 1, 'C');
$pdf->Ln(5);

// 14. TEXTO EN COLUMNAS
$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(0, 0, 0);

$col1 = 'TCPDF permite crear documentos PDF profesionales directamente desde PHP. ' .
        'No necesitas software adicional ni librerías externas complicadas.';

$col2 = 'Puedes personalizar completamente el diseño: fuentes, colores, tamaños, ' .
        'márgenes, encabezados, pies de página y mucho más.';

// Columna 1
$pdf->SetXY(15, $pdf->GetY());
$pdf->MultiCell(85, 5, $col1, 1, 'J');

// Columna 2
$pdf->SetXY(105, $pdf->GetY() - 20);
$pdf->MultiCell(85, 5, $col2, 1, 'J');

$pdf->Ln(10);

// 15. TABLA DE COLORES
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Paleta de Colores del Sistema:', 0, 1);
$pdf->Ln(2);

$colores = [
    ['Azul Oscuro', 30, 58, 95, '#1e3a5f'],
    ['Café', 107, 68, 35, '#6b4423'],
    ['Beige', 245, 230, 211, '#f5e6d3'],
    ['Gris', 73, 80, 87, '#495057']
];

foreach ($colores as $color) {
    // Cuadrado de color
    $pdf->SetFillColor($color[1], $color[2], $color[3]);
    $pdf->Rect(15, $pdf->GetY(), 10, 10, 'F');

    // Nombre del color
    $pdf->SetXY(28, $pdf->GetY() + 2);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(50, 5, $color[0]);

    // RGB
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(60, 5, 'RGB(' . $color[1] . ', ' . $color[2] . ', ' . $color[3] . ')');

    // HEX
    $pdf->Cell(0, 5, $color[4]);

    $pdf->Ln(12);
}

// 16. PIE DE PÁGINA
$pdf->SetY(-30);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, 'Sistema de Gestión de Citas - Barbería y Spa', 0, 1, 'C');
$pdf->Cell(0, 5, 'Desarrollado por: Ilich Esteban Reyes Botia - SENA', 0, 1, 'C');
$pdf->Cell(0, 5, 'Página ' . $pdf->getAliasNumPage() . ' de ' . $pdf->getAliasNbPages(), 0, 0, 'C');

// 17. GENERAR Y DESCARGAR PDF
$pdf->Output('ejemplo_simple.pdf', 'I');
// Modos de salida:
// 'I' = Mostrar en el navegador (Inline)
// 'D' = Descargar automáticamente (Download)
// 'F' = Guardar en servidor (File)
// 'S' = Devolver como string (String)
?>
