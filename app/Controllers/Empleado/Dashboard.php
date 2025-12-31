<?php

// Declarar el namespace (espacio de nombres) del controlador
// Esto organiza el código en la carpeta Empleado
namespace App\Controllers\Empleado;

// Importar la clase base del controlador
use App\Controllers\BaseController;
use App\Models\CitasModel;
use App\Models\EmpleadoModel;

/**
 * Controlador Dashboard Empleado
 *
 * Gestiona el panel principal del empleado/barbero
 *
 * Funcionalidades:
 * - Mostrar dashboard con citas del día
 * - Accesos rápidos a agenda y gestión de citas
 * - Ver historial de servicios realizados
 * - Información del empleado logueado
 *
 * Protección:
 * - Requiere autenticación (filter: empleado)
 * - Solo accesible por usuarios con rol 'empleado'
 *
 * NOTA: Este controlador hace validación manual de sesión
 * además del filtro automático para mayor seguridad
 */
class Dashboard extends BaseController
{
    protected $citasModel;
    protected $empleadoModel;

    public function __construct()
    {
        $this->citasModel = new CitasModel();
        $this->empleadoModel = new EmpleadoModel();
    }

    /**
     * Método index()
     *
     * Muestra la página principal del panel del empleado
     *
     * Proceso:
     * 1. Verifica que haya una sesión activa
     * 2. Verifica que el usuario sea empleado
     * 3. Obtiene las citas del día del empleado
     * 4. Prepara los datos para la vista
     * 5. Carga la vista del dashboard
     *
     * Datos enviados a la vista:
     * - titulo: Título de la página
     * - usuario_nombre: Email del empleado
     * - citas_hoy: Citas del empleado para hoy
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string Redirección si falla o vista si es exitoso
     */
    public function index()
    {
        // PASO 1: VERIFICAR SESIÓN ACTIVA
        // Comprobar que existe la variable de sesión 'logueado'
        // Esta variable se establece en true cuando el usuario hace login correctamente
        if (!session()->get('logueado')) {
            // Si no hay sesión, redirigir al login
            // with() envía un mensaje flash que se muestra en la siguiente página
            return redirect()->to(base_url('login'))
                           ->with('error', 'Debes iniciar sesión');
        }

        // PASO 2: VERIFICAR ROL DE EMPLEADO
        // Comprobar que el rol del usuario sea 'empleado'
        // Esto previene que otros roles (admin o cliente) accedan por error
        if (session()->get('usuario_rol') !== 'empleado') {
            // Si el rol no es empleado, redirigir al login con mensaje de error
            return redirect()->to(base_url('login'))
                           ->with('error', 'No tienes permisos para acceder a esta sección');
        }

        // PASO 3: OBTENER CITAS DEL DÍA
        $idUsuario = session()->get('usuario_id');
        $empleado = $this->empleadoModel->where('id_usuario', $idUsuario)->first();

        $citasHoy = [];
        if ($empleado) {
            $citasHoy = $this->citasModel->obtenerCitasDelDia($empleado['id_empleado']);
        }

        // PASO 4: PREPARAR DATOS PARA LA VISTA
        $data = [
            // Título que aparecerá en la página
            'titulo' => 'Dashboard Empleado',

            // Email del empleado para mostrar en el navbar
            // Se obtiene directamente de la sesión
            'usuario_nombre' => session()->get('usuario_email'),

            // Citas del día
            'citas_hoy' => $citasHoy
        ];

        // PASO 5: CARGAR Y RETORNAR LA VISTA
        // view() es una función helper de CodeIgniter
        // Carga la vista ubicada en app/Views/empleado/dashboard.php
        // Le pasa el array $data con la información necesaria
        return view('empleado/dashboard', $data);
    }
}
