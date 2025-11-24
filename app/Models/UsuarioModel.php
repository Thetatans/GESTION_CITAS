<?php

// Definir el espacio de nombres para organizar el código
namespace App\Models;

// Importar la clase Model de CodeIgniter
use CodeIgniter\Model;

/**
 * UsuarioModel
 * 
 * Modelo para gestionar usuarios del sistema
 * Maneja operaciones CRUD y autenticación
 */
class UsuarioModel extends Model
{
    // ============================================
    // CONFIGURACIÓN BÁSICA DEL MODELO
    // ============================================
    
    // Nombre de la tabla en la base de datos
    protected $table = 'usuarios';
    
    // Columna que actúa como clave primaria
    protected $primaryKey = 'id_usuario';
    
    // Usar autoincremento para la clave primaria
    protected $useAutoIncrement = true;
    
    // Tipo de dato que retorna (array o object)
    // array es más común y fácil de usar
    protected $returnType = 'array';
    
    // No usar soft deletes (eliminación suave)
    // Si se activa, los registros se marcan como eliminados pero no se borran
    protected $useSoftDeletes = false;
    
    // Proteger campos: solo se pueden modificar los campos permitidos
    protected $protectFields = true;
    
    // Campos que se pueden insertar/actualizar de forma masiva
    // Esto previene ataques de asignación masiva
    protected $allowedFields = [
        'email',          // Correo electrónico
        'password',       // Contraseña (se hashea automáticamente)
        'rol',           // Tipo de usuario
        'activo',        // Estado del usuario
        'ultimo_acceso'  // Fecha del último login
    ];

    // ============================================
    // CONFIGURACIÓN DE TIMESTAMPS
    // ============================================
    
    // No usar timestamps automáticos de CodeIgniter
    // porque ya tenemos fecha_registro con DEFAULT CURRENT_TIMESTAMP
    protected $useTimestamps = false;
    
    // Formato de fecha (datetime, date, int)
    protected $dateFormat = 'datetime';
    
    // Campo que almacena la fecha de creación
    protected $createdField = 'fecha_registro';
    
    // No hay campo de actualización
    protected $updatedField = '';

    // ============================================
    // REGLAS DE VALIDACIÓN
    // ============================================
    
    // Reglas que se aplican al insertar o actualizar
    protected $validationRules = [
        // Email: obligatorio, formato válido, único en la tabla
        // {id_usuario} permite actualizar el mismo usuario sin error de duplicado
        'email' => 'required|valid_email|is_unique[usuarios.email,id_usuario,{id_usuario}]',
        
        // Password: obligatorio, mínimo 8 caracteres
        'password' => 'required|min_length[8]',
        
        // Rol: obligatorio, debe ser uno de los valores permitidos
        'rol' => 'required|in_list[admin,empleado,cliente]',
    ];

    // Mensajes personalizados de error para cada regla
    protected $validationMessages = [
        'email' => [
            'required'    => 'El email es obligatorio',
            'valid_email' => 'Debe ser un email válido',
            'is_unique'   => 'Este email ya está registrado',
        ],
        'password' => [
            'required'   => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 8 caracteres',
        ],
        'rol' => [
            'required' => 'El rol es obligatorio',
            'in_list'  => 'Rol inválido',
        ],
    ];

    // ============================================
    // CALLBACKS (FUNCIONES AUTOMÁTICAS)
    // ============================================
    
    // Funciones que se ejecutan automáticamente antes de insertar
    protected $beforeInsert = ['hashPassword'];
    
    // Funciones que se ejecutan automáticamente antes de actualizar
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hashear contraseña antes de guardar en base de datos
     * 
     * IMPORTANTE: Nunca guardar contraseñas en texto plano
     * Se usa password_hash() que genera un hash seguro
     * 
     * @param array $data Datos que se van a insertar/actualizar
     * @return array Datos modificados con password hasheado
     */
    protected function hashPassword(array $data)
    {
        // Verificar si se está intentando modificar la contraseña
        if (isset($data['data']['password'])) {
            // Hashear la contraseña usando el algoritmo por defecto (bcrypt)
            // PASSWORD_DEFAULT usa bcrypt que es muy seguro
            $data['data']['password'] = password_hash(
                $data['data']['password'], 
                PASSWORD_DEFAULT
            );
        }
        
        // Retornar los datos modificados
        return $data;
    }

