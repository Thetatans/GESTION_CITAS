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
            background: var(--cafe) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .btn-outline-light:hover {
            background: var(--azul-oscuro);
            border-color: var(--azul-oscuro);
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

        .alert-info {
            background-color: white;
            border: 2px solid var(--cafe);
            border-left: 5px solid var(--azul-oscuro);
            color: var(--gris);
        }

        .alert-info h4 {
            color: var(--cafe);
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
            background: var(--azul-oscuro);
            color: white;
        }

        .card-custom-2 {
            background: var(--cafe);
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
            background: var(--cafe) !important;
            color: white;
            font-weight: 600;
        }

        .card-body .text-muted {
            color: var(--gris) !important;
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
            <a class="navbar-brand" href="<?= base_url('cliente/dashboard') ?>">
                <i class="bi bi-person-circle"></i> Cliente - Barbería
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
            <h1><i ></i> <?= esc($titulo) ?></h1>
            <hr>

            <div class="alert alert-info">
                <h4><i></i> ¡Bienvenido!</h4>
                <p class="mb-0">Has iniciado sesión correctamente con rol de <strong>CLIENTE</strong>.</p>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="card card-custom-1">
                        <div class="card-body text-center">
                            <i ></i>
                            <h3>Agendar Cita</h3>
                            <a href="<?= base_url('cliente/agendar') ?>" class="btn btn-light btn-sm">
                                Agendar ahora
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card card-custom-2">
                        <div class="card-body text-center">
                            <i ></i>
                            <h3>Mis Citas</h3>
                            <a href="<?= base_url('cliente/mis-citas') ?>" class="btn btn-light btn-sm">
                                Ver mis citas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"> Próximas Citas</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted text-center">No tienes citas programadas</p>
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
