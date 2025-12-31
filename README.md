# Sistema de Gestión de Citas - Barbería y Spa

## Descripción del Proyecto

Sistema web completo para la gestión de citas en una barbería desarrollado con CodeIgniter 4. Permite a clientes agendar citas, a empleados gestionar su agenda y a administradores supervisar todas las operaciones del negocio.

**Desarrollador:** Ilich Esteban Reyes Botia
**Institución:** SENA - Aprendiz
**Proyecto:** DICO TELECOMUNICACIONES
**Framework:** CodeIgniter 4
**Versión:** 1.0

---

## Características Principales

### Sistema de Roles

El sistema implementa 3 roles diferenciados con sus respectivos dashboards y permisos:

#### 1. ROL ADMINISTRADOR
- Gestión completa de clientes, empleados y servicios
- Visualización de todas las citas en calendario interactivo
- Creación, edición y cancelación de citas
- Generación de reportes y estadísticas
- Gestión de estados de citas
- Acceso total al sistema

#### 2. ROL EMPLEADO (Barbero)
- Vista de agenda personal
- Visualización de citas asignadas
- Actualización de estados de citas
- Ver detalles de clientes y servicios
- Dashboard personalizado con citas del día

#### 3. ROL CLIENTE
- Agendar nuevas citas
- Ver historial de citas
- Cancelar citas (con restricción de 24 horas)
- Ver detalles de servicios disponibles
- Consultar disponibilidad de horarios

---

## Módulos del Sistema

### 1. Módulo de Autenticación
**Ubicación:** `app/Controllers/Auth.php`

Funcionalidades:
- Registro de nuevos clientes
- Login con validación de credenciales
- Verificación de roles y permisos
- Validación de estado de usuarios (activo, inactivo, suspendido, despedido)
- Protección de sesiones
- Logout seguro

**Rutas principales:**
```
/login
/logout
/registro
/recuperar-password
```

### 2. Módulo de Gestión de Clientes
**Ubicación:** `app/Controllers/Admin/Clientes.php`

Funcionalidades:
- Listado de clientes con búsqueda
- Creación de nuevos clientes
- Edición de información de clientes
- Eliminación (soft delete)
- Visualización de historial de citas por cliente

**Campos de cliente:**
- Nombre y apellido
- Email (único)
- Teléfono
- Fecha de nacimiento
- Género
- Dirección

### 3. Módulo de Gestión de Empleados
**Ubicación:** `app/Controllers/Admin/Empleados.php`

Funcionalidades:
- Listado de empleados
- Alta de nuevos empleados
- Edición de información
- Asignación de especialidades
- Gestión de horarios laborales
- Vinculación con usuarios del sistema

**Campos de empleado:**
- Nombre y apellido
- Email
- Teléfono
- Especialidad
- Fecha de contratación
- Salario
- Estado (activo, inactivo, despedido)

### 4. Módulo de Servicios
**Ubicación:** `app/Controllers/Admin/Servicios.php`

Funcionalidades:
- Catálogo de servicios de la barbería
- Creación y edición de servicios
- Definición de precios y duraciones
- Activación/desactivación de servicios

**Campos de servicio:**
- Nombre del servicio
- Descripción
- Precio
- Duración en minutos
- Estado (activo/inactivo)

### 5. Módulo de Citas (PRINCIPAL)

#### 5.1 Vista de Administrador
**Ubicación:** `app/Controllers/Admin/Citas.php`
**Modelo:** `app/Models/CitasModel.php`

Funcionalidades:
- Calendario interactivo con FullCalendar 6.1.9
- Vistas: Mes, Semana, Día, Lista
- Código de colores por estado:
  - Amarillo: Pendiente
  - Cyan: Confirmada
  - Azul: En Proceso
  - Verde: Completada
  - Rojo: Cancelada
