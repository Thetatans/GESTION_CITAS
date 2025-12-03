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
        'id_rol',         // ID del rol (relación con tabla roles)
        'estado',         // Estado del usuario (activo, inactivo, suspendido, despedido)
        'ultimo_acceso',  // Fecha del último login
        // Campos antiguos (mantener para compatibilidad durante migración)
        'rol',            // Tipo de usuario (antiguo)
        'activo'          // Estado del usuario (antiguo)
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

        // Password: opcional, pero si se proporciona debe tener mínimo 8 caracteres
        'password' => 'permit_empty|min_length[8]',

        // ID Rol: obligatorio, debe existir en la tabla roles
        'id_rol' => 'permit_empty|integer|is_not_unique[roles.id_rol]',

        // Estado: obligatorio, debe ser uno de los valores permitidos
        'estado' => 'permit_empty|in_list[activo,inactivo,suspendido,despedido]',

        // Campos antiguos (mantener para compatibilidad durante migración)
        'rol' => 'permit_empty|in_list[admin,empleado,cliente]',
        'activo' => 'permit_empty|integer'
    ];

    // Mensajes personalizados de error para cada regla
    protected $validationMessages = [
        'email' => [
            'required'    => 'El email es obligatorio',
            'valid_email' => 'Debe ser un email válido',
            'is_unique'   => 'Este email ya está registrado',
        ],
        'password' => [
            'min_length' => 'La contraseña debe tener al menos 8 caracteres',
        ],
        'id_rol' => [
            'integer' => 'El ID del rol debe ser un número',
            'is_not_unique' => 'El rol seleccionado no existe',
        ],
        'estado' => [
            'in_list' => 'Estado inválido. Debe ser: activo, inactivo, suspendido o despedido',
        ],
        'rol' => [
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
            // Si el password está vacío, eliminarlo del array para no actualizarlo
            if (empty($data['data']['password'])) {
                unset($data['data']['password']);
            } else {
                // Hashear la contraseña usando el algoritmo por defecto (bcrypt)
                // PASSWORD_DEFAULT usa bcrypt que es muy seguro
                $data['data']['password'] = password_hash(
                    $data['data']['password'],
                    PASSWORD_DEFAULT
                );
            }
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
     * Este método verifica email, contraseña y estado del usuario
     * Retorna el usuario si las credenciales son correctas y está activo
     *
     * @param string $email Email ingresado
     * @param string $password Contraseña en texto plano ingresada
     * @return array|false Datos del usuario con información de estado
     */
    public function verificarCredenciales($email, $password)
    {
        // Paso 1: Buscar usuario por email con información del rol
        $usuario = $this->select('usuarios.*, roles.nombre_rol')
                        ->join('roles', 'roles.id_rol = usuarios.id_rol', 'left')
                        ->where('usuarios.email', $email)
                        ->first();

        // Si no existe el usuario, retornar false
        if (!$usuario) {
            return false;
        }

        // Paso 2: Verificar la contraseña ANTES de verificar el estado
        // Esto evita revelar si el usuario existe o no
        if (!password_verify($password, $usuario['password'])) {
            return false;
        }

        // Paso 3: Verificar el estado del usuario
        // IMPORTANTE: Verificar AMBOS campos (nuevo y antiguo) para máxima seguridad

        // A) Verificar campo 'estado' (sistema nuevo)
        if (isset($usuario['estado']) && $usuario['estado'] !== 'activo') {
            // Si el estado NO es 'activo', bloquear acceso
            return [
                'error' => 'usuario_inactivo',
                'estado' => $usuario['estado'],
                'email' => $usuario['email']
            ];
        }

        // B) Verificar campo 'activo' (sistema antiguo)
        // Esto se verifica ADEMÁS del campo estado, no en lugar de
        if (isset($usuario['activo']) && $usuario['activo'] == 0) {
            return [
                'error' => 'usuario_inactivo',
                'estado' => 'inactivo',
                'email' => $usuario['email']
            ];
        }

        // Paso 4: Si todo está correcto, retornar datos del usuario
        return $usuario;
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

    // ============================================
    // MÉTODOS NUEVOS PARA SISTEMA DE ROLES
    // ============================================

    /**
     * Obtener usuario con información completa del rol
     *
     * @param int $id_usuario ID del usuario
     * @return array|null Datos del usuario con información del rol
     */
    public function obtenerConRol($id_usuario)
    {
        return $this->select('usuarios.*, roles.nombre_rol, roles.descripcion as rol_descripcion')
                    ->join('roles', 'roles.id_rol = usuarios.id_rol', 'left')
                    ->where('usuarios.id_usuario', $id_usuario)
                    ->first();
    }

    /**
     * Obtener todos los usuarios con información del rol
     *
     * @return array Lista de usuarios con sus roles
     */
    public function obtenerTodosConRol()
    {
        return $this->select('usuarios.*, roles.nombre_rol')
                    ->join('roles', 'roles.id_rol = usuarios.id_rol', 'left')
                    ->orderBy('usuarios.id_usuario', 'DESC')
                    ->findAll();
    }

    /**
     * Obtener usuarios por ID de rol
     *
     * @param int $id_rol ID del rol
     * @param bool $soloActivos Si true, solo retorna usuarios activos
     * @return array Lista de usuarios
     */
    public function obtenerPorIdRol($id_rol, $soloActivos = true)
    {
        $builder = $this->where('id_rol', $id_rol);

        if ($soloActivos) {
            $builder->where('estado', 'activo');
        }

        return $builder->findAll();
    }

    /**
     * Cambiar el estado de un usuario
     *
     * @param int $id_usuario ID del usuario
     * @param string $nuevoEstado Nuevo estado (activo, inactivo, suspendido, despedido)
     * @return bool True si se cambió, false si falló
     */
    public function cambiarEstado($id_usuario, $nuevoEstado)
    {
        $estadosValidos = ['activo', 'inactivo', 'suspendido', 'despedido'];

        if (!in_array($nuevoEstado, $estadosValidos)) {
            return false;
        }

        return $this->update($id_usuario, ['estado' => $nuevoEstado]);
    }

    /**
     * Obtener nombre del rol de un usuario (compatibilidad)
     * Funciona tanto con el sistema nuevo como con el antiguo
     *
     * @param int $id_usuario ID del usuario
     * @return string|null Nombre del rol
     */
    public function obtenerNombreRol($id_usuario)
    {
        $usuario = $this->obtenerConRol($id_usuario);

        if (!$usuario) {
            return null;
        }

        // Sistema nuevo: usa nombre_rol de la tabla roles
        if (isset($usuario['nombre_rol'])) {
            return $usuario['nombre_rol'];
        }

        // Sistema antiguo: usa el campo 'rol' directamente
        if (isset($usuario['rol'])) {
            // Convertir nombres antiguos a nuevos
            $mapeo = [
                'admin' => 'administrador',
                'empleado' => 'empleado',
                'cliente' => 'cliente'
            ];
            return $mapeo[$usuario['rol']] ?? $usuario['rol'];
        }

        return null;
    }
}