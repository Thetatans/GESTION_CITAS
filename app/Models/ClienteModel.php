<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'id_cliente';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
    'id_usuario',
    'nombre',
    'apellido',
    'telefono',
    'fecha_nacimiento',
    'genero',
    'direccion'
];

    // Desactivar timestamps si la tabla no tiene created_at y updated_at
    protected $useTimestamps = false;
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