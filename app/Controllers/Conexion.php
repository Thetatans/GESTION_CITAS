<?php

namespace App\Controllers;

class Conexion extends BaseController
{
    public function index()
    {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Test de Conexión</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 40px;
                    background: linear-gradient(135deg, #ffffffff 0%, #ffffffff 100%);
                    margin: 0;
                }
                .container {
                    background: #d3ccccff;
                    padding: 40px;
                    border-radius: 10px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                    max-width: 700px;
                    margin: 0 auto;
                }
                .success {
                    color: #819182ff;
                    background: #E8F5E9;
                    padding: 15px;
                    border-radius: 5px;
                    border-left: 5px solid #808c80ff;
                    margin: 10px 0;
                }
                .info {
                    background: #cfbcbcff;
                    
                    margin: 10px 0;
                }
                .error {
                    color: #f44336;
                    background: #FFEBEE;
                    padding: 15px;
                    border-radius: 5px;
                    border-left: 5px solid #f44336;
                    margin: 10px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 15px 0;
                }
             
                
                h1 { color: #333; margin-top: 0; }
                 .button {
            
            color: #bb8d8d;
            padding: 10px 10px;
            text-align: center;
            text-decoration: none;
        }

        .button:hover {
            color: #bb4d4dff;
            
        }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>Conexión a Base de Datos</h1>";
        
        try {
            // Obtener la conexión
            $db = \Config\Database::connect();
            
            // FORZAR la inicialización de la conexión
            $db->initialize();
            
            // Verificar si se conectó
            if (!$db->connID) {
                echo "<div class='error'>";
                echo "<strong> Error:</strong> No se pudo establecer la conexión a la base de datos.";
                echo "</div>";
                return;
            }
            
            // Conexión exitosa
            echo "<div class='success'>";
            echo "<h2 style='margin-top:0;'>mysql conectó ilich!</h2>";
            echo "<p>La conexión a la base de datos se estableció correctamente.</p>";
            echo "</div>";
            
            // Información de la conexión
            echo "<div class='info'>";
            echo "<h3> Información de la Conexión</h3>";
            echo "<table>";
            echo "<tr><td>* Base de Datos</td><td> {$db->database} </td></tr>";
            echo "<tr><td>* Servidor</td><td>" . $db->hostname . "</td></tr>";
            echo "<tr><td>* Puerto</td><td>" . $db->port . "</td></tr>";
            echo "<tr><td>* Usuario</td><td>" . $db->username . "</td></tr>";
            echo "</table>";
            echo "</div>";
            
            // Probar una consulta
            $query = $db->query("SELECT DATABASE() as db_name, VERSION() as version");
            $row = $query->getRow();
            
           
            
            // Listar tablas
            $query = $db->query("SHOW TABLES");
            $tables = $query->getResultArray();
            
            echo "<div class='info'>";
            echo "<h3> Tablas en la Base de Datos</h3>";
            echo "<p><strong>Total de tablas:</strong> " . count($tables) . "</p>";
            echo "<ul>";
            foreach ($tables as $table) {
                $tableName = array_values($table)[0];
                echo "<li> " . $tableName . "</li>";
            }
            echo "</ul>";
            echo "</div>";
            
           
            
        } catch (\Exception $e) {
            echo "<div class='error'>";
            echo "<h3> Error de Conexión</h3>";
            echo "<p><strong>Mensaje:</strong> " . $e->getMessage() . "</p>";
            echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
            echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
            echo "</div>";
            
        }
        echo "<a class='button' href='http://localhost/gestion_citas/public'>volver</a>";
        
   
        
        echo "</div></body></html>";
    }
}