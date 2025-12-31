<?php

/**
 * CONTROLADOR DE REPORTES - MÓDULO ADMINISTRADOR
 *
 * Propósito:
 * Generar reportes y estadísticas del sistema de gestión de citas.
 * Incluye dashboard administrativo, reportes por fecha, empleado y servicio.
 * Funcionalidades de exportación a PDF y Excel.
 *
 * Funcionalidades:
 * - Dashboard con KPIs y estadísticas generales
 * - Reporte detallado por rango de fechas
 * - Reporte de productividad por empleado
 * - Reporte de servicios más solicitados
 * - Listado de citas realizadas y pendientes
 * - Exportación a PDF y Excel
 *
 * @author Sistema de Gestión de Citas - Barbería
 * @version 1.0 - Semana 8
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CitasModel;
use App\Models\ClienteModel;
use App\Models\EmpleadoModel;
use App\Models\ServicioModel;

class Reportes extends BaseController
{
    protected $citasModel;
    protected $clienteModel;
    protected $empleadoModel;
    protected $servicioModel;
    protected $session;

    /**
     * Constructor - Inicializa modelos y servicios necesarios
     */
    public function __construct()
    {
        $this->citasModel = new CitasModel();
        $this->clienteModel = new ClienteModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->servicioModel = new ServicioModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Dashboard principal de reportes
     * Muestra estadísticas generales y KPIs
     */
    public function index()
    {
        // Obtener rango de fechas (por defecto: mes actual)
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        // Obtener estadísticas avanzadas
        $estadisticas = $this->citasModel->obtenerEstadisticasAvanzadas($fechaInicio, $fechaFin);

        // Obtener datos para gráficas
        $citasPorDia = $this->citasModel->obtenerCitasPorDia($fechaInicio, $fechaFin);
        $reporteEmpleados = $this->citasModel->obtenerReportePorEmpleado($fechaInicio, $fechaFin);
        $reporteServicios = $this->citasModel->obtenerReportePorServicio($fechaInicio, $fechaFin);

        $data = [
            'titulo' => 'Panel de Control - Reportes y Estadísticas',
            'estadisticas' => $estadisticas,
            'citas_por_dia' => $citasPorDia,
            'reporte_empleados' => $reporteEmpleados,
            'reporte_servicios' => $reporteServicios,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        return view('admin/reportes/index', $data);
    }

    /**
     * Reporte detallado por rango de fechas
     */
    public function porFecha()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        $citas = $this->citasModel->obtenerReportePorFecha($fechaInicio, $fechaFin);
        $estadisticas = $this->citasModel->obtenerEstadisticasAvanzadas($fechaInicio, $fechaFin);

        $data = [
            'titulo' => 'Reporte por Fecha',
            'citas' => $citas,
            'estadisticas' => $estadisticas,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        return view('admin/reportes/por_fecha', $data);
    }

    /**
     * Reporte de productividad por empleado
     */
    public function porEmpleado()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        $reporte = $this->citasModel->obtenerReportePorEmpleado($fechaInicio, $fechaFin);

        $data = [
            'titulo' => 'Reporte por Empleado',
            'reporte' => $reporte,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        return view('admin/reportes/por_empleado', $data);
    }

    /**
     * Reporte de servicios más solicitados
     */
    public function porServicio()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        $reporte = $this->citasModel->obtenerReportePorServicio($fechaInicio, $fechaFin);

        $data = [
            'titulo' => 'Reporte por Servicio',
            'reporte' => $reporte,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        return view('admin/reportes/por_servicio', $data);
    }

    /**
     * Listado de citas realizadas (completadas)
     */
    public function citasRealizadas()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        $citas = $this->citasModel->obtenerCitasRealizadas($fechaInicio, $fechaFin);
        $ingresos = $this->citasModel->obtenerIngresosReales($fechaInicio, $fechaFin);

        $data = [
            'titulo' => 'Citas Realizadas',
            'citas' => $citas,
            'ingresos_totales' => $ingresos,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        return view('admin/reportes/citas_realizadas', $data);
    }

    /**
     * Listado de citas pendientes
     */
    public function citasPendientes()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? null;
        $fechaFin = $this->request->getGet('fecha_fin') ?? null;

        $citas = $this->citasModel->obtenerCitasPendientes($fechaInicio, $fechaFin);

        $data = [
            'titulo' => 'Citas Pendientes',
            'citas' => $citas,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        return view('admin/reportes/citas_pendientes', $data);
    }

    /**
     * Exportar reporte a PDF
     */
    public function exportarPDF()
    {
        $tipo = $this->request->getGet('tipo') ?? 'general';
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        // Verificar que TCPDF esté instalado
        if (!class_exists('TCPDF')) {
            return redirect()->back()->with('error', 'La librería TCPDF no está instalada. Por favor, instálala con: composer require tecnickcom/tcpdf');
        }

        // Crear instancia de TCPDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Configurar PDF
        $pdf->SetCreator('Sistema de Gestión de Citas');
        $pdf->SetAuthor('Barbería y Spa');
        $pdf->SetTitle('Reporte de Citas');
        $pdf->SetSubject('Reporte');

        // Configurar márgenes
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Agregar página
        $pdf->AddPage();

        // Título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'REPORTE DE CITAS-ILICH REYES', 0, 1, 'C');
        $pdf->Ln(5);

        // Período
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Período: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)), 0, 1, 'C');
        $pdf->Ln(10);

        // Contenido según tipo de reporte
        switch ($tipo) {
            case 'empleado':
                $this->generarPDFEmpleado($pdf, $fechaInicio, $fechaFin);
                break;
            case 'servicio':
                $this->generarPDFServicio($pdf, $fechaInicio, $fechaFin);
                break;
            case 'realizadas':
                $this->generarPDFRealizadas($pdf, $fechaInicio, $fechaFin);
                break;
            default:
                $this->generarPDFGeneral($pdf, $fechaInicio, $fechaFin);
                break;
        }

        // Salida del PDF
        $nombreArchivo = 'reporte_' . $tipo . '_' . date('Y-m-d') . '.pdf';
        $pdf->Output($nombreArchivo, 'D');
    }

    /**
     * Exportar reporte a Excel
     */
    public function exportarExcel()
    {
        $tipo = $this->request->getGet('tipo') ?? 'general';
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        // Verificar que PhpSpreadsheet esté instalado
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return redirect()->back()->with('error', 'La librería PhpSpreadsheet no está instalada. Por favor, instálala con: composer require phpoffice/phpspreadsheet');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Título
        $sheet->setCellValue('A1', 'REPORTE DE CITAS-Ilich Reyes');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Período
        $sheet->setCellValue('A2', 'Período: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)));
        $sheet->mergeCells('A2:F2');

        // Contenido según tipo de reporte
        switch ($tipo) {
            case 'empleado':
                $this->generarExcelEmpleado($sheet, $fechaInicio, $fechaFin);
                break;
            case 'servicio':
                $this->generarExcelServicio($sheet, $fechaInicio, $fechaFin);
                break;
            case 'realizadas':
                $this->generarExcelRealizadas($sheet, $fechaInicio, $fechaFin);
                break;
            default:
                $this->generarExcelGeneral($sheet, $fechaInicio, $fechaFin);
                break;
        }

        // Auto-ajustar columnas
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Salida del archivo
        $nombreArchivo = 'reporte_' . $tipo . '_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * MÉTODOS PRIVADOS PARA GENERACIÓN DE PDFs
     */

    private function generarPDFGeneral($pdf, $fechaInicio, $fechaFin)
    {
        $estadisticas = $this->citasModel->obtenerEstadisticasAvanzadas($fechaInicio, $fechaFin);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Estadísticas Generales', 0, 1);
        $pdf->SetFont('helvetica', '', 10);

        $html = '<table border="1" cellpadding="5">
            <tr><td width="60%"><b>Total de Citas:</b></td><td width="40%">' . $estadisticas['total_citas'] . '</td></tr>
            <tr><td><b>Citas Completadas:</b></td><td>' . $estadisticas['citas_completadas'] . '</td></tr>
            <tr><td><b>Citas Canceladas:</b></td><td>' . $estadisticas['citas_canceladas'] . '</td></tr>
            <tr><td><b>Tasa de Completación:</b></td><td>' . $estadisticas['tasa_completacion'] . '%</td></tr>
            <tr><td><b>Tasa de Cancelación:</b></td><td>' . $estadisticas['tasa_cancelacion'] . '%</td></tr>
            <tr><td><b>Ingresos Totales:</b></td><td>$' . number_format($estadisticas['ingresos_totales'], 2) . '</td></tr>
            <tr><td><b>Promedio por Cita:</b></td><td>$' . number_format($estadisticas['promedio_ingreso'], 2) . '</td></tr>
        </table>';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    private function generarPDFEmpleado($pdf, $fechaInicio, $fechaFin)
    {
        $reporte = $this->citasModel->obtenerReportePorEmpleado($fechaInicio, $fechaFin);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Reporte por Empleado', 0, 1);
        $pdf->SetFont('helvetica', '', 9);

        $html = '<table border="1" cellpadding="4">
            <tr style="background-color:#1e3a5f;color:white;">
                <th width="30%">Empleado</th>
                <th width="15%">Total Citas</th>
                <th width="15%">Completadas</th>
                <th width="15%">Canceladas</th>
                <th width="25%">Ingresos</th>
            </tr>';

        foreach ($reporte as $emp) {
            $html .= '<tr>
                <td>' . $emp['nombre'] . ' ' . $emp['apellido'] . '</td>
                <td align="center">' . $emp['total_citas'] . '</td>
                <td align="center">' . $emp['citas_completadas'] . '</td>
                <td align="center">' . $emp['citas_canceladas'] . '</td>
                <td align="right">$' . number_format($emp['ingresos_generados'], 2) . '</td>
            </tr>';
        }

        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    }

    private function generarPDFServicio($pdf, $fechaInicio, $fechaFin)
    {
        $reporte = $this->citasModel->obtenerReportePorServicio($fechaInicio, $fechaFin);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Reporte por Servicio', 0, 1);
        $pdf->SetFont('helvetica', '', 9);

        $html = '<table border="1" cellpadding="4">
            <tr style="background-color:#1e3a5f;color:white;">
                <th width="35%">Servicio</th>
                <th width="15%">Total Citas</th>
                <th width="15%">Completadas</th>
                <th width="15%">Precio</th>
                <th width="20%">Ingresos</th>
            </tr>';

        foreach ($reporte as $srv) {
            $html .= '<tr>
                <td>' . $srv['nombre'] . '</td>
                <td align="center">' . $srv['total_citas'] . '</td>
                <td align="center">' . $srv['citas_completadas'] . '</td>
                <td align="right">$' . number_format($srv['precio'], 2) . '</td>
                <td align="right">$' . number_format($srv['ingresos_generados'], 2) . '</td>
            </tr>';
        }

        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    }

    private function generarPDFRealizadas($pdf, $fechaInicio, $fechaFin)
    {
        $citas = $this->citasModel->obtenerCitasRealizadas($fechaInicio, $fechaFin);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Citas Realizadas', 0, 1);
        $pdf->SetFont('helvetica', '', 8);

        $html = '<table border="1" cellpadding="3">
            <tr style="background-color:#1e3a5f;color:white;">
                <th width="15%">Fecha</th>
                <th width="25%">Cliente</th>
                <th width="25%">Empleado</th>
                <th width="20%">Servicio</th>
                <th width="15%">Precio</th>
            </tr>';

        foreach ($citas as $cita) {
            $html .= '<tr>
                <td>' . date('d/m/Y', strtotime($cita['fecha_cita'])) . '</td>
                <td>' . $cita['nombre_cliente'] . ' ' . $cita['apellido_cliente'] . '</td>
                <td>' . $cita['nombre_empleado'] . ' ' . $cita['apellido_empleado'] . '</td>
                <td>' . $cita['nombre_servicio'] . '</td>
                <td align="right">$' . number_format($cita['precio'], 2) . '</td>
            </tr>';
        }

        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * MÉTODOS PRIVADOS PARA GENERACIÓN DE EXCEL
     */

    private function generarExcelGeneral($sheet, $fechaInicio, $fechaFin)
    {
        $estadisticas = $this->citasModel->obtenerEstadisticasAvanzadas($fechaInicio, $fechaFin);

        $row = 4;
        $sheet->setCellValue('A' . $row, 'Estadísticas Generales');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row += 2;

        $datos = [
            ['Total de Citas:', $estadisticas['total_citas']],
            ['Citas Completadas:', $estadisticas['citas_completadas']],
            ['Citas Canceladas:', $estadisticas['citas_canceladas']],
            ['Tasa de Completación:', $estadisticas['tasa_completacion'] . '%'],
            ['Tasa de Cancelación:', $estadisticas['tasa_cancelacion'] . '%'],
            ['Ingresos Totales:', '$' . number_format($estadisticas['ingresos_totales'], 2)],
            ['Promedio por Cita:', '$' . number_format($estadisticas['promedio_ingreso'], 2)]
        ];

        foreach ($datos as $dato) {
            $sheet->setCellValue('A' . $row, $dato[0]);
            $sheet->setCellValue('B' . $row, $dato[1]);
            $row++;
        }
    }

    private function generarExcelEmpleado($sheet, $fechaInicio, $fechaFin)
    {
        $reporte = $this->citasModel->obtenerReportePorEmpleado($fechaInicio, $fechaFin);

        $row = 4;
        $sheet->setCellValue('A' . $row, 'Empleado');
        $sheet->setCellValue('B' . $row, 'Total Citas');
        $sheet->setCellValue('C' . $row, 'Completadas');
        $sheet->setCellValue('D' . $row, 'Canceladas');
        $sheet->setCellValue('E' . $row, 'Pendientes');
        $sheet->setCellValue('F' . $row, 'Ingresos');
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        $row++;

        foreach ($reporte as $emp) {
            $sheet->setCellValue('A' . $row, $emp['nombre'] . ' ' . $emp['apellido']);
            $sheet->setCellValue('B' . $row, $emp['total_citas']);
            $sheet->setCellValue('C' . $row, $emp['citas_completadas']);
            $sheet->setCellValue('D' . $row, $emp['citas_canceladas']);
            $sheet->setCellValue('E' . $row, $emp['citas_pendientes']);
            $sheet->setCellValue('F' . $row, '$' . number_format($emp['ingresos_generados'], 2));
            $row++;
        }
    }

    private function generarExcelServicio($sheet, $fechaInicio, $fechaFin)
    {
        $reporte = $this->citasModel->obtenerReportePorServicio($fechaInicio, $fechaFin);

        $row = 4;
        $sheet->setCellValue('A' . $row, 'Servicio');
        $sheet->setCellValue('B' . $row, 'Total Citas');
        $sheet->setCellValue('C' . $row, 'Completadas');
        $sheet->setCellValue('D' . $row, 'Canceladas');
        $sheet->setCellValue('E' . $row, 'Precio');
        $sheet->setCellValue('F' . $row, 'Ingresos');
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        $row++;

        foreach ($reporte as $srv) {
            $sheet->setCellValue('A' . $row, $srv['nombre']);
            $sheet->setCellValue('B' . $row, $srv['total_citas']);
            $sheet->setCellValue('C' . $row, $srv['citas_completadas']);
            $sheet->setCellValue('D' . $row, $srv['citas_canceladas']);
            $sheet->setCellValue('E' . $row, '$' . number_format($srv['precio'], 2));
            $sheet->setCellValue('F' . $row, '$' . number_format($srv['ingresos_generados'], 2));
            $row++;
        }
    }

    private function generarExcelRealizadas($sheet, $fechaInicio, $fechaFin)
    {
        $citas = $this->citasModel->obtenerCitasRealizadas($fechaInicio, $fechaFin);

        $row = 4;
        $sheet->setCellValue('A' . $row, 'Fecha');
        $sheet->setCellValue('B' . $row, 'Hora');
        $sheet->setCellValue('C' . $row, 'Cliente');
        $sheet->setCellValue('D' . $row, 'Empleado');
        $sheet->setCellValue('E' . $row, 'Servicio');
        $sheet->setCellValue('F' . $row, 'Precio');
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        $row++;

        foreach ($citas as $cita) {
            $sheet->setCellValue('A' . $row, date('d/m/Y', strtotime($cita['fecha_cita'])));
            $sheet->setCellValue('B' . $row, date('g:i A', strtotime($cita['hora_inicio'])));
            $sheet->setCellValue('C' . $row, $cita['nombre_cliente'] . ' ' . $cita['apellido_cliente']);
            $sheet->setCellValue('D' . $row, $cita['nombre_empleado'] . ' ' . $cita['apellido_empleado']);
            $sheet->setCellValue('E' . $row, $cita['nombre_servicio']);
            $sheet->setCellValue('F' . $row, '$' . number_format($cita['precio'], 2));
            $row++;
        }
    }
}
