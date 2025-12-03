<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmpleadoModel;
use App\Models\UsuarioModel;
use App\Models\RolModel;

class Empleados extends BaseController
{
    protected $empleadoModel;
    protected $usuarioModel;
    protected $rolModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->rolModel = new RolModel();
        helper(['form', 'url']);
    }

    /**
     * Listar todos los empleados con buscador
     */
    public function index()
    {
        $buscar = $this->request->getGet('buscar');

        // Obtener empleados con información del usuario
        $builder = $this->empleadoModel
            ->select('empleados.*, usuarios.email, usuarios.estado as usuario_estado')
            ->join('usuarios', 'usuarios.id_usuario = empleados.id_usuario')
            ->orderBy('empleados.id_empleado', 'DESC');

        // Aplicar búsqueda si existe
        if ($buscar) {
            $builder->groupStart()
                    ->like('empleados.nombre', $buscar)
                    ->orLike('empleados.apellido', $buscar)
                    ->orLike('empleados.telefono', $buscar)
                    ->orLike('empleados.especialidad', $buscar)
                    ->orLike('usuarios.email', $buscar)
                    ->groupEnd();
        }

        $data = [
            'titulo' => 'Gestión de Empleados',
            'empleados' => $builder->findAll(),
            'buscar' => $buscar
        ];

        return view('admin/empleados/index', $data);
    }

    /**
     * Mostrar formulario para crear empleado
     */
    public function crear()
    {
        $data = [
            'titulo' => 'Registrar Nuevo Empleado'
        ];

        return view('admin/empleados/crear', $data);
    }

    /**
     * Guardar nuevo empleado
     */
    public function guardar()
    {
        // Validación
        $reglas = [
            'email' => 'required|valid_email|is_unique[usuarios.email]',
            'password' => 'required|min_length[8]',
            'nombre' => 'required|min_length[3]|max_length[100]',
            'apellido' => 'required|min_length[3]|max_length[100]',
            'telefono' => 'required|min_length[7]|max_length[20]',
            'especialidad' => 'permit_empty|max_length[100]',
            'comision_porcentaje' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
            'fecha_contratacion' => 'permit_empty|valid_date'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Obtener rol de empleado
            $rolEmpleado = $this->rolModel->obtenerPorNombre('empleado');

            // Crear usuario
            $datosUsuario = [
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'estado' => 'activo'
            ];

            if ($rolEmpleado) {
                $datosUsuario['id_rol'] = $rolEmpleado['id_rol'];
            } else {
                $datosUsuario['rol'] = 'empleado';
                $datosUsuario['activo'] = 1;
            }

            if (!$this->usuarioModel->insert($datosUsuario)) {
                throw new \Exception('Error al crear el usuario');
            }

            $id_usuario = $this->usuarioModel->getInsertID();

            // Crear empleado
            $datosEmpleado = [
                'id_usuario' => $id_usuario,
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'telefono' => $this->request->getPost('telefono'),
                'especialidad' => $this->request->getPost('especialidad') ?: null,
                'comision_porcentaje' => $this->request->getPost('comision_porcentaje') ?: 0.00,
                'fecha_contratacion' => $this->request->getPost('fecha_contratacion') ?: date('Y-m-d'),
            ];

            if (!$this->empleadoModel->insert($datosEmpleado)) {
                throw new \Exception('Error al crear el empleado');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error al completar la transacción');
            }

            return redirect()->to('/admin/empleados')
                           ->with('success', 'Empleado registrado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear el empleado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar empleado
     */
    public function editar($id)
    {
        $empleado = $this->empleadoModel
            ->select('empleados.*, usuarios.email')
            ->join('usuarios', 'usuarios.id_usuario = empleados.id_usuario')
            ->where('empleados.id_empleado', $id)
            ->first();

        if (!$empleado) {
            return redirect()->to('/admin/empleados')
                           ->with('error', 'Empleado no encontrado');
        }

        $data = [
            'titulo' => 'Editar Empleado',
            'empleado' => $empleado
        ];

        return view('admin/empleados/editar', $data);
    }

    /**
     * Actualizar empleado
     */
    public function actualizar($id)
    {
        $empleado = $this->empleadoModel->find($id);

        if (!$empleado) {
            return redirect()->to('/admin/empleados')
                           ->with('error', 'Empleado no encontrado');
        }

        // Obtener email actual del usuario
        $usuario = $this->usuarioModel->find($empleado['id_usuario']);
        $emailActual = $usuario ? $usuario['email'] : '';
        $emailNuevo = trim($this->request->getPost('email'));

        // Solo validar is_unique si el email cambió
        if (strtolower($emailActual) === strtolower($emailNuevo)) {
            $reglaEmail = 'required|valid_email';
        } else {
            $reglaEmail = 'required|valid_email|is_unique[usuarios.email]';
        }

        $reglas = [
            'email' => $reglaEmail,
            'nombre' => 'required|min_length[3]|max_length[100]',
            'apellido' => 'required|min_length[3]|max_length[100]',
            'telefono' => 'required|min_length[7]|max_length[20]',
            'especialidad' => 'permit_empty|max_length[100]',
            'comision_porcentaje' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
            'fecha_contratacion' => 'permit_empty|valid_date'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Actualizar email del usuario
            $datosUsuario = [
                'email' => $this->request->getPost('email')
            ];

            // Si se proporcionó nueva contraseña
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                if (strlen($password) < 8) {
                    return redirect()->back()
                                   ->withInput()
                                   ->with('error', 'La contraseña debe tener al menos 8 caracteres');
                }
                $datosUsuario['password'] = $password;
            }

            // Desactivar validación del modelo temporalmente para evitar conflictos
            $this->usuarioModel->skipValidation(true);
            if (!$this->usuarioModel->update($empleado['id_usuario'], $datosUsuario)) {
                $errores = $this->usuarioModel->errors();
                $mensajeError = !empty($errores) ? implode(', ', $errores) : 'Error desconocido al actualizar el usuario';
                throw new \Exception($mensajeError);
            }
            $this->usuarioModel->skipValidation(false);

            // Actualizar empleado
            $datosEmpleado = [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'telefono' => $this->request->getPost('telefono'),
                'especialidad' => $this->request->getPost('especialidad') ?: null,
                'comision_porcentaje' => $this->request->getPost('comision_porcentaje') ?: 0.00,
                'fecha_contratacion' => $this->request->getPost('fecha_contratacion') ?: null,
            ];

            if (!$this->empleadoModel->update($id, $datosEmpleado)) {
                throw new \Exception('Error al actualizar el empleado');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error al completar la transacción');
            }

            return redirect()->to('/admin/empleados')
                           ->with('success', 'Empleado actualizado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar el empleado: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar empleado
     */
    public function eliminar($id)
    {
        $empleado = $this->empleadoModel->find($id);

        if (!$empleado) {
            return redirect()->to('/admin/empleados')
                           ->with('error', 'Empleado no encontrado');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Eliminar empleado
            if (!$this->empleadoModel->delete($id)) {
                throw new \Exception('Error al eliminar el empleado');
            }

            // Eliminar usuario asociado
            if (!$this->usuarioModel->delete($empleado['id_usuario'])) {
                throw new \Exception('Error al eliminar el usuario');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error al completar la transacción');
            }

            return redirect()->to('/admin/empleados')
                           ->with('success', 'Empleado eliminado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/admin/empleados')
                           ->with('error', 'Error al eliminar el empleado: ' . $e->getMessage());
        }
    }
}
