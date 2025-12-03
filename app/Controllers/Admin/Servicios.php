<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServicioModel;

class Servicios extends BaseController
{
    protected $servicioModel;

    public function __construct()
    {
        $this->servicioModel = new ServicioModel();
        helper(['form', 'url']);
    }

    /**
     * Listar todos los servicios con buscador
     */
    public function index()
    {
        $buscar = $this->request->getGet('buscar');

        $builder = $this->servicioModel->orderBy('id_servicio', 'DESC');

        // Aplicar búsqueda si existe
        if ($buscar) {
            $builder->groupStart()
                    ->like('nombre', $buscar)
                    ->orLike('descripcion', $buscar)
                    ->groupEnd();
        }

        $data = [
            'titulo' => 'Gestión de Servicios',
            'servicios' => $builder->findAll(),
            'buscar' => $buscar
        ];

        return view('admin/servicios/index', $data);
    }

    /**
     * Mostrar formulario para crear servicio
     */
    public function crear()
    {
        $data = [
            'titulo' => 'Crear Nuevo Servicio'
        ];

        return view('admin/servicios/crear', $data);
    }

    /**
     * Guardar nuevo servicio
     */
    public function guardar()
    {
        // Validación
        $reglas = [
            'nombre' => 'required|min_length[3]|max_length[100]|is_unique[servicios.nombre]',
            'descripcion' => 'permit_empty',
            'precio' => 'required|decimal|greater_than[0]',
            'duracion_minutos' => 'required|integer|greater_than[0]|in_list[20,40,60,80,100,120,140,160,180]',
            'activo' => 'required|in_list[0,1]'
        ];

        $mensajes = [
            'nombre' => [
                'required' => 'El nombre del servicio es obligatorio',
                'min_length' => 'El nombre debe tener al menos 3 caracteres',
                'is_unique' => 'Ya existe un servicio con ese nombre'
            ],
            'precio' => [
                'required' => 'El precio es obligatorio',
                'decimal' => 'El precio debe ser un número válido',
                'greater_than' => 'El precio debe ser mayor a 0'
            ],
            'duracion_minutos' => [
                'required' => 'La duración es obligatoria',
                'integer' => 'La duración debe ser un número entero',
                'greater_than' => 'La duración debe ser mayor a 0',
                'in_list' => 'La duración debe ser en incrementos de 20 minutos (20, 40, 60, etc.)'
            ]
        ];

        if (!$this->validate($reglas, $mensajes)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
            'duracion_minutos' => $this->request->getPost('duracion_minutos'),
            'activo' => $this->request->getPost('activo')
        ];

        if ($this->servicioModel->insert($datos)) {
            return redirect()->to('/admin/servicios')
                           ->with('success', 'Servicio creado exitosamente');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear el servicio');
        }
    }

    /**
     * Mostrar formulario para editar servicio
     */
    public function editar($id)
    {
        $servicio = $this->servicioModel->find($id);

        if (!$servicio) {
            return redirect()->to('/admin/servicios')
                           ->with('error', 'Servicio no encontrado');
        }

        $data = [
            'titulo' => 'Editar Servicio',
            'servicio' => $servicio
        ];

        return view('admin/servicios/editar', $data);
    }

    /**
     * Actualizar servicio
     */
    public function actualizar($id)
    {
        $servicio = $this->servicioModel->find($id);

        if (!$servicio) {
            return redirect()->to('/admin/servicios')
                           ->with('error', 'Servicio no encontrado');
        }

        // Validación
        $nombreActual = $servicio['nombre'];
        $nombreNuevo = trim($this->request->getPost('nombre'));

        // Solo validar is_unique si el nombre cambió
        if (strtolower($nombreActual) === strtolower($nombreNuevo)) {
            $reglaNombre = 'required|min_length[3]|max_length[100]';
        } else {
            $reglaNombre = 'required|min_length[3]|max_length[100]|is_unique[servicios.nombre]';
        }

        $reglas = [
            'nombre' => $reglaNombre,
            'descripcion' => 'permit_empty',
            'precio' => 'required|decimal|greater_than[0]',
            'duracion_minutos' => 'required|integer|greater_than[0]|in_list[20,40,60,80,100,120,140,160,180]',
            'activo' => 'required|in_list[0,1]'
        ];

        $mensajes = [
            'nombre' => [
                'required' => 'El nombre del servicio es obligatorio',
                'min_length' => 'El nombre debe tener al menos 3 caracteres',
                'is_unique' => 'Ya existe un servicio con ese nombre'
            ],
            'precio' => [
                'required' => 'El precio es obligatorio',
                'decimal' => 'El precio debe ser un número válido',
                'greater_than' => 'El precio debe ser mayor a 0'
            ],
            'duracion_minutos' => [
                'required' => 'La duración es obligatoria',
                'integer' => 'La duración debe ser un número entero',
                'greater_than' => 'La duración debe ser mayor a 0',
                'in_list' => 'La duración debe ser en incrementos de 20 minutos (20, 40, 60, etc.)'
            ]
        ];

        if (!$this->validate($reglas, $mensajes)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
            'duracion_minutos' => $this->request->getPost('duracion_minutos'),
            'activo' => $this->request->getPost('activo')
        ];

        if ($this->servicioModel->update($id, $datos)) {
            return redirect()->to('/admin/servicios')
                           ->with('success', 'Servicio actualizado exitosamente');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar el servicio');
        }
    }

    /**
     * Eliminar servicio
     */
    public function eliminar($id)
    {
        $servicio = $this->servicioModel->find($id);

        if (!$servicio) {
            return redirect()->to('/admin/servicios')
                           ->with('error', 'Servicio no encontrado');
        }

        if ($this->servicioModel->delete($id)) {
            return redirect()->to('/admin/servicios')
                           ->with('success', 'Servicio eliminado exitosamente');
        } else {
            return redirect()->to('/admin/servicios')
                           ->with('error', 'Error al eliminar el servicio');
        }
    }
}
