<?php

// Declaración del namespace (espacio de nombres) donde está este controlador
namespace App\Controllers;

/**
 * Clase Datos
 * 
 * Este controlador maneja la visualización de datos de la base de datos
 * en diferentes formatos (HTML, JSON, etc.)
 * 
 * Extiende de BaseController que es la clase base de CodeIgniter 4
 */
class Datos extends BaseController
{
    /**
     * Método index()
     * 
     * Este es el método principal que se ejecuta cuando accedes a /datos
     * Muestra todas las tablas de la base de datos con sus datos en formato HTML
     */
    public function index()
    {
        // ==========================================
        // PASO 1: CONEXIÓN A LA BASE DE DATOS
        // ==========================================
        
        // Obtener la instancia de conexión a la base de datos
        // \Config\Database::connect() crea un objeto de conexión usando la configuración en Database.php
        $db = \Config\Database::connect();
        
        // Forzar la inicialización de la conexión
        // Sin esto, connID sería false (conexión perezosa)
        $db->initialize();
        
        
        // ==========================================
        // PASO 2: OBTENER TODAS LAS TABLAS
        // ==========================================
        
        // $db->listTables() devuelve un array con los nombres de todas las tablas
        // Ejemplo: ['usuarios', 'clientes', 'empleados', 'servicios', ...]
        $tablas = $db->listTables();
        
        // Crear un array vacío donde guardaremos la información de cada tabla
        $resultado = [];
        
        // ==========================================
        // PASO 3: RECORRER CADA TABLA Y EXTRAER DATOS
        // ==========================================
        
        // foreach itera sobre cada tabla de la base de datos
        foreach ($tablas as $tabla) {
            
            // Ejecutar una consulta SQL para obtener TODOS los registros de la tabla actual
            // $tabla contiene el nombre de la tabla (ej: 'usuarios', 'clientes', etc.)
            // $query es un objeto ResultSet que contiene los resultados
            $query = $db->query("SELECT * FROM $tabla");
            
            // Convertir los resultados a un array asociativo
            // getResultArray() devuelve algo como:
            // [
            //   ['id' => 1, 'nombre' => 'Juan', 'email' => 'juan@mail.com'],
            //   ['id' => 2, 'nombre' => 'María', 'email' => 'maria@mail.com']
            // ]
            $datos = $query->getResultArray();
            
            // Guardar la información de esta tabla en el array $resultado
            // La clave del array es el nombre de la tabla
            $resultado[$tabla] = [
                // count($datos) cuenta cuántos registros hay
                'total_registros' => count($datos),
                
                // getFieldNames() devuelve los nombres de las columnas
                // Ejemplo: ['id', 'nombre', 'email', 'password']
                'campos' => $query->getFieldNames(),
                
                // Los datos completos de todos los registros
                'datos' => $datos
            ];
        }
        
 
       
        // Recorrer el array $resultado que contiene la info de todas las tablas
  
        foreach ($resultado as $nombreTabla => $info) {
            
            // Convertir el array $info a formato JSON
            // JSON_PRETTY_PRINT: formatea el JSON con indentación (más legible)
            // JSON_UNESCAPED_UNICODE: mantiene caracteres especiales como tildes (ñ, á, etc.)
            // Resultado: un string con formato JSON bonito
            $jsonData = json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            
            // Crear un ID único para cada tabla eliminando guiones bajos
            // 'horarios_empleado' se convierte en 'horariosempleado'
            // Esto se usa para IDs de HTML (no pueden tener caracteres especiales)
            $jsonId = str_replace('_', '', $nombreTabla);
            
            // NOTA: Aquí falta el código HTML que imprime la tabla
            // En el código original estaba el echo con el HTML
        }
    }
    
