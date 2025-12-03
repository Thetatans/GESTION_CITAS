<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicioModel extends Model
{
    protected $table = 'servicios';
    protected $primaryKey = 'id_servicio';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'precio',
        'duracion_minutos',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'precio' => 'required|decimal|greater_than[0]',
        'duracion_minutos' => 'required|integer|greater_than[0]',
        'activo' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del servicio es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'precio' => [
            'required' => 'El precio es obligatorio',
            'decimal' => 'El precio debe ser un número válido',
            'greater_than' => 'El precio debe ser mayor a 0'
        ],
        'duracion_minutos' => [
            'required' => 'La duración es obligatoria',
            'integer' => 'La duración debe ser un número entero',
            'greater_than' => 'La duración debe ser mayor a 0 minutos'
        ]
    ];

    /**
     * Obtener servicios activos
     */
    public function obtenerActivos()
    {
        return $this->where('activo', 1)
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Cambiar estado del servicio
     */
    public function cambiarEstado($id, $activo)
    {
        return $this->update($id, ['activo' => $activo]);
    }
}
