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
            --azul-hover: #152d47;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--beige);
        }

        .main-content {
            flex: 1;
        }

        .navbar {
            background: var(--azul-oscuro) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .btn-outline-light:hover {
            background: var(--cafe);
            border-color: var(--cafe);
        }

        h1 {
            color: var(--azul-oscuro);
            font-weight: 700;
        }

        hr {
            border-color: var(--cafe);
            opacity: 0.3;
            border-width: 2px;
        }

        .alert-success {
            background-color: white;
            border: 2px solid var(--azul-oscuro);
            border-left: 5px solid var(--cafe);
            color: var(--gris);
        }

        .alert-success h4 {
            color: var(--azul-oscuro);
        }

        .card {
            border: 2px solid var(--azul-oscuro);
            border-radius: 15px;
            overflow: hidden;
        }

        .card i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .card-custom-1 {
            background: var(--cafe);
            color: white;
        }

        .card-custom-2 {
            background: var(--azul-oscuro);
            color: white;
        }

        .card-custom-3 {
            background: var(--gris);
            color: white;
        }

        .card .btn-light {
            background: white;
            border: none;
            color: var(--azul-oscuro);
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 10px;
        }

        .card .btn-light:hover {
            background: var(--beige);
        }

        .card-header {
            background: var(--azul-oscuro) !important;
            color: white;
            font-weight: 600;
        }

        .list-group-item {
            border: 1px solid var(--azul-oscuro);
            color: var(--gris);
        }

        .list-group-item:hover {
            background: var(--beige);
        }

        .list-group-item i {
            color: var(--cafe);
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }

        footer {
            background: var(--azul-oscuro) !important;
            color: white;
        }

        footer .text-muted {
            color: rgba(255,255,255,0.7) !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>">
                <i class="bi bi-scissors"></i> Admin - Barbería
            </a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> <?= esc($usuario_nombre) ?>
                </span>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <h1><i ></i> <?= esc($titulo) ?></h1>
                    <hr>

                    <div class="alert alert-success">
                        <h4><i></i> ¡Bienvenido Administrador!</h4>
                        <p class="mb-0">Has iniciado sesión correctamente con rol de <strong>ADMINISTRADOR</strong>.</p>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"> Accesos Rápidos</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="<?= base_url('admin/clientes') ?>" class="list-group-item list-group-item-action">
                                            <i class="bi bi-people"></i> Gestionar Clientes
                                        </a>
                                        <a href="<?= base_url('admin/empleados') ?>" class="list-group-item list-group-item-action">
                                            <i class="bi bi-people-fill"></i> Gestionar Empleados
                                        </a>
                                        <a href="<?= base_url('admin/servicios') ?>" class="list-group-item list-group-item-action">
                                            <i class="bi bi-gear"></i> Gestionar Servicios
                                        </a>
                                        <a href="<?= base_url('admin/citas') ?>" class="list-group-item list-group-item-action">
                                            <i class="bi bi-calendar-check"></i> Ver Todas las Citas
                                        </a>
                                        <a href="<?= base_url('admin/reportes') ?>" class="list-group-item list-group-item-action">
                                            <i class="bi bi-graph-up"></i> Reportes y Estadísticas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <small class="text-muted">
                       <p><strong>Sistema de Gestión de Citas</strong></p>
                    <p>Barbería y Spa - Proyecto DICO TELECOMUNICACIONES</p>
                    <p>Desarrollado por: <strong>Ilich Esteban Reyes Botia</strong></p>
                    <p>Aprendiz SENA</p>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
