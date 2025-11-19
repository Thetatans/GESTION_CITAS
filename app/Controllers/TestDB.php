<?php

namespace App\Controllers;

use Config\Database as ConfigDatabase;

class TestDB extends BaseController
{
    public function index()
    {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Test Detallado</title>
            <style>
                body { font-family: Arial; padding: 20px; background: #f5f5f5; }
                .box { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
                .success { color: green; font-weight: bold; }
                .error { color: red; font-weight: bold; }
                pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
            </style>
        </head>
        <body>
            <h1>🔍 Diagnóstico Detallado de CodeIgniter</h1>";
        
        // Test 1: Configuración
        echo "<div class='box'>";
        echo "<h2>1. Configuración de Database.php</h2>";
        $config = new ConfigDatabase();
        echo "<pre>";
        print_r($config->default);
        echo "</pre>";
        echo "</div>";
        
        // Test 2: Intentar conectar manualmente
        echo "<div class='box'>";
        echo "<h2>2. Conexión Manual MySQLi</h2>";
        $mysqli = @new \mysqli(
            $config->default['hostname'],
            $config->default['username'],
            $config->default['password'],
            $config->default['database'],
            $config->default['port']
        );
        
        if ($mysqli->connect_error) {
            echo "<p class='error'>❌ Error: " . $mysqli->connect_error . "</p>";
        } else {
            echo "<p class='success'>✅ Conexión manual exitosa</p>";
            echo "<p>Versión MySQL: " . $mysqli->server_info . "</p>";
            $mysqli->close();
        }
        echo "</div>";
        
        // Test 3: Usar Database::connect()
        echo "<div class='box'>";
        echo "<h2>3. CodeIgniter Database::connect()</h2>";
        
        try {
            $db = \Config\Database::connect();
            
            echo "<p>Tipo de objeto: " . get_class($db) . "</p>";
            echo "<p>connID existe: " . (isset($db->connID) ? 'Sí' : 'No') . "</p>";
            echo "<p>connID valor: " . var_export($db->connID, true) . "</p>";
            
            if ($db->connID) {
                echo "<p class='success'>✅ CodeIgniter conectado!</p>";
                echo "<p>Database: " . $db->database . "</p>";
                
                // Probar query
                $query = $db->query("SELECT DATABASE() as db, VERSION() as version");
                $row = $query->getRow();
                echo "<p>Base de datos activa: " . $row->db . "</p>";
                echo "<p>Versión: " . $row->version . "</p>";
                
            } else {
                echo "<p class='error'>❌ connID es false o null</p>";
                echo "<p>Error de conexión</p>";
            }
            
        } catch (\Exception $e) {
            echo "<p class='error'>❌ Excepción: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        echo "</div>";
        
        echo "</body></html>";
    }
}