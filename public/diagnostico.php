<?php
echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Diagnóstico de Conexión</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { background: #f0f0f0; padding: 10px; margin: 10px 0; }
        h2 { border-bottom: 2px solid #333; padding-bottom: 5px; }
    </style>
</head>
<body>";

echo "<h1>🔍 Diagnóstico Completo del Sistema</h1>";

// 1. Verificar PHP
echo "<h2>1. Información de PHP</h2>";
echo "<div class='info'>";
echo "Versión de PHP: <strong>" . phpversion() . "</strong><br>";
echo "Sistema Operativo: <strong>" . PHP_OS . "</strong>";
echo "</div>";

// 2. Verificar extensiones
echo "<h2>2. Extensiones de Base de Datos</h2>";
echo "<div class='info'>";
echo "MySQLi: " . (extension_loaded('mysqli') ? "<span class='success'>✅ Habilitada</span>" : "<span class='error'>❌ NO habilitada</span>") . "<br>";
echo "PDO: " . (extension_loaded('pdo') ? "<span class='success'>✅ Habilitada</span>" : "<span class='error'>❌ NO habilitada</span>") . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? "<span class='success'>✅ Habilitada</span>" : "<span class='error'>❌ NO habilitada</span>");
echo "</div>";

// 3. Probar conexión MySQLi directa
echo "<h2>3. Prueba de Conexión MySQLi Directa</h2>";
echo "<div class='info'>";

$host = 'localhost';
$dbname = 'barberia_db';
$username = 'root';
$password = '';

echo "Host: <strong>$host</strong><br>";
echo "Base de datos: <strong>$dbname</strong><br>";
echo "Usuario: <strong>$username</strong><br>";
echo "Password: <strong>" . (empty($password) ? '(vacío)' : '(con valor)') . "</strong><br>";
echo "Puerto: <strong>3306</strong><br><br>";

$conn = @new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "<span class='error'>❌ ERROR de conexión MySQLi:</span><br>";
    echo "<strong>Código:</strong> " . $conn->connect_errno . "<br>";
    echo "<strong>Mensaje:</strong> " . $conn->connect_error . "<br>";
} else {
    echo "<span class='success'>✅ Conexión MySQLi EXITOSA</span><br>";
    echo "Versión del servidor: <strong>" . $conn->server_info . "</strong><br>";
    echo "Versión del cliente: <strong>" . $conn->client_info . "</strong><br><br>";
    
    // Listar tablas
    $result = $conn->query("SHOW TABLES");
    if ($result && $result->num_rows > 0) {
        echo "<strong>Tablas encontradas (" . $result->num_rows . "):</strong><br>";
        echo "<ul>";
        while ($row = $result->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<span class='error'>⚠️ No hay tablas en la base de datos</span><br>";
    }
    
    $conn->close();
}
echo "</div>";

// 4. Verificar archivo .env
echo "<h2>4. Verificación del archivo .env</h2>";
echo "<div class='info'>";

$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    echo "<span class='success'>✅ Archivo .env encontrado</span><br>";
    echo "Ubicación: <code>$envPath</code><br>";
    echo "Permisos de lectura: " . (is_readable($envPath) ? "<span class='success'>✅ SÍ</span>" : "<span class='error'>❌ NO</span>") . "<br>";
} else {
    echo "<span class='error'>❌ Archivo .env NO encontrado</span><br>";
    echo "Buscado en: <code>$envPath</code><br>";
}
echo "</div>";

// 5. Verificar variables de entorno cargadas
echo "<h2>5. Variables de Entorno Cargadas</h2>";
echo "<div class='info'>";
$dbVars = [
    'database.default.hostname',
    'database.default.database', 
    'database.default.username',
    'database.default.DBDriver',
    'database.default.port'
];

foreach ($dbVars as $var) {
    $value = getenv($var);
    if ($value !== false) {
        echo "$var = <strong>$value</strong><br>";
    } else {
        echo "$var = <span class='error'>❌ No definida</span><br>";
    }
}
echo "</div>";

// 6. Probar conexión con CodeIgniter
echo "<h2>6. Prueba de Conexión con CodeIgniter</h2>";
echo "<div class='info'>";

try {
    // Cargar CodeIgniter
    require_once dirname(__DIR__) . '/vendor/autoload.php';
    require_once dirname(__DIR__) . '/app/Config/Paths.php';
    
    $paths = new Config\Paths();
    require_once SYSTEMPATH . 'Config/DotEnv.php';
    (new CodeIgniter\Config\DotEnv(ROOTPATH))->load();
    
    require_once APPPATH . 'Config/Database.php';
    
    $config = config('Database');
    $db = \Config\Database::connect();
    
    if ($db->connID) {
        echo "<span class='success'>✅ Conexión CodeIgniter EXITOSA</span><br>";
        echo "Base de datos conectada: <strong>" . $db->database . "</strong>";
    } else {
        echo "<span class='error'>❌ Error en conexión CodeIgniter</span>";
    }
    
} catch (\Exception $e) {
    echo "<span class='error'>❌ Excepción en CodeIgniter:</span><br>";
    echo "<code>" . $e->getMessage() . "</code><br>";
    echo "<strong>Archivo:</strong> " . $e->getFile() . "<br>";
    echo "<strong>Línea:</strong> " . $e->getLine();
}

echo "</div>";

echo "</body></html>";
?>
```

---

## 🌐 EJECUTA EL DIAGNÓSTICO

**Accede a:**
```
http://localhost/gestion_citas/public/diagnostico.php