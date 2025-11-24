<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo ?? 'Barbería') ?> - Sistema de Gestión</title>
    
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
            background: var(--azul-oscuro);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

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

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            overflow: hidden;
            max-width: 650px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .auth-header {
            background: var(--cafe  );
            color: white;
            padding: 2.5rem;
            text-align: center;
            position: relative;
        }

        .auth-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            background: var(--beige);
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        }

        .auth-header h3 {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .auth-header i {
            font-size: 2rem;
        }

        .auth-body {
            padding: 3rem 2.5rem 2.5rem;
            background: var(--beige);
        }

        .form-label {
            color: var(--gris);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--azul-oscuro);
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 95, 0.15);
            background-color: white;
        }

        .btn-primary {
            background: var(--cafe);
            border: none;
            padding: 0.875rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background:var(--azul-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
 
        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        a {
            color: var(--azul-oscuro);
            transition: color 0.3s ease;
        }

        a:hover {
            color: var(--cafe);
        }

        hr {
            border-color: var(--cafe);
            opacity: 0.2;
        }

        .bg-light {
            background-color: white !important;
            border: 2px solid var(--azul-oscuro);
            border-left: 5px solid var(--cafe);
        }

        .form-check-input:checked {
            background-color: var(--azul-oscuro);
            border-color: var(--azul-oscuro);
        }

        .form-check-input:focus {
            border-color: var(--azul-oscuro);
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 95, 0.15);
        }

        .form-check-label {
            color: var(--gris);
        }

        h4 {
            color: var(--azul-oscuro);
            font-weight: 700;
        }

        .text-muted {
            color: var(--gris) !important;
        }

    
        
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>