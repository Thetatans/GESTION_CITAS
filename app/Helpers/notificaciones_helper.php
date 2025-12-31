<?php

/**
 * Helper de Notificaciones Básicas
 * Proporciona funciones para enviar notificaciones a los usuarios
 */

if (!function_exists('enviar_notificacion_cita')) {
    /**
     * Enviar notificación básica de cita
     *
     * @param string $tipo Tipo de notificación: 'creada', 'confirmada', 'recordatorio', 'cancelada'
     * @param array $datosCita Datos de la cita
     * @return bool
     */
    function enviar_notificacion_cita($tipo, $datosCita)
    {
        $session = \Config\Services::session();

        // Construir mensaje según el tipo
        $mensaje = '';

        switch ($tipo) {
            case 'creada':
                $mensaje = "Tu cita ha sido agendada exitosamente para el " .
                          date('d/m/Y', strtotime($datosCita['fecha_cita'])) .
                          " a las " . date('g:i A', strtotime($datosCita['hora_inicio'])) .
                          ". Te esperamos!";
                break;

            case 'confirmada':
                $mensaje = "Tu cita para el " .
                          date('d/m/Y', strtotime($datosCita['fecha_cita'])) .
                          " ha sido confirmada.";
                break;

            case 'recordatorio':
                $mensaje = "Recordatorio: Tienes una cita mañana " .
                          date('d/m/Y', strtotime($datosCita['fecha_cita'])) .
                          " a las " . date('g:i A', strtotime($datosCita['hora_inicio'])) .
                          ".";
                break;

            case 'cancelada':
                $mensaje = "Tu cita del " .
                          date('d/m/Y', strtotime($datosCita['fecha_cita'])) .
                          " ha sido cancelada.";
                break;

            default:
                return false;
        }

        // Por ahora solo guardamos en sesión
        // En una versión futura se puede integrar con email, SMS, etc.
        $session->setFlashdata('notificacion_cita', $mensaje);

        // Registrar en log
        log_message('info', "Notificación de cita [{$tipo}]: {$mensaje}");

        return true;
    }
}

if (!function_exists('enviar_email_cita')) {
    /**
     * Enviar email de notificación de cita
     *
     * @param string $emailDestino Email del destinatario
     * @param string $asunto Asunto del email
     * @param array $datosCita Datos de la cita
     * @return bool
     */
    function enviar_email_cita($emailDestino, $asunto, $datosCita)
    {
        // Esta es una implementación básica
        // En producción se usaría una librería de email como PHPMailer o SendGrid

        $email = \Config\Services::email();

        // Configurar email (esto debería estar en Config/Email.php en producción)
        $config['protocol'] = 'mail';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";

        $email->initialize($config);

        // Construir mensaje HTML
        $mensaje = view('emails/cita_template', ['datos' => $datosCita]);

        $email->setFrom('noreply@barberia.com', 'Barbería');
        $email->setTo($emailDestino);
        $email->setSubject($asunto);
        $email->setMessage($mensaje);

        // En desarrollo, solo registrar en log
        log_message('info', "Email simulado enviado a {$emailDestino}: {$asunto}");

        // Descomentar para enviar email real
        // return $email->send();

        return true;
    }
}

if (!function_exists('obtener_plantilla_notificacion')) {
    /**
     * Obtener plantilla HTML para notificación
     *
     * @param string $tipo Tipo de notificación
     * @param array $datos Datos para la plantilla
     * @return string
     */
    function obtener_plantilla_notificacion($tipo, $datos)
    {
        $titulo = '';
        $contenido = '';
        $color = '#6b4423'; // Color por defecto

        switch ($tipo) {
            case 'nueva_cita':
                $titulo = '¡Nueva Cita Agendada!';
                $color = '#198754'; // verde
                $contenido = "
                    <p>Hola <strong>{$datos['cliente']}</strong>,</p>
                    <p>Tu cita ha sido agendada exitosamente:</p>
                    <ul>
                        <li><strong>Servicio:</strong> {$datos['servicio']}</li>
                        <li><strong>Fecha:</strong> {$datos['fecha']}</li>
                        <li><strong>Hora:</strong> {$datos['hora']}</li>
                        <li><strong>Barbero:</strong> {$datos['empleado']}</li>
                        <li><strong>Precio:</strong> \${$datos['precio']}</li>
                    </ul>
                    <p>Te esperamos!</p>
                ";
                break;

            case 'confirmacion':
                $titulo = 'Cita Confirmada';
                $color = '#0dcaf0'; // cyan
                $contenido = "
                    <p>Hola <strong>{$datos['cliente']}</strong>,</p>
                    <p>Tu cita ha sido confirmada por nuestro equipo:</p>
                    <ul>
                        <li><strong>Fecha:</strong> {$datos['fecha']}</li>
                        <li><strong>Hora:</strong> {$datos['hora']}</li>
                    </ul>
                ";
                break;

            case 'recordatorio':
                $titulo = 'Recordatorio de Cita';
                $color = '#ffc107'; // amarillo
                $contenido = "
                    <p>Hola <strong>{$datos['cliente']}</strong>,</p>
                    <p>Este es un recordatorio de tu cita:</p>
                    <ul>
                        <li><strong>Fecha:</strong> {$datos['fecha']}</li>
                        <li><strong>Hora:</strong> {$datos['hora']}</li>
                        <li><strong>Servicio:</strong> {$datos['servicio']}</li>
                    </ul>
                    <p>Te esperamos!</p>
                ";
                break;

            case 'cancelacion':
                $titulo = 'Cita Cancelada';
                $color = '#dc3545'; // rojo
                $contenido = "
                    <p>Hola <strong>{$datos['cliente']}</strong>,</p>
                    <p>Tu cita del {$datos['fecha']} ha sido cancelada.</p>
                    <p>Si deseas reagendar, no dudes en contactarnos.</p>
                ";
                break;
        }

        $html = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: {$color}; color: white; padding: 20px; text-align: center; }
                    .content { background-color: #f8f9fa; padding: 20px; }
                    .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
                    ul { background: white; padding: 20px; border-radius: 5px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>{$titulo}</h1>
                    </div>
                    <div class='content'>
                        {$contenido}
                    </div>
                    <div class='footer'>
                        <p>Barbería - Sistema de Gestión de Citas</p>
                        <p>Este es un mensaje automático, por favor no responder.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        return $html;
    }
}

if (!function_exists('registrar_log_notificacion')) {
    /**
     * Registrar notificación en log del sistema
     *
     * @param string $tipo Tipo de notificación
     * @param int $idCita ID de la cita
     * @param string $destinatario Email o teléfono del destinatario
     * @param bool $exitoso Si la notificación fue exitosa
     * @return void
     */
    function registrar_log_notificacion($tipo, $idCita, $destinatario, $exitoso = true)
    {
        $estado = $exitoso ? 'EXITOSO' : 'FALLIDO';
        log_message('info', "[NOTIFICACION {$estado}] Tipo: {$tipo}, Cita ID: {$idCita}, Destinatario: {$destinatario}");
    }
}
