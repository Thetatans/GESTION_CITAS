<?php

// Definir namespace del controlador
namespace App\Controllers;

// Importar el modelo de usuario
use App\Models\UsuarioModel;

/**
 * Controlador Auth
 * 
 * Maneja toda la autenticación del sistema:
 * - Login
 * - Registro
 * - Logout
 * - Recuperación de contraseña
 * - Cambio de contraseña
 */
class Auth extends BaseController
{
    // ============================================
    // PROPIEDADES
    // ============================================
    
    // Instancia del modelo de usuario
    // Se inicializa en el constructor
    protected $usuarioModel;

    /**
     * Constructor
     * 
     * Se ejecuta automáticamente al crear el controlador
     * Aquí inicializamos lo que necesitamos
     */
    public function __construct()
    {
        // Crear instancia del modelo
        $this->usuarioModel = new UsuarioModel();
        
        // Cargar helpers (funciones auxiliares)
        // form: funciones para formularios
        // url: funciones para URLs
        // cookie: funciones para cookies
        helper(['form', 'url', 'cookie']);
    }

    // ============================================
    // MÉTODOS DE LOGIN
    // ============================================

    /**
     * Mostrar formulario de login
     * 
     * Si el usuario ya está logueado, lo redirige
     * Si no, muestra el formulario
     * 
     * @return mixed Vista de login o redirección
     */
    public function login()
    {
        // Verificar si ya hay una sesión activa
        // session()->has() verifica si existe una variable en sesión
        if (session()->has('usuario_id')) {
            // Si ya está logueado, redirigir según su rol
            return $this->redirigirSegunRol();
        }

        // Preparar datos para la vista
        $data = [
            'titulo' => 'Iniciar Sesión'
        ];

        // Mostrar la vista de login
        return view('auth/login', $data);
    }

