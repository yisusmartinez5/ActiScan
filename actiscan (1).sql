CREATE DATABASE actiscan;
GO
USE actiscan;
GO

-- =========================
-- TABLA ROLES
-- =========================
CREATE TABLE roles (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(150)
);

-- =========================
-- TABLA PERMISOS
-- =========================
CREATE TABLE permisos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(150)
);

-- =========================
-- TABLA ROL_PERMISOS (N:M)
-- =========================
CREATE TABLE rol_permisos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_rol INT NOT NULL,
    id_permiso INT NOT NULL,
    CONSTRAINT UQ_rol_permiso UNIQUE (id_rol, id_permiso),
    CONSTRAINT FK_RP_Rol FOREIGN KEY (id_rol) REFERENCES roles(id),
    CONSTRAINT FK_RP_Permiso FOREIGN KEY (id_permiso) REFERENCES permisos(id)
);

-- =========================
-- TABLA USUARIOS
-- =========================
CREATE TABLE usuarios (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    fecha_creacion DATETIME DEFAULT GETDATE(),
    estatus_usuario VARCHAR(10) DEFAULT 'Activo',
    id_rol INT NOT NULL,
    CONSTRAINT CK_estatus_usuario CHECK (estatus_usuario IN ('Activo','Inactivo')),
    CONSTRAINT FK_Usuario_Rol FOREIGN KEY (id_rol) REFERENCES roles(id)
);

-- =========================
-- TABLA CATEGORIA_ACTIVOS
-- =========================
CREATE TABLE categoria_activos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(150),
    estatus_categoria VARCHAR(10) DEFAULT 'Activa',
    fecha_creacion DATETIME DEFAULT GETDATE(),
    CONSTRAINT CK_estatus_categoria CHECK (estatus_categoria IN ('Activa','Inactiva'))
);

-- =========================
-- TABLA ESTADO_ACTIVOS
-- =========================
CREATE TABLE estado_activos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(150)
);

-- =========================
-- TABLA UBICACIONES
-- =========================
CREATE TABLE ubicaciones (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(150)
);

-- =========================
-- TABLA ACTIVOS
-- =========================
CREATE TABLE activos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    codigo_interno VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(150),
    marca VARCHAR(100),
    modelo VARCHAR(100),
    numero_serie VARCHAR(100) UNIQUE,
    fecha_alta DATETIME DEFAULT GETDATE(),
    id_categoria INT NOT NULL,
    id_estado_activo INT NOT NULL,
    id_ubicacion INT NOT NULL,
    id_usuario INT NULL,
    CONSTRAINT FK_Activo_Categoria FOREIGN KEY (id_categoria) REFERENCES categoria_activos(id),
    CONSTRAINT FK_Activo_Estado FOREIGN KEY (id_estado_activo) REFERENCES estado_activos(id),
    CONSTRAINT FK_Activo_Ubicacion FOREIGN KEY (id_ubicacion) REFERENCES ubicaciones(id),
    CONSTRAINT FK_Activo_Usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- =========================
-- TABLA TIPO_MOVIMIENTOS
-- =========================
CREATE TABLE tipo_movimientos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(150)
);

-- =========================
-- TABLA MOVIMIENTOS
-- =========================
CREATE TABLE movimientos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    fecha_hora DATETIME DEFAULT GETDATE(),
    detalle VARCHAR(255),
    id_activo INT NOT NULL,
    id_tipo_movimiento INT NOT NULL,
    id_ubi_origen INT NULL,
    id_ubi_destino INT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT FK_Mov_Activo FOREIGN KEY (id_activo) REFERENCES activos(id),
    CONSTRAINT FK_Mov_Tipo FOREIGN KEY (id_tipo_movimiento) REFERENCES tipo_movimientos(id),
    CONSTRAINT FK_Mov_Ubi_Origen FOREIGN KEY (id_ubi_origen) REFERENCES ubicaciones(id),
    CONSTRAINT FK_Mov_Ubi_Destino FOREIGN KEY (id_ubi_destino) REFERENCES ubicaciones(id),
    CONSTRAINT FK_Mov_Usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- =========================
-- TABLA ASIGNACION_ACTIVOS
-- =========================
CREATE TABLE asignacion_activos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME,
    estatus VARCHAR(12) DEFAULT 'Activa',
    motivo VARCHAR(150),
    id_usuario INT NOT NULL,
    id_activo INT NOT NULL,
    CONSTRAINT CK_estatus_asignacion CHECK (estatus IN ('Activa','Finalizada')),
    CONSTRAINT FK_Asig_Usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    CONSTRAINT FK_Asig_Activo FOREIGN KEY (id_activo) REFERENCES activos(id)
);

-- =========================
-- TABLA QR_ACTIVOS
-- =========================
CREATE TABLE qr_activos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    valor_qr VARCHAR(255) NOT NULL,
    fecha_generacion DATETIME DEFAULT GETDATE(),
    vigente BIT DEFAULT 1,
    motivo_revocacion VARCHAR(150),
    id_activo INT NOT NULL,
    CONSTRAINT FK_QR_Activo FOREIGN KEY (id_activo) REFERENCES activos(id)
);