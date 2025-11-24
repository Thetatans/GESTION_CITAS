<?php

// Definir el espacio de nombres para organizar el código
namespace App\Models;

// Importar la clase Model de CodeIgniter
use CodeIgniter\Model;

/**
 * AdministradorModel
 *
 * Modelo para gestionar el perfil extendido de los administradores
 *
 * Este modelo trabaja en conjunto con UsuarioModel:
 * - UsuarioModel: Maneja credenciales y autenticación (email, password, rol)
 * - AdministradorModel: Maneja información adicional del administrador (nombre, cargo, etc.)
 *
 * Relación con la base de datos:
 * - Tabla: administradores
 * - Relación: 1:1 con tabla usuarios (id_usuario es clave foránea)
 *
 * Funcionalidad:
 * - Almacenar datos personales del administrador
 * - Validar información antes de guardar
 * - Actualizar timestamps automáticamente
 */
class AdministradorModel extends Model
{
    // ============================================
    // CONFIGURACIÓN BÁSICA DEL MODELO
    // ============================================

    // Nombre de la tabla en la base de datos
    protected $table            = 'administradores';

    // Columna que actúa como clave primaria
    protected $primaryKey       = 'id_admin';

    // Usar autoincremento para la clave primaria
    protected $useAutoIncrement = true;

    // Tipo de dato que retorna (array o object)
    // array es más común y fácil de usar
    protected $returnType       = 'array';

    // No usar soft deletes (eliminación suave)
    // Si se activa, los registros se marcan como eliminados pero no se borran
    protected $useSoftDeletes   = false;

    // Proteger campos: solo se pueden modificar los campos permitidos
    protected $protectFields    = true;

    // Campos que se pueden insertar/actualizar de forma masiva
    // Esto previene ataques de asignación masiva
    protected $allowedFields    = [
        'id_usuario',           // FK hacia tabla usuarios (obligatorio)
        'nombre',              // Nombre del administrador
        'apellido',            // Apellido del administrador
        'telefono',            // Teléfono de contacto
        'cargo'                // Cargo o posición (ej: "Gerente General")
    ];

    // ============================================
    // CONFIGURACIÓN DE TIMESTAMPS
    // ============================================

    // Usar timestamps automáticos
    // Actualiza created_at al crear y updated_at al actualizar
    protected $useTimestamps = true;

    // Formato de fecha (datetime, date, int)
    protected $dateFormat    = 'datetime';

    // Campo que almacena la fecha de creación
    protected $createdField  = 'created_at';

    // Campo que almacena la fecha de última actualización
    protected $updatedField  = 'updated_at';

    // ============================================
    // REGLAS DE VALIDACIÓN
    // ============================================

    // Reglas que se aplican al insertar o actualizar
    protected $validationRules = [
        // id_usuario: obligatorio, debe ser entero
        // Este campo vincula el administrador con su cuenta de usuario
        'id_usuario' => 'required|integer',

        // nombre: obligatorio, mínimo 3 caracteres, máximo 100
        'nombre'     => 'required|min_length[3]|max_length[100]',

        // apellido: obligatorio, mínimo 3 caracteres, máximo 100
        'apellido'   => 'required|min_length[3]|max_length[100]',
    ];

    // Mensajes personalizados de error para cada regla
    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
        ],
        'apellido' => [
            'required' => 'El apellido es obligatorio',
        ],
    ];

    // ============================================
    // MÉTODOS PERSONALIZADOS
    // ============================================
    // Puedes agregar métodos personalizados aquí según necesites
    // Por ejemplo:
    // - obtenerPorUsuario($id_usuario)
    // - buscarPorCargo($cargo)
    // - listarActivos()
}
