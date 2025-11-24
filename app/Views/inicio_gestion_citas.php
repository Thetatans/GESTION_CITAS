<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Barbería & Spa</title>

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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--azul-oscuro);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Patrón decorativo de fondo */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,112C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x bottom;
            opacity: 0.3;
        }

        .container-main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        .welcome-card {
            background: white;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .welcome-header {
            background: var(--cafe);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
        }

        .welcome-header h1 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 1rem;
        }

        .welcome-header .subtitle {
            font-size: 1.5rem;
            opacity: 0.95;
            margin-top: 0.5rem;
        }

        .welcome-header i {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }

        .welcome-body {
            flex: 1;
            padding: 4rem 3rem 3rem;
            background: var(--beige);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-message {
            color: var(--gris);
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 2.5rem;
        }

        .welcome-message h2 {
            color: var(--azul-oscuro);
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 3rem auto;
            max-width: 1400px;
            width: 100%;
        }

        .feature-item {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            border: 2px solid var(--azul-oscuro);
            border-left: 5px solid var(--cafe);
        }

        .feature-item i {
            font-size: 2.5rem;
            color: var(--cafe);
            margin-bottom: 1rem;
        }

        .feature-item h3 {
            color: var(--azul-oscuro);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .feature-item p {
            color: var(--gris);
            font-size: 0.9rem;
            margin: 0;
        }

        .btn-login {
            background: var(--cafe);
            color: white;
            border: none;
            padding: 1.2rem 3rem;
            font-size: 1.3rem;
            font-weight: 600;
            border-radius: 15px;
            text-decoration: none;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(107, 68, 35, 0.3);
        }

        .btn-login:hover {
            background: var(--azul-hover);
            color: white;
        }

        .btn-login i {
            margin-right: 0.8rem;
            font-size: 1.5rem;
        }

        .footer-info {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid var(--cafe);
            opacity: 0.8;
        }

        .footer-info p {
            color: var(--gris);
            font-size: 0.9rem;
            margin: 0.3rem 0;
        }

        .footer-info strong {
            color: var(--azul-oscuro);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-header h1 {
                font-size: 2rem;
            }

            .welcome-header .subtitle {
                font-size: 1.2rem;
            }

            .welcome-body {
                padding: 2.5rem 1.5rem;
            }

            .btn-login {
                padding: 1rem 2rem;
                font-size: 1.1rem;
            }

            .feature-list {
                grid-template-columns: 1fr;
            }
        }

    </style>
</head>
<body>
    <div class="container-main">
        <div class="welcome-card">
            <!-- Header -->
            <div class="welcome-header">
                <i class="bi bi-scissors"></i>
                <h1>Barbería & Spa</h1>
                <p class="subtitle">Sistema de Gestión de Citas</p>
            </div>

            <!-- Body -->
            <div class="welcome-body">
                <div class="welcome-message">
                    <h2>¡Bienvenido a Nuestro Sistema!</h2>
                    <p>
                        Gestiona tus citas, servicios y clientes de manera fácil y eficiente.
                        <br>
                        Nuestro sistema te permite llevar un control completo de tu barbería.
                    </p>
                </div>

                <!-- Características -->
                <div class="feature-list">
                    <div class="feature-item">
                        <i class="bi bi-calendar-check"></i>
                        <h3>Agenda de Citas</h3>
                        <p>Gestión completa de citas</p>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-people"></i>
                        <h3>Clientes</h3>
                        <p>Base de datos de clientes</p>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-person-badge"></i>
                        <h3>Empleados</h3>
                        <p>Control de personal</p>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-scissors"></i>
                        <h3>Servicios</h3>
                        <p>Catálogo de servicios</p>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-graph-up"></i>
                        <h3>Reportes</h3>
                        <p>Estadísticas y análisis</p>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-gear"></i>
                        <h3>Configuración</h3>
                        <p>Personalización total</p>
                    </div>
                </div>

                <!-- Botón Principal -->
                <div class="mt-5">
                    <a href="<?= base_url('login') ?>" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Iniciar Sesión
                    </a>
                </div>

                <!-- Footer Info -->
                <div class="footer-info">
                    <p><strong>Sistema de Gestión de Citas</strong></p>
                    <p>Barbería y Spa - Proyecto DICO TELECOMUNICACIONES</p>
                    <p>Desarrollado por: <strong>Ilich Esteban Reyes Botia</strong></p>
                    <p>Aprendiz SENA</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
