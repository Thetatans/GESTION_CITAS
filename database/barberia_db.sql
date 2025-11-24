-- ============================================
-- Base de datos para Sistema de Gestión de Citas - Barbería
-- ============================================

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS barberia_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE barberia_db;

-- ============================================
-- Tabla: usuarios (tabla principal de autenticación)
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'empleado', 'cliente') NOT NULL DEFAULT 'cliente',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    ultimo_acceso DATETIME NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rol (rol),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: clientes (perfil de clientes)
-- ============================================
CREATE TABLE IF NOT EXISTS clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    fecha_nacimiento DATE NULL,
    genero VARCHAR(20) NULL,
    direccion TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_id_usuario (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: empleados (perfil de empleados)
-- ============================================
CREATE TABLE IF NOT EXISTS empleados (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    especialidad VARCHAR(100) NULL,
    comision_porcentaje DECIMAL(5,2) NULL DEFAULT 0.00,
    fecha_contratacion DATE NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_id_usuario (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: administradores (perfil de administradores)
-- ============================================
CREATE TABLE IF NOT EXISTS administradores (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    cargo VARCHAR(100) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_id_usuario (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: servicios (servicios de la barbería)
-- ============================================
CREATE TABLE IF NOT EXISTS servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    precio DECIMAL(10,2) NOT NULL,
    duracion_minutos INT NOT NULL DEFAULT 30,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: citas (gestión de citas)
-- ============================================
CREATE TABLE IF NOT EXISTS citas (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_empleado INT NOT NULL,
    id_servicio INT NOT NULL,
    fecha_cita DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'en_proceso', 'completada', 'cancelada') NOT NULL DEFAULT 'pendiente',
    notas TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio) ON DELETE CASCADE,
    INDEX idx_fecha (fecha_cita),
    INDEX idx_estado (estado),
    INDEX idx_cliente (id_cliente),
    INDEX idx_empleado (id_empleado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Datos de prueba
-- ============================================

-- Usuario administrador de prueba
-- Contraseña: ilich123
INSERT INTO usuarios (email, password, rol, activo) VALUES
('admin@barberia.com', '$2y$10$9AkGpUouFUmRGV5OPOCC7uH0tKZUKTqScxXWOObACxc9yqlCgvrMq', 'admin', 1);

-- Perfil del administrador
INSERT INTO administradores (id_usuario, nombre, apellido, telefono, cargo) VALUES
(1, 'Admin', 'Sistema', '1234567890', 'Gerente General');

-- Usuario empleado de prueba
-- Contraseña: ilich123
INSERT INTO usuarios (email, password, rol, activo) VALUES
('empleado@barberia.com', '$2y$10$9AkGpUouFUmRGV5OPOCC7uH0tKZUKTqScxXWOObACxc9yqlCgvrMq', 'empleado', 1);

-- Perfil del empleado
INSERT INTO empleados (id_usuario, nombre, apellido, telefono, especialidad, comision_porcentaje, fecha_contratacion) VALUES
(2, 'Juan', 'Barbero', '0987654321', 'Corte de cabello', 15.00, CURDATE());

-- Usuario cliente de prueba
-- Contraseña: ilich123
INSERT INTO usuarios (email, password, rol, activo) VALUES
('cliente@barberia.com', '$2y$10$9AkGpUouFUmRGV5OPOCC7uH0tKZUKTqScxXWOObACxc9yqlCgvrMq', 'cliente', 1);

-- Perfil del cliente
INSERT INTO clientes (id_usuario, nombre, apellido, telefono, fecha_nacimiento, genero, direccion) VALUES
(3, 'Carlos', 'Cliente', '1122334455', '1990-01-15', 'Masculino', 'Calle Principal 123');

-- Servicios de ejemplo
INSERT INTO servicios (nombre, descripcion, precio, duracion_minutos, activo) VALUES
('Corte de cabello', 'Corte clásico o moderno', 15000.00, 30, 1),
('Afeitado', 'Afeitado tradicional con navaja', 10000.00, 20, 1),
('Corte + Barba', 'Corte de cabello y arreglo de barba', 25000.00, 45, 1),
('Tinte', 'Coloración de cabello', 35000.00, 60, 1);

-- ============================================
-- Notas importantes:
-- ============================================
-- 1. La contraseña de prueba para todos los usuarios es: ilich123
-- 2. El hash usado es de bcrypt (password_hash de PHP)
-- 3. Para generar nuevos hashes usa: password_hash('tu_contraseña', PASSWORD_DEFAULT)
-- 4. Ejecuta este script en phpMyAdmin o MySQL Workbench
