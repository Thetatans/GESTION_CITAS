<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Citas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .header {
            background-color: #1a3a52;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 40px;
            background-color: white;
            border: 1px solid #ddd;
        }

        .status {
            background-color: #8b7355;
            color: white;
            padding: 15px;
            text-align: center;
            margin-bottom: 30px;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section h2 {
            color: #1a3a52;
            margin-bottom: 15px;
            border-bottom: 2px solid #8b7355;
            padding-bottom: 5px;
        }

        .info-section p {
            line-height: 1.6;
            color: #555;
        }

        .info-list {
            list-style: none;
            padding: 0;
        }

        .info-list li {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .info-list li:before {
            content: " ";
            color: #8b7355;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #1a3a52;
            color: white;
            margin-top: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background-color: #1a3a52;
            color: white;
            padding: 10px;
            text-align: left;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .button {
         
            color: #bb8d8d;
            padding: 10px;
            text-align: center;
            text-decoration: none;
        }

        .button:hover {
            color: #bb4d4dff;
            
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Sistema de Gestión de Citas</h1>
        <p>Barbería & Spa</p>
    </div>

    <!-- Container Principal -->
    <div class="container">
        <!-- Estado del Sistema -->
        <div class="status">
            <h2>Sistema Operativo</h2>
        </div>

        <!-- Información del Sistema -->
        <div class="info-section">
            <h2>Información de el proyecto </h2>
            <ul class="info-list">
                
                <li>Pagina de bienvenida para verificar que la conexion con codeigniter 4 fue exitosa y para comprobar que la conexion con la base de datos fue exitosa.</li>
                <li>para verificar que la base de datos copia este link en tu barra de navegacion "http://localhost/gestion_citas/public/test"</li>
                <li>para hacer mas pruebas de calidad sobre la base de datos esta la opcion de ver los datos de cada tabla, ingresa <a class="button"href="http://localhost/gestion_citas/public/datos/json">aqui</a> para ver todo el json de la base de datos</li>
                <a class="button"href="http://localhost/gestion_citas/public/conexion">prueba aqui si la bd funciona</a>
        </div>

        <!-- Módulos del Sistema -->
        <div class="info-section">
            <h2>Módulos Disponibles</h2>
            <ul class="info-list">
                
                <li>Clientes</li>
                <li>Empleados</li>
                <li>Servicios</li>
                <li>Citas</li>
                <li>Reportes</li>
                <li>para ver los detalles de cada tabla ingresa el link a tu barra de navegacion "http://localhost/gestion_citas/public/datos/tabla/NOMBRE DE TU TABLA"</li>
            </ul>
        </div>

        <!-- Información de la Base de Datos -->
        
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Sistema de Gestión de Citas - Barbería & Spa / Proyecto para DICO TELECOMUNICACIONES</p>
        <p> lich Esteban Reyes Botia</p>
    </div>
</body>
</html>