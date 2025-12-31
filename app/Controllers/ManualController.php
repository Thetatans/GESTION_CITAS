<?php

namespace App\Controllers;

class ManualController extends BaseController
{
    /**
     * Genera y descarga el manual de usuario en formato PDF
     *
     * @return void Descarga directa del archivo PDF
     */
    public function descargar()
    {
        // Validar que TCPDF esté disponible
        if (!class_exists('TCPDF')) {
            return redirect()->back()->with('error', 'La librería TCPDF no está instalada. Por favor, ejecute: composer require tecnickcom/tcpdf');
        }

        try {
            // Crear instancia de TCPDF
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Configuración del documento
            $pdf->SetCreator('Sistema de Gestión de Citas');
            $pdf->SetAuthor('Ilich Esteban Reyes Botia - SENA');
            $pdf->SetTitle('Manual de Usuario - Sistema de Gestión de Citas');
            $pdf->SetSubject('Manual completo del sistema');
            $pdf->SetKeywords('manual, usuario, barbería, citas, spa');

            // Configuración de márgenes
            $pdf->SetMargins(15, 15, 15);
            $pdf->SetHeaderMargin(5);
            $pdf->SetFooterMargin(10);
            $pdf->SetAutoPageBreak(TRUE, 15);

            // Configurar footer personalizado
            $pdf->setFooterFont(Array('helvetica', '', 8));
            $pdf->setFooterMargin(10);

            // Desactivar header por defecto
            $pdf->setPrintHeader(false);

            // Generar contenido del PDF
            $this->agregarPortadaManual($pdf);
            $pdf->AddPage();
            $this->agregarIndiceManual($pdf);
            $pdf->AddPage();
            $this->agregarSeccionIntroduccion($pdf);
            $pdf->AddPage();
            $this->agregarSeccionAcceso($pdf);
            $pdf->AddPage();
            $this->agregarSeccionRoles($pdf);
            $pdf->AddPage();
            $this->agregarSeccionCliente($pdf);
            $pdf->AddPage();
            $this->agregarSeccionEmpleado($pdf);
            $pdf->AddPage();
            $this->agregarSeccionAdmin($pdf);
            $pdf->AddPage();
            $this->agregarSeccionFAQ($pdf);
            $pdf->AddPage();
            $this->agregarSeccionSoporte($pdf);

            // Generar nombre del archivo
            $nombreArchivo = 'Manual_Usuario_SistemaCitas_' . date('Y-m-d') . '.pdf';

            // Salida del PDF (D = Descarga)
            $pdf->Output($nombreArchivo, 'D');

        } catch (\Exception $e) {
            log_message('error', 'Error al generar manual PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el manual PDF. Intente nuevamente.');
        }
    }