    /**
     * Método json()
     * 
     * Devuelve TODAS las tablas en formato JSON puro (sin HTML)
     * Útil para crear APIs o para consumir desde JavaScript
     * 
     * Ejemplo de URL: http://localhost/gestion_citas/public/datos/json
     */
    public function json()
    {

        // PASO 1: CONECTAR A LA BASE DE DATOS

        
        // Obtener la conexión
        $db = \Config\Database::connect();
        
        // Forzar la inicialización
        $db->initialize();
        

        // PASO 2: VERIFICAR CONEXIÓN

        
        // Si connID es false, significa que NO se pudo conectar
        if (!$db->connID) {
            // Devolver un JSON con el mensaje de error
            // setJSON() convierte el array a JSON y lo envía como respuesta HTTP
            return $this->response->setJSON([
                'error' => 'No se pudo conectar a la base de datos'
            ]);
        }
        

        // PASO 3: OBTENER DATOS DE TODAS LAS TABLAS

        
        // Obtener lista de tablas
        $tablas = $db->listTables();
        
        // Array para guardar resultados
        $resultado = [];
        
        // Recorrer cada tabla
        foreach ($tablas as $tabla) {
            // Consultar todos los registros de la tabla
            $query = $db->query("SELECT * FROM $tabla");
            
            // Obtener datos como array
            $datos = $query->getResultArray();
            
            // Guardar información estructurada de la tabla
            $resultado[$tabla] = [
                'total_registros' => count($datos),      // Número de registros
                'campos' => $query->getFieldNames(),     // Nombres de columnas
                'datos' => $datos                        // Todos los datos
            ];
        }
        

        // PASO 4: DEVOLVER JSON

        
        // Convertir $resultado a JSON y enviarlo como respuesta
        // El navegador recibirá un JSON puro sin HTML
        return $this->response->setJSON($resultado);
    }
    
    /**
     * Método tabla()
     * 
     * Devuelve los datos de UNA SOLA tabla específica en formato JSON
     * 
     * @param string|null $nombreTabla - Nombre de la tabla a consultar
     * 
     * Ejemplo de URL: http://localhost/gestion_citas/public/datos/tabla/usuarios
     */
    public function tabla($nombreTabla = null)
    {

        // PASO 1: VALIDAR QUE SE ENVIÓ EL NOMBRE DE LA TABLA

        
        // Si $nombreTabla es null, significa que no se especificó
        if (!$nombreTabla) {
            // Devolver error en formato JSON
            return $this->response->setJSON([
                'error' => 'Debes especificar el nombre de la tabla'
            ]);
        }
        
 
        // PASO 2: CONECTAR A LA BASE DE DATOS

        
        $db = \Config\Database::connect();
        $db->initialize();
        
        // Verificar que la conexión sea exitosa
        if (!$db->connID) {
            return $this->response->setJSON([
                'error' => 'No se pudo conectar a la base de datos'
            ]);
        }
        

        // PASO 3: VERIFICAR QUE LA TABLA EXISTE
   
        
        // Obtener lista de todas las tablas de la base de datos
        $tablas = $db->listTables();
        
        // in_array() verifica si $nombreTabla está en el array $tablas
        // Si no está, significa que la tabla no existe
        if (!in_array($nombreTabla, $tablas)) {
            // Devolver error con la lista de tablas disponibles
            return $this->response->setJSON([
                'error' => "La tabla '$nombreTabla' no existe",
                'tablas_disponibles' => $tablas  // Ayuda al usuario a ver qué tablas existen
            ]);
        }
        

        // PASO 4: CONSULTAR DATOS DE LA TABLA

        
        // Ejecutar SELECT * para obtener todos los datos de la tabla
        $query = $db->query("SELECT * FROM $nombreTabla");
        
        // Convertir resultados a array
        $datos = $query->getResultArray();
        
   
        // PASO 5: DEVOLVER JSON CON LA INFORMACIÓN
   
        
        // Devolver JSON estructurado con:
        // - nombre de la tabla
        // - cantidad de registros
        // - nombres de las columnas
        // - todos los datos
        return $this->response->setJSON([
            'tabla' => $nombreTabla,                 // Nombre de la tabla consultada
            'total_registros' => count($datos),      // Cantidad de filas
            'campos' => $query->getFieldNames(),     // Array con nombres de columnas
            'datos' => $datos                        // Array con todos los registros
        ]);
    }
}