    // ============================================
    // MÉTODOS PERSONALIZADOS
    // ============================================

    /**
     * Buscar un usuario por su email
     * 
     * @param string $email Email del usuario a buscar
     * @return array|null Datos del usuario o null si no existe
     */
    public function buscarPorEmail($email)
    {
        // Buscar en la tabla donde el email coincida
        // first() retorna solo el primer resultado (o null)
        return $this->where('email', $email)->first();
    }

    /**
     * Verificar credenciales de login
     * 
     * Este método verifica email y contraseña
     * y retorna el usuario si las credenciales son correctas
     * 
     * @param string $email Email ingresado
     * @param string $password Contraseña en texto plano ingresada
     * @return array|false Datos del usuario si es válido, false si no
     */
    public function verificarCredenciales($email, $password)
    {
        // Paso 1: Buscar usuario por email
        $usuario = $this->buscarPorEmail($email);

        // Si no existe el usuario, retornar false
        if (!$usuario) {
            return false;
        }

        // Paso 2: Verificar que el usuario esté activo
        // No permitir login a usuarios inactivos
        if (!$usuario['activo']) {
            return false;
        }

        // Paso 3: Verificar la contraseña
        // password_verify() compara la contraseña ingresada con el hash
        // Esta función es segura contra timing attacks
        if (password_verify($password, $usuario['password'])) {
            // Si la contraseña es correcta, retornar datos del usuario
            return $usuario;
        }

        // Si la contraseña no coincide, retornar false
        return false;
    }

    /**
     * Actualizar la fecha del último acceso
     * 
     * Se llama cada vez que el usuario inicia sesión exitosamente
     * Útil para estadísticas y auditoría
     * 
     * @param int $id_usuario ID del usuario
     * @return bool True si se actualizó, false si falló
     */
    public function actualizarUltimoAcceso($id_usuario)
    {
        // Actualizar el campo ultimo_acceso con la fecha actual
        return $this->update($id_usuario, [
            'ultimo_acceso' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Verificar si un usuario es administrador
     * 
     * @param int $id_usuario ID del usuario
     * @return bool True si es admin, false si no
     */
    public function esAdmin($id_usuario)
    {
        // Buscar el usuario por ID
        $usuario = $this->find($id_usuario);
        
        // Retornar true solo si existe y su rol es 'admin'
        return $usuario && $usuario['rol'] === 'admin';
    }

    /**
     * Verificar si un usuario es empleado
     * 
     * @param int $id_usuario ID del usuario
     * @return bool True si es empleado, false si no
     */
    public function esEmpleado($id_usuario)
    {
        $usuario = $this->find($id_usuario);
        return $usuario && $usuario['rol'] === 'empleado';
    }

    /**
     * Verificar si un usuario es cliente
     * 
     * @param int $id_usuario ID del usuario
     * @return bool True si es cliente, false si no
     */
    public function esCliente($id_usuario)
    {
        $usuario = $this->find($id_usuario);
        return $usuario && $usuario['rol'] === 'cliente';
    }

    /**
     * Obtener todos los usuarios de un rol específico
     * 
     * Útil para listar empleados, clientes, etc.
     * Solo retorna usuarios activos
     * 
     * @param string $rol Rol a filtrar (admin, empleado, cliente)
     * @return array Lista de usuarios
     */
    public function obtenerPorRol($rol)
    {
        return $this->where('rol', $rol)      // Filtrar por rol
                    ->where('activo', 1)       // Solo activos
                    ->findAll();               // Obtener todos
    }

    /**
     * Cambiar la contraseña de un usuario
     * 
     * @param int $id_usuario ID del usuario
     * @param string $nueva_password Nueva contraseña en texto plano
     * @return bool True si se cambió, false si falló
     */
    public function cambiarPassword($id_usuario, $nueva_password)
    {
        // Actualizar solo el campo password
        // El callback hashPassword() se encargará de hashearla
        return $this->update($id_usuario, [
            'password' => password_hash($nueva_password, PASSWORD_DEFAULT)
        ]);
    }
}