    /**
     * Agrega la portada del manual
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarPortadaManual($pdf)
    {
        $pdf->AddPage();

        // Espacio superior
        $pdf->Ln(40);

        // Ícono/Logo (usando símbolos)
        $pdf->SetFont('helvetica', 'B', 60);
        $pdf->SetTextColor(30, 58, 95); // Azul oscuro
        $pdf->Cell(0, 20, '✂', 0, 1, 'C');

        $pdf->Ln(10);

        // Título principal
        $pdf->SetFont('helvetica', 'B', 28);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 15, 'MANUAL DE USUARIO', 0, 1, 'C');

        $pdf->Ln(5);

        // Subtítulo
        $pdf->SetFont('helvetica', '', 18);
        $pdf->SetTextColor(107, 68, 35); // Café
        $pdf->Cell(0, 10, 'Sistema de Gestión de Citas', 0, 1, 'C');

        $pdf->Ln(20);

        // Línea decorativa
        $pdf->SetDrawColor(107, 68, 35);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(50, $pdf->GetY(), 160, $pdf->GetY());

        $pdf->Ln(15);

        // Información del proyecto
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetTextColor(73, 80, 87); // Gris
        $pdf->Cell(0, 7, 'Barbería y Spa', 0, 1, 'C');
        $pdf->Cell(0, 7, 'Proyecto DICO TELECOMUNICACIONES', 0, 1, 'C');

        $pdf->Ln(25);

        // Autor
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 6, 'Desarrollado por:', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(73, 80, 87);
        $pdf->Cell(0, 6, 'Ilich Esteban Reyes Botia', 0, 1, 'C');
        $pdf->Cell(0, 6, 'Aprendiz SENA', 0, 1, 'C');

        $pdf->Ln(15);

        // Fecha y versión
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Versión 1.0.0', 0, 1, 'C');
        $pdf->Cell(0, 5, date('d/m/Y'), 0, 1, 'C');

        // Footer de portada
        $pdf->SetY(-30);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 5, 'Este documento es propiedad del Sistema de Gestión de Citas', 0, 1, 'C');
    }

    /**
     * Agrega el índice del manual
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarIndiceManual($pdf)
    {
        // Título
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 10, 'ÍNDICE', 0, 1, 'C');
        $pdf->Ln(10);

        // Contenido del índice
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(73, 80, 87);

        $indice = [
            ['1. Introducción', '3'],
            ['   1.1. ¿Qué es este sistema?', '3'],
            ['   1.2. Funcionalidades principales', '3'],
            ['2. Acceso al Sistema', '4'],
            ['   2.1. Registro de nuevo usuario', '4'],
            ['   2.2. Inicio de sesión', '4'],
            ['   2.3. Recuperar contraseña', '4'],
            ['   2.4. Cerrar sesión', '4'],
            ['3. Roles de Usuario', '5'],
            ['   3.1. Cliente', '5'],
            ['   3.2. Empleado', '5'],
            ['   3.3. Administrador', '5'],
            ['4. Manual del Cliente', '6'],
            ['   4.1. Dashboard del cliente', '6'],
            ['   4.2. Agendar una cita', '6'],
            ['   4.3. Ver mis citas', '7'],
            ['   4.4. Estados de las citas', '7'],
            ['   4.5. Cancelar una cita', '7'],
            ['5. Manual del Empleado', '8'],
            ['   5.1. Dashboard del empleado', '8'],
            ['   5.2. Ver mi agenda', '8'],
            ['   5.3. Gestionar citas del día', '9'],
            ['   5.4. Actualizar estados de citas', '9'],
            ['6. Manual del Administrador', '10'],
            ['   6.1. Dashboard del administrador', '10'],
            ['   6.2. Gestionar clientes', '10'],
            ['   6.3. Gestionar empleados', '11'],
            ['   6.4. Gestionar servicios', '11'],
            ['   6.5. Ver todas las citas', '12'],
            ['   6.6. Reportes y estadísticas', '12'],
            ['7. Preguntas Frecuentes', '13'],
            ['8. Soporte Técnico', '14'],
        ];

        foreach ($indice as $item) {
            $pdf->Cell(160, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(20, 6, $item[1], 0, 1, 'R');
        }
    }

    /**
     * Agrega la sección de Introducción
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarSeccionIntroduccion($pdf)
    {
        // Título de sección
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 10, '1. INTRODUCCIÓN', 0, 1, 'L');
        $pdf->Ln(5);

        // Contenido
        $html = '
        <p style="font-size:11pt; color:#495057; line-height:1.6;">
            Bienvenido al <strong>Sistema de Gestión de Citas para Barbería y Spa</strong>. Este manual le guiará en el uso de todas las funcionalidades del sistema.
        </p>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:15px;">¿Qué es este sistema?</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Es una aplicación web diseñada para facilitar la gestión de citas, clientes, empleados y servicios en establecimientos de barbería y spa. Permite:
        </p>

        <ul style="font-size:10pt; color:#495057; line-height:1.8;">
            <li><strong style="color:#4caf50;">✓</strong> Agendar citas de forma rápida y sencilla</li>
            <li><strong style="color:#4caf50;">✓</strong> Gestionar horarios de empleados</li>
            <li><strong style="color:#4caf50;">✓</strong> Administrar servicios y precios</li>
            <li><strong style="color:#4caf50;">✓</strong> Generar reportes y estadísticas</li>
            <li><strong style="color:#4caf50;">✓</strong> Enviar notificaciones automáticas</li>
        </ul>

        <div style="background-color:#e3f2fd; border-left:4px solid #2196f3; padding:10px; margin:15px 0;">
            <strong>💡 Tip:</strong> Este manual está dividido por roles (Cliente, Empleado, Administrador). Diríjase a la sección que corresponda a su rol.
        </div>
        ';

        $pdf->SetFont('helvetica', '', 10);
        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Agrega la sección de Acceso al Sistema
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarSeccionAcceso($pdf)
    {
        // Título de sección
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 10, '2. ACCESO AL SISTEMA', 0, 1, 'L');
        $pdf->Ln(5);

        $html = '
        <h3 style="color:#6b4423; font-size:13pt;">Registro de Nuevo Usuario</h3>

        <div style="background-color:#f5e6d3; padding:10px; margin:10px 0; border-left:4px solid #6b4423;">
            <span style="background-color:#1e3a5f; color:white; padding:5px 12px; border-radius:50%; font-weight:bold;">1</span>
            <strong style="margin-left:10px;">Acceder a la página de registro</strong>
            <p style="margin-left:40px; margin-top:8px;">Desde la página de inicio, haga clic en el botón <code>Registrarse</code>.</p>
        </div>

        <div style="background-color:#f5e6d3; padding:10px; margin:10px 0; border-left:4px solid #6b4423;">
            <span style="background-color:#1e3a5f; color:white; padding:5px 12px; border-radius:50%; font-weight:bold;">2</span>
            <strong style="margin-left:10px;">Completar el formulario</strong>
            <p style="margin-left:40px; margin-top:8px;">Ingrese sus datos personales: nombre completo, correo electrónico, teléfono y contraseña segura (mínimo 8 caracteres).</p>
        </div>

        <div style="background-color:#f5e6d3; padding:10px; margin:10px 0; border-left:4px solid #6b4423;">
            <span style="background-color:#1e3a5f; color:white; padding:5px 12px; border-radius:50%; font-weight:bold;">3</span>
            <strong style="margin-left:10px;">Confirmar registro</strong>
            <p style="margin-left:40px; margin-top:8px;">Haga clic en <code>Registrarse</code> y recibirá una confirmación en pantalla.</p>
        </div>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Inicio de Sesión</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Para acceder al sistema, ingrese su correo electrónico y contraseña en la página de login. Haga clic en <code>Ingresar</code> y será redirigido a su dashboard según su rol.
        </p>

        <div style="background-color:#fff3e0; border-left:4px solid #ff9800; padding:10px; margin:15px 0;">
            <strong>⚠ Importante:</strong> Si olvida su contraseña, use la opción "Recuperar contraseña" en la página de login.
        </div>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Cerrar Sesión</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Para cerrar sesión de forma segura, haga clic en el botón <code>Salir</code> ubicado en la esquina superior derecha de cualquier página.
        </p>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Agrega la sección de Roles de Usuario
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarSeccionRoles($pdf)
    {
        // Título de sección
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 10, '3. ROLES DE USUARIO', 0, 1, 'L');
        $pdf->Ln(5);

        $html = '
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            El sistema cuenta con tres roles principales, cada uno con funcionalidades específicas:
        </p>

        <table border="1" cellpadding="8" style="border-collapse:collapse; margin-top:15px;">
            <tr style="background-color:#1e3a5f; color:white; font-weight:bold;">
                <th width="25%">Rol</th>
                <th width="75%">Descripción y Permisos</th>
            </tr>
            <tr>
                <td style="background-color:#6b4423; color:white; font-weight:bold; text-align:center;">
                    👤 CLIENTE
                </td>
                <td>
                    <strong>Permisos:</strong>
                    <ul style="margin:5px 0; padding-left:20px;">
                        <li>Agendar nuevas citas</li>
                        <li>Ver historial de citas</li>
                        <li>Cancelar citas pendientes o confirmadas</li>
                        <li>Actualizar información personal</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td style="background-color:#495057; color:white; font-weight:bold; text-align:center;">
                    👨‍💼 EMPLEADO
                </td>
                <td>
                    <strong>Permisos:</strong>
                    <ul style="margin:5px 0; padding-left:20px;">
                        <li>Ver agenda personal de citas</li>
                        <li>Confirmar o rechazar citas asignadas</li>
                        <li>Actualizar estados de citas (en proceso, completada)</li>
                        <li>Acceder a información de contacto de clientes</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td style="background-color:#1e3a5f; color:white; font-weight:bold; text-align:center;">
                    🛡️ ADMIN
                </td>
                <td>
                    <strong>Permisos completos:</strong>
                    <ul style="margin:5px 0; padding-left:20px;">
                        <li>Gestionar clientes (crear, editar, eliminar)</li>
                        <li>Gestionar empleados (crear, editar, eliminar)</li>
                        <li>Gestionar servicios (crear, editar, eliminar)</li>
                        <li>Ver y gestionar todas las citas del sistema</li>
                        <li>Generar reportes y estadísticas</li>
                        <li>Exportar datos a PDF y Excel</li>
                    </ul>
                </td>
            </tr>
        </table>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Agrega la sección Manual del Cliente
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarSeccionCliente($pdf)
    {
        // Título de sección
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 10, '4. MANUAL DEL CLIENTE', 0, 1, 'L');
        $pdf->Ln(5);

        $html = '
        <h3 style="color:#6b4423; font-size:13pt;">Dashboard del Cliente</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Al iniciar sesión como cliente, verá su panel principal con acceso rápido para agendar citas, vista de sus próximas citas y historial de servicios recibidos.
        </p>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Agendar una Nueva Cita</h3>

        <div style="background-color:#f5e6d3; padding:10px; margin:10px 0; border-left:4px solid #6b4423;">
            <span style="background-color:#1e3a5f; color:white; padding:5px 12px; border-radius:50%; font-weight:bold;">1</span>
            <strong style="margin-left:10px;">Acceder al formulario</strong>
            <p style="margin-left:40px; margin-top:8px;">Haga clic en <code>Agendar Cita</code> desde su dashboard.</p>
        </div>

        <div style="background-color:#f5e6d3; padding:10px; margin:10px 0; border-left:4px solid #6b4423;">
            <span style="background-color:#1e3a5f; color:white; padding:5px 12px; border-radius:50%; font-weight:bold;">2</span>
            <strong style="margin-left:10px;">Seleccionar servicio</strong>
            <p style="margin-left:40px; margin-top:8px;">Elija el servicio deseado del menú desplegable (corte, barba, spa, etc.).</p>
        </div>

        <div style="background-color:#f5e6d3; padding:10px; margin:10px 0; border-left:4px solid #6b4423;">
            <span style="background-color:#1e3a5f; color:white; padding:5px 12px; border-radius:50%; font-weight:bold;">3</span>
            <strong style="margin-left:10px;">Elegir empleado</strong>
            <p style="margin-left:40px; margin-top:8px;">Seleccione el empleado de su preferencia o deje que el sistema asigne uno disponible.</p>
        </div>

        <div style="background-color:#f5e6d3; padding:10px; margin:10px 0; border-left:4px solid #6b4423;">
            <span style="background-color:#1e3a5f; color:white; padding:5px 12px; border-radius:50%; font-weight:bold;">4</span>
            <strong style="margin-left:10px;">Seleccionar fecha y hora</strong>
            <p style="margin-left:40px; margin-top:8px;">El calendario mostrará solo horarios disponibles. Elija la fecha y hora que le convenga.</p>
        </div>

        <div style="background-color:#f5e6d3; padding:10px; margin:10px 0; border-left:4px solid #6b4423;">
            <span style="background-color:#1e3a5f; color:white; padding:5px 12px; border-radius:50%; font-weight:bold;">5</span>
            <strong style="margin-left:10px;">Confirmar reserva</strong>
            <p style="margin-left:40px; margin-top:8px;">Revise los detalles y haga clic en <code>Agendar Cita</code>.</p>
        </div>

        <div style="background-color:#e8f5e9; border-left:4px solid #4caf50; padding:10px; margin:15px 0;">
            <strong>✓ Confirmación:</strong> Recibirá una notificación en pantalla y por correo electrónico confirmando su cita.
        </div>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px; page-break-before:always;">Estados de las Citas</h3>
        <table border="1" cellpadding="6" style="border-collapse:collapse; margin-top:10px;">
            <tr style="background-color:#1e3a5f; color:white; font-weight:bold;">
                <th width="25%">Estado</th>
                <th width="45%">Significado</th>
                <th width="30%">Acciones</th>
            </tr>
            <tr>
                <td style="text-align:center; background-color:#ffc107; font-weight:bold;">Pendiente</td>
                <td>Cita agendada, esperando confirmación del empleado</td>
                <td>Cancelar</td>
            </tr>
            <tr>
                <td style="text-align:center; background-color:#28a745; color:white; font-weight:bold;">Confirmada</td>
                <td>Empleado confirmó la cita</td>
                <td>Cancelar</td>
            </tr>
            <tr>
                <td style="text-align:center; background-color:#17a2b8; color:white; font-weight:bold;">En Proceso</td>
                <td>El servicio está siendo realizado</td>
                <td>Ninguna</td>
            </tr>
            <tr>
                <td style="text-align:center; background-color:#007bff; color:white; font-weight:bold;">Completada</td>
                <td>Servicio finalizado exitosamente</td>
                <td>Ver detalle</td>
            </tr>
            <tr>
                <td style="text-align:center; background-color:#dc3545; color:white; font-weight:bold;">Cancelada</td>
                <td>Cita cancelada por cliente o empleado</td>
                <td>Ver detalle</td>
            </tr>
        </table>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Cancelar una Cita</h3>
        <div style="background-color:#fff3e0; border-left:4px solid #ff9800; padding:10px; margin:10px 0;">
            <strong>⚠ Política de cancelación:</strong> Las citas deben cancelarse con al menos 24 horas de anticipación.
        </div>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Para cancelar una cita, acceda a <code>Mis Citas</code>, busque la cita que desea cancelar (solo disponible para citas pendientes o confirmadas), haga clic en <code>Cancelar</code> y confirme la acción en el mensaje emergente.
        </p>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Agrega la sección Manual del Empleado
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarSeccionEmpleado($pdf)
    {
        // Título de sección
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 10, '5. MANUAL DEL EMPLEADO', 0, 1, 'L');
        $pdf->Ln(5);

        $html = '
        <h3 style="color:#6b4423; font-size:13pt;">Dashboard del Empleado</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Como empleado, su panel principal muestra las citas del día actual, acceso rápido a su agenda y notificaciones de nuevas citas asignadas.
        </p>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Ver Mi Agenda</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            La agenda le permite visualizar todas sus citas organizadas por fecha. Use los controles del calendario para navegar entre fechas y haga clic en cualquier cita para ver información completa del cliente y servicio.
        </p>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Gestionar Citas del Día</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            En su dashboard verá una tabla con las citas del día actual. Para cada cita puede realizar diferentes acciones según su estado:
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">Confirmar una Cita Pendiente</h4>
        <ul style="font-size:10pt; color:#495057; line-height:1.6;">
            <li>Localice la cita con estado <strong style="color:#ffc107;">Pendiente</strong></li>
            <li>Haga clic en <code>Confirmar</code></li>
            <li>La cita cambiará a estado <strong style="color:#28a745;">Confirmada</strong></li>
        </ul>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">Iniciar un Servicio</h4>
        <ul style="font-size:10pt; color:#495057; line-height:1.6;">
            <li>Cuando el cliente llegue, busque su cita <strong style="color:#28a745;">Confirmada</strong></li>
            <li>Haga clic en <code>Iniciar</code></li>
            <li>El estado cambia a <strong style="color:#17a2b8;">En Proceso</strong></li>
        </ul>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">Completar un Servicio</h4>
        <ul style="font-size:10pt; color:#495057; line-height:1.6;">
            <li>Al finalizar el servicio, localice la cita <strong style="color:#17a2b8;">En Proceso</strong></li>
            <li>Haga clic en <code>Completar</code></li>
            <li>El estado cambia a <strong style="color:#007bff;">Completada</strong></li>
        </ul>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">Cancelar una Cita</h4>
        <ul style="font-size:10pt; color:#495057; line-height:1.6;">
            <li>Si el cliente no asiste o solicita cancelación, haga clic en <code>Cancelar</code></li>
            <li>Confirme la acción en el mensaje emergente</li>
            <li>El cliente recibirá una notificación automática</li>
        </ul>

        <div style="background-color:#e3f2fd; border-left:4px solid #2196f3; padding:10px; margin:15px 0;">
            <strong>💡 Tip:</strong> Puede contactar al cliente directamente haciendo clic en su número de teléfono para realizar una llamada.
        </div>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Agrega la sección Manual del Administrador
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarSeccionAdmin($pdf)
    {
        // Título de sección
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 10, '6. MANUAL DEL ADMINISTRADOR', 0, 1, 'L');
        $pdf->Ln(5);

        $html = '
        <h3 style="color:#6b4423; font-size:13pt;">Dashboard del Administrador</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            El panel de administración proporciona acceso a todas las funcionalidades del sistema: gestión de clientes, empleados, servicios, visualización de citas y generación de reportes.
        </p>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Gestionar Clientes</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Acceda a <code>Gestionar Clientes</code> para ver todos los clientes registrados. Puede crear nuevos clientes haciendo clic en <code>Nuevo Cliente</code> y completando el formulario con nombre, apellido, correo, teléfono y contraseña inicial.
        </p>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Para editar un cliente, haga clic en <code>Editar</code> en el listado, modifique los campos necesarios y guarde los cambios.
        </p>
        <div style="background-color:#fff3e0; border-left:4px solid #ff9800; padding:10px; margin:10px 0;">
            <strong>⚠ Advertencia:</strong> Eliminar un cliente es una acción permanente. Asegúrese de que no tenga citas pendientes.
        </div>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Gestionar Empleados</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            En <code>Gestionar Empleados</code> puede ver todos los empleados con su información completa: nombre, especialidad, horario de trabajo y estado (activo/inactivo).
        </p>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Al crear un nuevo empleado, debe ingresar datos personales, configurar su horario de trabajo (hora inicio y hora fin) y asignar su especialidad (barbero, estilista, masajista, etc.).
        </p>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            <strong>Tip:</strong> En lugar de eliminar empleados, puede desactivarlos temporalmente. Los empleados desactivados no aparecerán disponibles para nuevas citas.
        </p>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px; page-break-before:always;">Gestionar Servicios</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Acceda a <code>Gestionar Servicios</code> para ver todos los servicios ofrecidos: nombre, descripción, duración (en minutos), precio y estado (activo/inactivo).
        </p>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Para crear un nuevo servicio, ingrese nombre, descripción, duración en minutos y precio. Puede actualizar precios y duración en cualquier momento.
        </p>
        <div style="background-color:#e3f2fd; border-left:4px solid #2196f3; padding:10px; margin:10px 0;">
            <strong>💡 Tip:</strong> Al cambiar la duración de un servicio, tenga en cuenta que esto afectará la disponibilidad de horarios en el calendario.
        </div>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Ver Todas las Citas</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            En <code>Ver Todas las Citas</code> tiene una vista completa de todas las citas del sistema. Puede filtrar por fecha, estado, empleado o cliente. También puede ver detalles completos, editar información o cancelar citas si es necesario.
        </p>
        <div style="background-color:#fff3e0; border-left:4px solid #ff9800; padding:10px; margin:10px 0;">
            <strong>⚠ Importante:</strong> Al modificar una cita, tanto el cliente como el empleado recibirán notificación del cambio.
        </div>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Reportes y Estadísticas</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            El módulo de reportes ofrece información valiosa para la toma de decisiones:
        </p>
        <ul style="font-size:10pt; color:#495057; line-height:1.8;">
            <li><strong>Reporte por Fecha:</strong> Seleccione un rango de fechas, visualice todas las citas en ese período y genere PDF para imprimir o compartir.</li>
            <li><strong>Reporte por Empleado:</strong> Vea el rendimiento de cada empleado: citas completadas, canceladas e ingresos generados. Identifique a los empleados más productivos.</li>
            <li><strong>Reporte por Servicio:</strong> Analice qué servicios son más demandados, vea ingresos por tipo de servicio y optimice su oferta.</li>
            <li><strong>Citas Realizadas:</strong> Reporte de todas las citas completadas exitosamente, útil para calcular ingresos totales.</li>
            <li><strong>Citas Pendientes:</strong> Listado de citas que requieren atención o confirmación.</li>
        </ul>
        <div style="background-color:#e8f5e9; border-left:4px solid #4caf50; padding:10px; margin:15px 0;">
            <strong>✓ Exportar:</strong> Todos los reportes pueden exportarse a PDF para su archivo o presentación.
        </div>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Agrega la sección de Preguntas Frecuentes
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarSeccionFAQ($pdf)
    {
        // Título de sección
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 10, '7. PREGUNTAS FRECUENTES (FAQ)', 0, 1, 'L');
        $pdf->Ln(5);

        $html = '
        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿Puedo cambiar mi contraseña?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Sí, puede cambiar su contraseña desde la opción "Cambiar Contraseña" en el menú de su perfil.
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿Cómo recupero mi contraseña si la olvido?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            En la página de login, haga clic en "¿Olvidaste tu contraseña?" e ingrese su correo electrónico. Recibirá instrucciones para restablecerla.
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿Puedo agendar múltiples citas al mismo tiempo?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Sí, puede agendar varias citas para diferentes fechas. Sin embargo, no puede tener dos citas en el mismo horario.
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿Con cuánta anticipación debo agendar una cita?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Recomendamos agendar con al menos 24 horas de anticipación para asegurar disponibilidad.
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿Qué pasa si llego tarde a mi cita?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Si llega más de 15 minutos tarde, la cita podría ser cancelada. Le recomendamos llegar puntualmente.
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿Puedo solicitar un empleado específico?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Sí, al agendar su cita puede seleccionar el empleado de su preferencia, sujeto a disponibilidad.
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿Cómo sé si mi cita fue confirmada?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Recibirá una notificación por correo electrónico y el estado de su cita cambiará de "Pendiente" a "Confirmada".
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿El sistema envía recordatorios de citas?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Sí, el sistema envía recordatorios automáticos 24 horas antes de su cita programada.
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿Puedo ver el historial de mis citas pasadas?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Sí, en la sección "Mis Citas" puede filtrar y ver todas sus citas completadas.
        </p>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">¿Los precios incluyen propina?</h4>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            No, los precios mostrados son por el servicio. La propina es opcional y se maneja directamente con el empleado.
        </p>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    /**
     * Agrega la sección de Soporte Técnico
     *
     * @param object $pdf Instancia de TCPDF
     * @return void
     */
    private function agregarSeccionSoporte($pdf)
    {
        // Título de sección
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 10, '8. SOPORTE TÉCNICO', 0, 1, 'L');
        $pdf->Ln(5);

