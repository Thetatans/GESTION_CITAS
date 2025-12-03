<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --azul-oscuro: #1e3a5f;
            --cafe: #6b4423;
            --beige: #f5e6d3;
            --gris: #495057;
        }
        body { background: var(--beige); min-height: 100vh; }
        .navbar { background: var(--azul-oscuro) !important; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .navbar-brand { font-weight: 700; font-size: 1.3rem; }
        h1 { color: var(--azul-oscuro); font-weight: 700; }
        .btn-primary { background: var(--azul-oscuro); border-color: var(--azul-oscuro); }
        .btn-primary:hover { background: #152d47; border-color: #152d47; }
        .table { background: white; }
        .table thead { background: var(--azul-oscuro); color: white; }
        .badge { font-size: 0.85rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>">
                <i class="bi bi-scissors"></i> Admin - Barbería
            </a>
            <div class="d-flex">
                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-house"></i> Inicio
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1><i class="bi bi-people"></i> <?= esc($titulo) ?></h1>
                <hr>

                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle"></i> <?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i> <?= session('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <a href="<?= base_url('admin/clientes/crear') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Nuevo Cliente
                                </a>
                            </div>
                            <div class="col-md-6">
                                <form action="<?= base_url('admin/clientes') ?>" method="get" class="d-flex">
                                    <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar cliente..." value="<?= esc($buscar) ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    <?php if ($buscar): ?>
                                        <a href="<?= base_url('admin/clientes') ?>" class="btn btn-secondary ms-2">
                                            <i class="bi bi-x"></i>
                                        </a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre Completo</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($clientes)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No hay clientes registrados</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <tr>
                                                <td><?= esc($cliente['id_cliente']) ?></td>
                                                <td><?= esc(ucwords(strtolower($cliente['nombre'] . ' ' . $cliente['apellido']))) ?></td>
                                                <td><?= esc($cliente['email']) ?></td>
                                                <td><?= esc($cliente['telefono']) ?></td>
                                                <td>
                                                    <?php if ($cliente['usuario_estado'] == 'activo'): ?>
                                                        <span class="badge bg-success">Activo</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger"><?= ucfirst($cliente['usuario_estado']) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('admin/clientes/editar/' . $cliente['id_cliente']) ?>" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="<?= base_url('admin/clientes/eliminar/' . $cliente['id_cliente']) ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