    /**
     * Procesar intento de login
     * 
     * Valida credenciales y crea la sesión
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse Redirección
     */
    public function intentarLogin()
    {
        // ========================================
        // PASO 1: VALIDAR DATOS DEL FORMULARIO
        // ========================================
        
        // Definir reglas de validación
        $reglas = [
            // Email es obligatorio y debe tener formato válido
            'email' => 'required|valid_email',
            
            // Password es obligatorio, mínimo 8 caracteres
            'password' => 'required|min_length[8]'
        ];

        // Ejecutar validación
        // validate() retorna true si pasa, false si falla
        if (!$this->validate($reglas)) {
            // Si falla la validación:
            // - redirect()->back() vuelve a la página anterior
            // - withInput() mantiene los datos ingresados (excepto password)
            // - with() envía datos a la siguiente petición
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // ========================================
        // PASO 2: OBTENER DATOS DEL FORMULARIO
        // ========================================
        
        // getPost() obtiene datos enviados por POST
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $recordar = $this->request->getPost('recordar'); // Checkbox "Recordarme"

        // ========================================
        // PASO 3: VERIFICAR CREDENCIALES
        // ========================================
        
        // Llamar al método del modelo que verifica email y password
        $usuario = $this->usuarioModel->verificarCredenciales($email, $password);

        // Si las credenciales son incorrectas
        if (!$usuario) {
            // Volver atrás con mensaje de error
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Email o contraseña incorrectos');
        }

        // ========================================
        // PASO 4: ACTUALIZAR ÚLTIMO ACCESO
        // ========================================
        
        // Registrar que el usuario inició sesión
        // Útil para estadísticas y auditoría
        $this->usuarioModel->actualizarUltimoAcceso($usuario['id_usuario']);

        // ========================================
        // PASO 5: CREAR SESIÓN
        // ========================================
        
        // Preparar datos que se guardarán en la sesión
        // Estos datos estarán disponibles en todo el sistema
        $sesionData = [
            'usuario_id'    => $usuario['id_usuario'],  // ID del usuario
            'usuario_email' => $usuario['email'],        // Email del usuario
            'usuario_rol'   => $usuario['rol'],          // Rol (admin/empleado/cliente)
            'logueado'      => true                      // Bandera de autenticación
        ];

        // Guardar datos en la sesión
        // session() retorna la instancia de sesión de CodeIgniter
        session()->set($sesionData);

        // ========================================
        // PASO 6: MANEJAR "RECORDARME" (OPCIONAL)
        // ========================================
        
        // Si el usuario marcó el checkbox "Recordarme"
        if ($recordar) {
            // Crear una cookie que durará 30 días
            $cookie = [
                'name'   => 'recordar_usuario',          // Nombre de la cookie
                'value'  => $usuario['id_usuario'],      // Valor (ID del usuario)
                'expire' => 2592000,                     // 30 días en segundos
            ];
            
            // Establecer la cookie en la respuesta
            $this->response->setCookie($cookie);
        }

        // ========================================
        // PASO 7: REDIRIGIR SEGÚN ROL
        // ========================================
        
        // Enviar al usuario a su dashboard correspondiente
        return $this->redirigirSegunRol();
    }

    // ============================================
    // MÉTODOS DE REGISTRO
    // ============================================

    /**
     * Mostrar formulario de registro
     * 
     * @return mixed Vista de registro o redirección
     */
    public function registro()
    {
        // Si ya está logueado, redirigir
        if (session()->has('usuario_id')) {
            return $this->redirigirSegunRol();
        }

        // Datos para la vista
        $data = [
            'titulo' => 'Crear Cuenta'
        ];

        // Mostrar formulario de registro
        return view('auth/registro', $data);
    }

/**
 * Procesar registro de nuevo cliente
 *
 * Solo permite registro de clientes.
 * Empleados y admins son creados por el administrador.
 *
 * @return \CodeIgniter\HTTP\RedirectResponse Redirección
 */
public function intentarRegistro()
{
    $reglas = [
        'nombre'             => 'required|min_length[3]|max_length[100]',
        'apellido'           => 'required|min_length[3]|max_length[100]',
        'telefono'           => 'required|min_length[7]|max_length[20]',
        'email'              => 'required|valid_email|is_unique[usuarios.email]',
        'password'           => 'required|min_length[8]',
        'password_confirmar' => 'required|matches[password]',
    ];

    $mensajes = [
        'nombre' => [
            'required'   => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
        ],
        'apellido' => [
            'required'   => 'El apellido es obligatorio',
            'min_length' => 'El apellido debe tener al menos 3 caracteres',
        ],
        'telefono' => [
            'required'   => 'El teléfono es obligatorio',
            'min_length' => 'El teléfono debe tener al menos 7 dígitos',
        ],
        'email' => [
            'required'    => 'El email es obligatorio',
            'valid_email' => 'Ingresa un email válido',
            'is_unique'   => 'Este email ya está registrado'
        ],
        'password' => [
            'required'   => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 8 caracteres'
        ],
        'password_confirmar' => [
            'required' => 'Debes confirmar la contraseña',
            'matches'  => 'Las contraseñas no coinciden'
        ]
    ];

    if (!$this->validate($reglas, $mensajes)) {
        return redirect()->back()
                       ->withInput()
                       ->with('errors', $this->validator->getErrors());
    }

    $db = \Config\Database::connect();
    $db->transStart();

    try {
        // Solo se permite registro de clientes
        $datosUsuario = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'rol'      => 'cliente',
            'activo'   => 1
        ];

        if (!$this->usuarioModel->insert($datosUsuario)) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear el usuario');
        }

        $id_usuario = $this->usuarioModel->getInsertID();

        // Crear perfil de cliente
        $clienteModel = new \App\Models\ClienteModel();
        $datosCliente = [
            'id_usuario'       => $id_usuario,
            'nombre'           => $this->request->getPost('nombre'),
            'apellido'         => $this->request->getPost('apellido'),
            'telefono'         => $this->request->getPost('telefono'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento') ?: null,
            'genero'           => $this->request->getPost('genero') ?: null,
            'direccion'        => $this->request->getPost('direccion') ?: null,
        ];

        if (!$clienteModel->insert($datosCliente)) {
            $db->transRollback();
            $errores = $clienteModel->errors();
            $mensajeError = !empty($errores) ? implode(', ', $errores) : 'Error desconocido al crear perfil';
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear el perfil: ' . $mensajeError);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al completar el registro');
        }

        // Login automático después del registro
        $sesionData = [
            'usuario_id'    => $id_usuario,
            'usuario_email' => $this->request->getPost('email'),
            'usuario_rol'   => 'cliente',
            'logueado'      => true
        ];
        session()->set($sesionData);

        // Redirigir al dashboard del cliente
        return redirect()->to('/cliente/dashboard')
                       ->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');

    } catch (\Exception $e) {
        $db->transRollback();
        log_message('error', 'Error en registro: ' . $e->getMessage());
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error al crear la cuenta: ' . $e->getMessage());
    }
}
    public function logout()
    {
        // Destruir completamente la sesión
        // Esto elimina todos los datos almacenados
        session()->destroy();

        // Eliminar la cookie "recordarme" si existe
        delete_cookie('recordar_usuario');

        // Redirigir al login con mensaje
        return redirect()->to('/login')
                       ->with('success', 'Has cerrado sesión correctamente');
    }

    // ============================================
    // MÉTODOS DE CAMBIO DE CONTRASEÑA
    // ============================================

    /**
     * Mostrar formulario para cambiar contraseña
     * 
     * @return mixed Vista o redirección
     */
    public function cambiarPassword()
    {
        // Solo usuarios logueados pueden cambiar su contraseña
        if (!session()->has('usuario_id')) {
            return redirect()->to('/login');
        }

        $data = [
            'titulo' => 'Cambiar Contraseña'
        ];

        return view('auth/cambiar_password', $data);
    }

    /**
     * Procesar cambio de contraseña
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse Redirección
     */
    public function actualizarPassword()
    {
        // Verificar que esté logueado
        if (!session()->has('usuario_id')) {
            return redirect()->to('/login');
        }

        // ========================================
        // PASO 1: VALIDAR FORMULARIO
        // ========================================
        
        $reglas = [
            'password_actual'    => 'required',
            'password_nueva'     => 'required|min_length[8]',
            'password_confirmar' => 'required|matches[password_nueva]'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors());
        }

        // ========================================
        // PASO 2: VERIFICAR CONTRASEÑA ACTUAL
        // ========================================
        
        // Obtener ID del usuario logueado
        $usuario_id = session()->get('usuario_id');
        
        // Buscar datos del usuario
        $usuario = $this->usuarioModel->find($usuario_id);

        // Verificar que la contraseña actual sea correcta
        if (!password_verify($this->request->getPost('password_actual'), $usuario['password'])) {
            return redirect()->back()
                           ->with('error', 'La contraseña actual es incorrecta');
        }

        // ========================================
        // PASO 3: ACTUALIZAR CONTRASEÑA
        // ========================================
        
        // Cambiar a la nueva contraseña
        if ($this->usuarioModel->cambiarPassword($usuario_id, $this->request->getPost('password_nueva'))) {
            return redirect()->back()
                           ->with('success', 'Contraseña actualizada correctamente');
        } else {
            return redirect()->back()
                           ->with('error', 'Error al actualizar la contraseña');
        }
    }

    // ============================================
    // MÉTODOS AUXILIARES PRIVADOS
    // ============================================

    /**
     * Redirigir al usuario según su rol
     * 
     * Cada rol tiene su propio dashboard
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse Redirección
     */
    private function redirigirSegunRol()
    {
        // Obtener el rol del usuario de la sesión
        $rol = session()->get('usuario_rol');

        // Según el rol, redirigir a diferentes URLs
        switch ($rol) {
            case 'admin':
                // Administrador va al panel de administración
                return redirect()->to('/admin/dashboard');
                
            case 'empleado':
                // Empleado va a su panel
                return redirect()->to('/empleado/dashboard');
                
            case 'cliente':
                // Cliente va a su panel
                return redirect()->to('/cliente/dashboard');
                
            default:
                // Si el rol no es reconocido, ir al inicio
                return redirect()->to('/');
        }
    }

    /**
     * Mostrar formulario de recuperación de contraseña
     * (Funcionalidad opcional - no implementada completamente)
     * 
     * @return string Vista
     */
    public function recuperarPassword()
    {
        $data = [
            'titulo' => 'Recuperar Contraseña'
        ];

        return view('auth/recuperar', $data);
    }
}