        $html = '
        <h3 style="color:#6b4423; font-size:13pt;">Contacto</h3>
        <p style="font-size:10pt; color:#495057; line-height:1.6;">
            Si experimenta problemas técnicos o tiene dudas sobre el funcionamiento del sistema, puede contactarnos:
        </p>

        <table border="1" cellpadding="10" style="border-collapse:collapse; margin-top:15px;">
            <tr>
                <td width="50%" style="background-color:#f5e6d3;">
                    <h4 style="color:#1e3a5f; margin:0;">📧 Correo Electrónico</h4>
                    <p style="margin:5px 0;">soporte@barberia.com</p>
                </td>
                <td width="50%" style="background-color:#f5e6d3;">
                    <h4 style="color:#1e3a5f; margin:0;">📞 Teléfono</h4>
                    <p style="margin:5px 0;">+57 300 123 4567</p>
                </td>
            </tr>
        </table>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Problemas Comunes</h3>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">No puedo iniciar sesión</h4>
        <ul style="font-size:10pt; color:#495057; line-height:1.6;">
            <li>Verifique que su correo y contraseña sean correctos</li>
            <li>Intente recuperar su contraseña</li>
            <li>Borre caché y cookies de su navegador</li>
            <li>Intente con otro navegador</li>
        </ul>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">No veo horarios disponibles</h4>
        <ul style="font-size:10pt; color:#495057; line-height:1.6;">
            <li>Intente con otra fecha</li>
            <li>Seleccione otro empleado</li>
            <li>Verifique que el servicio esté activo</li>
        </ul>