- Filtros por empleado, estado y fecha
- Creación y edición de citas con validación
- Verificación automática de disponibilidad
- Gestión de estados
- Generación de estadísticas

**Métodos principales:**
```php
index()                      // Vista de calendario
obtenerCitas()               // API JSON para calendario
listado()                    // Vista de tabla
crear()                      // Formulario nueva cita
guardar()                    // Guardar cita con validación
editar()                     // Formulario edición
actualizar()                 // Actualizar cita
cambiarEstado()              // Cambiar estado de cita
eliminar()                   // Cancelar cita
obtenerHorariosDisponibles() // API de disponibilidad
estadisticas()               // Reportes
```

#### 5.2 Vista de Cliente
**Ubicación:** `app/Controllers/Cliente/Citas.php`

Funcionalidades:
- Formulario de agendamiento de citas
- Selección de empleado y servicio
- Calendario de fechas disponibles
- Lista de horarios disponibles en tiempo real
- Vista de citas próximas e historial
- Cancelación de citas (restricción 24h)

**Métodos principales:**
```php
index()                      // Mis citas
agendar()                    // Formulario de agendamiento
guardarCita()                // Guardar nueva cita
ver()                        // Detalle de cita
cancelar()                   // Cancelar cita
obtenerHorariosDisponibles() // API de horarios
```

**Validaciones de agendamiento:**
- No se pueden agendar citas en el pasado
- Validación de disponibilidad del empleado
- Cálculo automático de hora fin según duración
- Horario de trabajo: 9:00 AM - 7:00 PM
- Slots de 30 minutos

#### 5.3 Vista de Empleado
**Ubicación:**
- `app/Controllers/Empleado/Citas.php`
- `app/Controllers/Empleado/Agenda.php`

Funcionalidades:
- Vista de agenda personal
- Listado de citas asignadas
- Actualización de estados
- Ver detalles de clientes

---

## Estructura de Base de Datos

### Tablas Principales

#### usuarios
```sql
- id_usuario (PK)
- email (UNIQUE)
- password_hash
- rol (admin, empleado, cliente)
- estado (activo, inactivo, suspendido, despedido)
- created_at
- updated_at
```

#### clientes
```sql
- id_cliente (PK)
- id_usuario (FK -> usuarios)
- nombre
- apellido
- telefono
- fecha_nacimiento
- genero
- direccion
- created_at
- updated_at
```

#### empleados
```sql
- id_empleado (PK)
- id_usuario (FK -> usuarios)
- nombre
- apellido
- telefono
- especialidad
- fecha_contratacion
- salario
- estado
- created_at
- updated_at
```

#### servicios
```sql
- id_servicio (PK)
- nombre
- descripcion
- precio (DECIMAL)
- duracion_minutos (INT)
- activo (BOOLEAN)
- created_at
- updated_at
```

#### citas
```sql
- id_cita (PK)
- id_cliente (FK -> clientes)
- id_empleado (FK -> empleados)
- id_servicio (FK -> servicios)
- fecha_cita (DATE)
- hora_inicio (TIME)
- hora_fin (TIME)
- estado (ENUM: pendiente, confirmada, en_proceso, completada, cancelada)
- notas (TEXT)
- created_at
- updated_at
```

### Relaciones
- Un cliente puede tener muchas citas (1:N)
- Un empleado puede tener muchas citas (1:N)
- Un servicio puede estar en muchas citas (1:N)
- Cada cita pertenece a un cliente, un empleado y un servicio

---

## Tecnologías Utilizadas

### Backend
- **PHP 7.4+**
- **CodeIgniter 4** - Framework MVC
- **MySQL/MariaDB** - Base de datos

### Frontend
- **HTML5**
- **CSS3**
- **JavaScript (ES6+)**
- **Bootstrap 5.3.0** - Framework CSS
- **Bootstrap Icons 1.10.0** - Iconografía
- **FullCalendar 6.1.9** - Calendario interactivo
- **jQuery** (para compatibilidad con FullCalendar)

