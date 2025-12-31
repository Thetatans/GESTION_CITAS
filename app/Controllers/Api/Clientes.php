<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ClienteModel;

class Clientes extends BaseController
{
    protected $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    /**
     * Listar todos los clientes (para uso en AJAX/API)
     */
    public function listar()
    {
        $clientes = $this->clienteModel->findAll();

        return $this->response->setJSON($clientes);
    }
}