        <h4 style="color:#1e3a5f; font-size:11pt; margin-top:15px;">No recibí confirmación por correo</h4>
        <ul style="font-size:10pt; color:#495057; line-height:1.6;">
            <li>Revise su carpeta de spam</li>
            <li>Verifique que su correo esté correctamente escrito en su perfil</li>
            <li>Espere unos minutos, el envío puede demorar</li>
        </ul>

        <div style="background-color:#e3f2fd; border-left:4px solid #2196f3; padding:10px; margin:15px 0;">
            <strong>🕐 Horario de atención:</strong> Lunes a Viernes de 8:00 AM a 6:00 PM
        </div>

        <h3 style="color:#6b4423; font-size:13pt; margin-top:20px;">Información del Sistema</h3>
        <table border="1" cellpadding="6" style="border-collapse:collapse; margin-top:10px;">
            <tr>
                <td width="40%" style="background-color:#f5e6d3;"><strong>Versión del Sistema:</strong></td>
                <td width="60%">1.0.0</td>
            </tr>
            <tr>
                <td style="background-color:#f5e6d3;"><strong>Última actualización:</strong></td>
                <td>Diciembre 2024</td>
            </tr>
            <tr>
                <td style="background-color:#f5e6d3;"><strong>Desarrollador:</strong></td>
                <td>Ilich Esteban Reyes Botia - SENA</td>
            </tr>
            <tr>
                <td style="background-color:#f5e6d3;"><strong>Proyecto:</strong></td>
                <td>DICO TELECOMUNICACIONES</td>
            </tr>
        </table>

        <div style="background-color:#e8f5e9; border-left:4px solid #4caf50; padding:15px; margin:20px 0; text-align:center;">
            <h4 style="color:#1e3a5f; margin:0 0 10px 0;">Gracias por utilizar nuestro Sistema de Gestión de Citas</h4>
            <p style="margin:0; color:#495057;">Estamos comprometidos en brindarle la mejor experiencia posible.</p>
        </div>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');

        // Footer final
        $pdf->SetY(-30);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 5, '───────────────────────────────────────────────────────────────', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Sistema de Gestión de Citas | Barbería y Spa | Desarrollado por: Ilich Esteban Reyes Botia - SENA', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Proyecto DICO TELECOMUNICACIONES | ' . date('Y'), 0, 1, 'C');
    }
}
