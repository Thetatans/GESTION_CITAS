<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClienteModel;
use App\Models\UsuarioModel;
use App\Models\RolModel;

class Clientes extends BaseController
{
    protected $clienteModel;
    protected $usuarioModel;
    protected $rolModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
        $this->usuarioModel = new UsuarioModel();
        $this->rolModel = new RolModel();
        helper(['form', 'url']);
    }

    /**
     * Listar todos los clientes con buscador
     */
    public function index()
    {
        $buscar = $this->request->getGet('buscar');

        // Obtener clientes con información del usuario
        $builder = $this->clienteModel
            ->select('clientes.*, usuarios.email, usuarios.estado as usuario_estado')
            ->join('usuarios', 'usuarios.id_usuario = clientes.id_usuario')
            ->orderBy('clientes.id_cliente', 'DESC');

        // Aplicar búsqueda si existe
        if ($buscar) {
            $builder->groupStart()
                    ->like('clientes.nombre', $buscar)
                    ->orLike('clientes.apellido', $buscar)
                    ->orLike('clientes.telefono', $buscar)
                    ->orLike('usuarios.email', $buscar)
                    ->groupEnd();
        }

        $data = [
            'titulo' => 'Gestión de Clientes',
            'clientes' => $builder->findAll(),
            'buscar' => $buscar
        ];

        return view('admin/clientes/index', $data);
    }

    /**
     * Mostrar formulario para crear cliente
     */
    public function crear()
    {
        $data = [
            'titulo' => 'Registrar Nuevo Cliente'
        ];

        return view('admin/clientes/crear', $data);
    }

    /**
     * Guardar nuevo cliente
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
            'fecha_nacimiento' => 'permit_empty|valid_date',
            'genero' => 'permit_empty|in_list[Masculino,Femenino,Otro]'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Obtener rol de cliente
            $rolCliente = $this->rolModel->obtenerPorNombre('cliente');

            // Crear usuario
            $datosUsuario = [
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'estado' => 'activo'
            ];

            if ($rolCliente) {
                $datosUsuario['id_rol'] = $rolCliente['id_rol'];
            } else {
                $datosUsuario['rol'] = 'cliente';
                $datosUsuario['activo'] = 1;
            }

            if (!$this->usuarioModel->insert($datosUsuario)) {
                throw new \Exception('Error al crear el usuario');
            }

            $id_usuario = $this->usuarioModel->getInsertID();

            // Crear cliente
            $datosCliente = [
                'id_usuario' => $id_usuario,
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'telefono' => $this->request->getPost('telefono'),
                'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento') ?: null,
                'genero' => $this->request->getPost('genero') ?: null,
                'direccion' => $this->request->getPost('direccion') ?: null,
            ];

            if (!$this->clienteModel->insert($datosCliente)) {
                throw new \Exception('Error al crear el cliente');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error al completar la transacción');
            }

            return redirect()->to('/admin/clientes')
                           ->with('success', 'Cliente registrado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar cliente
     */
    public function editar($id)
    {
        $cliente = $this->clienteModel
            ->select('clientes.*, usuarios.email')
            ->join('usuarios', 'usuarios.id_usuario = clientes.id_usuario')
            ->where('clientes.id_cliente', $id)
            ->first();

        if (!$cliente) {
            return redirect()->to('/admin/clientes')
                           ->with('error', 'Cliente no encontrado');
        }

        $data = [
            'titulo' => 'Editar Cliente',
            'cliente' => $cliente
        ];

        return view('admin/clientes/editar', $data);
    }

    /**
     * Actualizar cliente
     */
    public function actualizar($id)
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente) {
            return redirect()->to('/admin/clientes')
                           ->with('error', 'Cliente no encontrado');
        }

        // Obtener email actual del usuario
        $usuario = $this->usuarioModel->find($cliente['id_usuario']);
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
            'fecha_nacimiento' => 'permit_empty|valid_date',
            'genero' => 'permit_empty|in_list[Masculino,Femenino,Otro]'
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
            if (!$this->usuarioModel->update($cliente['id_usuario'], $datosUsuario)) {
                $errores = $this->usuarioModel->errors();
                $mensajeError = !empty($errores) ? implode(', ', $errores) : 'Error desconocido al actualizar el usuario';
                throw new \Exception($mensajeError);
            }
            $this->usuarioModel->skipValidation(false);

            // Actualizar cliente
            $datosCliente = [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'telefono' => $this->request->getPost('telefono'),
                'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento') ?: null,
                'genero' => $this->request->getPost('genero') ?: null,
                'direccion' => $this->request->getPost('direccion') ?: null,
            ];

            if (!$this->clienteModel->update($id, $datosCliente)) {
                throw new \Exception('Error al actualizar el cliente');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error al completar la transacción');
            }

            return redirect()->to('/admin/clientes')
                           ->with('success', 'Cliente actualizado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar cliente
     */
    public function eliminar($id)
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente) {
            return redirect()->to('/admin/clientes')
                           ->with('error', 'Cliente no encontrado');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Eliminar cliente
            if (!$this->clienteModel->delete($id)) {
                throw new \Exception('Error al eliminar el cliente');
            }

            // Eliminar usuario asociado
            if (!$this->usuarioModel->delete($cliente['id_usuario'])) {
                throw new \Exception('Error al eliminar el usuario');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error al completar la transacción');
            }

            return redirect()->to('/admin/clientes')
                           ->with('success', 'Cliente eliminado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/admin/clientes')
                           ->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }
}