### Herramientas
- **XAMPP** - Servidor local de desarrollo
- **Composer** - Gestor de dependencias PHP
- **Git** - Control de versiones

---

## Instalación y Configuración

### Requisitos Previos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Composer
- Servidor web (Apache/Nginx) o XAMPP

### Pasos de Instalación

#### 1. Clonar el repositorio
```bash
cd C:\xampp\htdocs
git clone [URL_REPOSITORIO] gestion_citas
cd gestion_citas
```

#### 2. Instalar dependencias
```bash
composer install
```

#### 3. Configurar base de datos
Crear la base de datos:
```sql
CREATE DATABASE barberia_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 4. Configurar archivo .env
Copiar el archivo de ejemplo:
```bash
cp env .env
```

Editar `.env` con tus credenciales:
```env
database.default.hostname = localhost
database.default.database = barberia_db
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

#### 5. Ejecutar migraciones
```bash
php spark migrate
```

#### 6. Cargar datos iniciales (seeds)
```bash
php spark db:seed DatabaseSeeder
```

#### 7. Iniciar servidor
```bash
php spark serve
```

El sistema estará disponible en: `http://localhost:8080`

---

## Credenciales de Prueba

### Administrador
- **Email:** admin@barberia.com
- **Contraseña:** ilich123

### Empleado
- **Email:** empleado@barberia.com
- **Contraseña:** ilich123

### Cliente
- **Email:** cliente@barberia.com
- **Contraseña:** ilich123

---

## Estructura de Archivos del Proyecto

```
gestion_citas/
├── app/
│   ├── Config/
│   │   ├── Routes.php              # Rutas del sistema
│   │   ├── Database.php            # Configuración de BD
│   │   └── Filters.php             # Filtros de autenticación
│   │
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── Dashboard.php       # Dashboard admin
│   │   │   ├── Clientes.php        # CRUD clientes
│   │   │   ├── Empleados.php       # CRUD empleados
│   │   │   ├── Servicios.php       # CRUD servicios
│   │   │   └── Citas.php           # Gestión de citas
│   │   │
│   │   ├── Cliente/
│   │   │   ├── Dashboard.php       # Dashboard cliente
│   │   │   └── Citas.php           # Citas del cliente
│   │   │
│   │   ├── Empleado/
│   │   │   ├── Dashboard.php       # Dashboard empleado
│   │   │   ├── Citas.php           # Citas del empleado
│   │   │   └── Agenda.php          # Agenda del empleado
│   │   │
│   │   └── Auth.php                # Autenticación
│   │
│   ├── Models/
│   │   ├── UsuarioModel.php        # Modelo de usuarios
│   │   ├── ClienteModel.php        # Modelo de clientes
│   │   ├── EmpleadoModel.php       # Modelo de empleados
│   │   ├── ServicioModel.php       # Modelo de servicios
│   │   └── CitasModel.php          # Modelo de citas
│   │
│   ├── Views/
│   │   ├── admin/
│   │   │   ├── dashboard.php
│   │   │   ├── clientes/
│   │   │   ├── empleados/
│   │   │   ├── servicios/
│   │   │   └── citas/
│   │   │
│   │   ├── cliente/
│   │   │   ├── dashboard.php
│   │   │   └── citas/
│   │   │
│   │   ├── empleado/
│   │   │   ├── dashboard.php
│   │   │   ├── citas/
│   │   │   └── agenda/
│   │   │
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── registro.php
│   │   │
│   │   └── layouts/
│   │       └── auth.php            # Layout de autenticación
│   │
│   └── Helpers/
│       └── notificaciones_helper.php # Helper de notificaciones
│
├── public/
│   ├── index.php                   # Punto de entrada
│   └── diagnostico.php             # Diagnóstico del sistema
│
├── writable/
│   └── logs/                       # Logs del sistema
│
├── .env                            # Variables de entorno
├── composer.json                   # Dependencias
└── README.md                       # Este archivo
```

