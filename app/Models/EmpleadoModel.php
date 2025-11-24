<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoModel extends Model
{
    protected $table            = 'empleados';
    protected $primaryKey       = 'id_empleado';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_usuario',
        'nombre',
        'apellido',
        'telefono',
        'especialidad',
        'comision_porcentaje',
        'fecha_contratacion'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id_usuario' => 'required|integer',
        'nombre'     => 'required|min_length[3]|max_length[100]',
        'apellido'   => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
        ],
        'apellido' => [
            'required' => 'El apellido es obligatorio',
        ],
    ];
}