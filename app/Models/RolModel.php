<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * RolModel
 *
 * Modelo para gestionar los roles del sistema
 * Los roles definen qué puede hacer cada tipo de usuario
 */
class RolModel extends Model
{
    // ============================================
    // CONFIGURACIÓN BÁSICA DEL MODELO
    // ============================================

    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'nombre_rol',
        'descripcion'
    ];

    // ============================================
    // CONFIGURACIÓN DE TIMESTAMPS
    // ============================================

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_creacion';
    protected $updatedField = '';

    // ============================================
    // REGLAS DE VALIDACIÓN
    // ============================================

    protected $validationRules = [
        'nombre_rol' => 'required|min_length[3]|max_length[50]|is_unique[roles.nombre_rol,id_rol,{id_rol}]',
        'descripcion' => 'max_length[255]'
    ];

    protected $validationMessages = [
        'nombre_rol' => [
            'required' => 'El nombre del rol es obligatorio',
            'min_length' => 'El nombre del rol debe tener al menos 3 caracteres',
            'max_length' => 'El nombre del rol no puede exceder 50 caracteres',
            'is_unique' => 'Este nombre de rol ya existe'
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede exceder 255 caracteres'
        ]
    ];

    // ============================================
    // MÉTODOS PERSONALIZADOS
    // ============================================

    /**
     * Obtener un rol por su nombre
     *
     * @param string $nombreRol Nombre del rol a buscar
     * @return array|null Datos del rol o null si no existe
     */
    public function obtenerPorNombre($nombreRol)
    {
        return $this->where('nombre_rol', $nombreRol)->first();
    }

    /**
     * Obtener todos los roles activos
     *
     * @return array Lista de roles
     */
    public function obtenerTodos()
    {
        return $this->orderBy('nombre_rol', 'ASC')->findAll();
    }

    /**
     * Verificar si un rol existe por su ID
     *
     * @param int $idRol ID del rol
     * @return bool True si existe, false si no
     */
    public function existeRol($idRol)
    {
        return $this->find($idRol) !== null;
    }

    /**
     * Obtener el ID de un rol por su nombre
     * Útil para migraciones y asignaciones
     *
     * @param string $nombreRol Nombre del rol
     * @return int|null ID del rol o null si no existe
     */
    public function obtenerIdPorNombre($nombreRol)
    {
        $rol = $this->obtenerPorNombre($nombreRol);
        return $rol ? $rol['id_rol'] : null;
    }

    /**
     * Contar usuarios por rol
     *
     * @param int $idRol ID del rol
     * @return int Cantidad de usuarios con este rol
     */
    public function contarUsuarios($idRol)
    {
        $db = \Config\Database::connect();
        return $db->table('usuarios')
                  ->where('id_rol', $idRol)
                  ->countAllResults();
    }
}