---

## Paleta de Colores del Sistema

La aplicación utiliza una paleta profesional y elegante:

- **Azul Oscuro:** #1e3a5f (Color principal, botones, encabezados)
- **Café:** #6b4423 (Gradientes, acentos, hover)
- **Beige:** #f5e6d3 (Fondos, contraste suave)
- **Gris:** #495057 (Textos, elementos secundarios)

---

## Funcionalidades de Seguridad

### Autenticación y Autorización
- Sistema de roles robusto
- Filtros de autenticación en rutas protegidas
- Validación de permisos por controlador
- Hash de contraseñas con bcrypt
- Protección CSRF en formularios

### Validaciones
- Validación de datos en backend
- Escape de datos con `esc()` para prevenir XSS
- Validación de fechas y horarios
- Verificación de disponibilidad antes de agendar

### Sesiones
- Manejo seguro de sesiones con CodeIgniter
- Timeout de sesión
- Datos de usuario en sesión:
  - `usuario_id`
  - `usuario_email`
  - `usuario_rol`
  - `usuario_nombre`

---

## API Endpoints (AJAX)

### Citas
```
GET  /admin/citas/obtenerCitas        # Obtener citas para calendario
POST /admin/citas/guardar             # Crear nueva cita
POST /admin/citas/actualizar/:id      # Actualizar cita
POST /admin/citas/cambiarEstado/:id   # Cambiar estado
GET  /citas/obtenerHorariosDisponibles # Horarios disponibles
```

### Respuestas JSON
```json
{
    "success": true,
    "message": "Operación exitosa",
    "data": {}
}
```

---

## Sistema de Notificaciones

**Helper:** `app/Helpers/notificaciones_helper.php`

Funciones disponibles:
```php
enviar_email($para, $asunto, $mensaje)
notificar_nueva_cita($cita)
notificar_confirmacion($cita)
notificar_cancelacion($cita)
notificar_recordatorio($cita)
```

Las notificaciones actualmente se registran en logs y están preparadas para integración con servicios de email reales.

---

## Próximas Mejoras Planificadas

- Integración con pasarela de pagos
- Sistema de notificaciones por email real
- Notificaciones push
- Exportación de reportes a PDF/Excel
- Sistema de calificaciones y reseñas
- Multi-sucursal
- App móvil (React Native/Flutter)
- Recordatorios automáticos por WhatsApp
- Dashboard con gráficas avanzadas
- Sistema de fidelización de clientes

---

## Problemas Conocidos y Soluciones

### Problema: Cliente no puede agendar cita
**Causa:** Usuario no tiene perfil de cliente creado
**Solución:** El sistema crea automáticamente el perfil al intentar agendar

### Problema: Horarios no se muestran
**Causa:** Servicio o empleado no seleccionado
**Solución:** Validar que ambos campos estén llenos antes de cargar horarios

### Problema: Error de conexión a BD
**Causa:** Credenciales incorrectas en `.env`
**Solución:** Ejecutar `public/diagnostico.php` para verificar

---

## Soporte y Contribuciones

Para reportar bugs o sugerir mejoras:
1. Crear un issue en el repositorio
2. Describir el problema con capturas si es posible
3. Indicar pasos para reproducir

---

## Licencia

Este proyecto es propiedad de **Ilich Esteban Reyes Botia** y fue desarrollado como proyecto académico para el SENA en el marco del proyecto DICO TELECOMUNICACIONES.

---

## Agradecimientos

- SENA por la formación
- DICO TELECOMUNICACIONES por el proyecto
- Comunidad de CodeIgniter

---

## Contacto

**Desarrollador:** Ilich Esteban Reyes Botia
**Institución:** SENA
**Rol:** Aprendiz

---

**Última actualización:** Diciembre 2024
**Versión:** 1.0
