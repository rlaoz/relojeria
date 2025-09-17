CREATE DATABASE IF NOT EXISTS manjares_de_honduras;
USE manjares_de_honduras;

DROP TABLE IF EXISTS producto_acompaniante;
DROP TABLE IF EXISTS producto_proveedor;
DROP TABLE IF EXISTS acompaniante_plato;
DROP TABLE IF EXISTS menu_plato;
DROP TABLE IF EXISTS bitacora_menu;
DROP TABLE IF EXISTS tel_proveedor;
DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS platos;
DROP TABLE IF EXISTS acompaniante;
DROP TABLE IF EXISTS proveedores;
DROP TABLE IF EXISTS productos;

-- ==========================================
-- TABLA PRODUCTOS
-- ==========================================
CREATE TABLE productos (
    cod_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre_producto VARCHAR(45) NOT NULL,
    ubicacion_bodega VARCHAR(60) NOT NULL,
    cant_actual INT NOT NULL,
    precio_costo DECIMAL(10,2) NOT NULL,
    precio_venta DECIMAL(10,2) NOT NULL
);

-- ==========================================
-- TABLA PROVEEDORES
-- ==========================================
CREATE TABLE proveedores (
    cod_proveedor INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    direccion VARCHAR(60) NOT NULL,
    RTN VARCHAR(45) NOT NULL,
    ciudad_actual VARCHAR(45) NOT NULL
);

-- ==========================================
-- TELEFONO PROVEEDOR
-- ==========================================
CREATE TABLE tel_proveedor (
    cod_telefono INT PRIMARY KEY AUTO_INCREMENT,
    cod_proveedor INT,
    telefono VARCHAR(20),
    FOREIGN KEY (cod_proveedor) REFERENCES proveedores(cod_proveedor)
);

-- ==========================================
-- PRODUCTO - PROVEEDOR
-- ==========================================
CREATE TABLE producto_proveedor (
    cod_producto INT,
    cod_proveedor INT,
    PRIMARY KEY (cod_producto, cod_proveedor),
    FOREIGN KEY (cod_proveedor) REFERENCES proveedores(cod_proveedor),
    FOREIGN KEY (cod_producto) REFERENCES productos(cod_producto)
);

-- ==========================================
-- ACOMPAÑANTE
-- ==========================================
CREATE TABLE acompaniante (
    cod_acompaniante INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    precio DECIMAL(10,2) NOT NULL
);

-- ==========================================
-- PRODUCTO - ACOMPAÑANTE
-- ==========================================
CREATE TABLE producto_acompaniante (
    cod_producto INT,
    cod_acompaniante INT,
    cantidad DECIMAL(10,2),
    PRIMARY KEY (cod_producto, cod_acompaniante),
    FOREIGN KEY (cod_producto) REFERENCES productos(cod_producto),
    FOREIGN KEY (cod_acompaniante) REFERENCES acompaniante(cod_acompaniante)
);

-- ==========================================
-- PLATOS
-- ==========================================
CREATE TABLE platos (
    cod_plato INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(60) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    fecha_hora_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_hora_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- ==========================================
-- ACOMPAÑANTE - PLATO
-- ==========================================
CREATE TABLE acompaniante_plato (
    cod_plato INT,
    cod_acompaniante INT,
    PRIMARY KEY (cod_plato, cod_acompaniante),
    FOREIGN KEY (cod_plato) REFERENCES platos(cod_plato),
    FOREIGN KEY (cod_acompaniante) REFERENCES acompaniante(cod_acompaniante)
);

-- ==========================================
-- MENÚ
-- ==========================================
CREATE TABLE menu (
    cod_menu INT PRIMARY KEY AUTO_INCREMENT,
    fecha_elaboracion DATE NOT NULL,
    descripcion VARCHAR(100) NOT NULL,
    fecha_hora_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_hora_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- ==========================================
-- MENÚ - PLATO
-- ==========================================
CREATE TABLE menu_plato (
    cod_plato INT,
    cod_menu INT,
    cant_producir INT NOT NULL,
    existencia_actual INT NOT NULL,
    PRIMARY KEY (cod_plato, cod_menu),
    FOREIGN KEY (cod_plato) REFERENCES platos(cod_plato),
    FOREIGN KEY (cod_menu) REFERENCES menu(cod_menu)
);

-- ==========================================
-- BITÁCORA DE MENÚ
-- ==========================================
CREATE TABLE bitacora_menu (
    cod_bitacora INT PRIMARY KEY AUTO_INCREMENT,
    cod_menu INT,
    usuario VARCHAR(50) NOT NULL,
    desc_operacion VARCHAR(150) NOT NULL,
    fecha_hora_operacion DATETIME NOT NULL,
    FOREIGN KEY (cod_menu) REFERENCES menu(cod_menu)
);

/* INSERCION DE DATOS */

/* PRODUCTOS */
INSERT INTO productos (nombre_producto, ubicacion_bodega, cant_actual, precio_costo, precio_venta) VALUES
('Azúcar refinada', 'Estante A3', 40, 18.50, 25.00),
('Sal yodada', 'Estante S1', 35, 5.00, 9.00),
('Arroz blanco', 'Estante R2', 60, 20.00, 30.00),
('Lentejas', 'Estante L1', 25, 15.00, 22.00),
('Café molido', 'Bodega D1', 50, 30.00, 45.00),
('Mantequilla', 'Bodega D2', 20, 12.00, 20.00),
('Yogurt natural', 'Bodega D3', 18, 22.00, 32.00),
('Pepinos', 'Estante V1', 33, 6.50, 10.00),
('Manzanas rojas', 'Estante F1', 40, 15.00, 25.00),
('Naranjas', 'Estante F2', 42, 14.00, 22.00);

/* PROVEEDORES */
INSERT INTO proveedores (nombre, direccion, RTN, ciudad_actual) VALUES
('Exportadora Tropical', 'Col. San Ángel', '1111-AA-01', 'Tegucigalpa'),
('Frutas del Valle', 'Mercado San Isidro', '2222-BB-02', 'San Pedro Sula'),
('Granos de Oro', 'Zona Centro', '3333-CC-03', 'Danlí'),
('Café Montaña', 'Barrio El Carmen', '4444-DD-04', 'La Esperanza'),
('Distribuidora El Surco', 'Col. San Francisco', '5555-EE-05', 'Comayagua'),
('Lácteos El Buen Gusto', 'Col. Santa Fe', '6666-FF-06', 'Tegucigalpa'),
('Frigorífico del Norte', 'Zona Industrial', '7777-GG-07', 'Choloma'),
('Distribuciones López', 'Barrio La Merced', '8888-HH-08', 'Santa Rosa de Copán'),
('Verduras Selectas', 'Col. 15 de Septiembre', '9999-II-09', 'Juticalpa'),
('Jugos Naturales HN', 'Col. El Bosque', '1010-JJ-10', 'Choluteca');

/* ACOMPAÑANTES */
INSERT INTO acompaniante (nombre, precio) VALUES
('tortillas de maíz', 12.00),
('ensalada de repollo', 18.50),
('frijoles fritos', 20.00),
('chismol', 15.00),
('papas al horno', 25.00);

/* PLATOS */
INSERT INTO platos (nombre, precio) VALUES
('Baleadas sencillas', 35.00),
('Baleadas especiales', 55.00),
('Tacos hondureños', 70.00),
('Pollo frito tradicional', 85.00),
('Filete de res asado', 160.00);

/* MENÚS */
INSERT INTO menu (fecha_elaboracion, descripcion) VALUES
('2025-07-15', 'Menu mitad de mes'),
('2025-07-20', 'Menu fin de semana'),
('2025-07-25', 'Menu aniversario'),
('2025-07-28', 'Menu especial feriado'),
('2025-07-31', 'Menu cierre de mes');

/* BITÁCORA MENÚ */
INSERT INTO bitacora_menu (cod_menu, usuario, desc_operacion, fecha_hora_operacion) VALUES
(1, 'Carlos M', 'Inserción de menú vegetariano', '2025-07-01 08:30:00'),
(2, 'María L', 'Actualización de precios de platos', '2025-07-02 10:45:00'),
(3, 'Lissy G', 'Eliminación de menú de temporada', '2025-07-03 11:00:00'),
(4, 'Esteban', 'Inserción de menú infantil', '2025-07-05 12:15:00'),
(5, 'Sara L', 'Corrección de descripción en menú especial', '2025-07-06 09:50:00'),
(5, 'Andrea', 'Reactivación de menú regional', '2025-07-08 07:40:00');

/* PRODUCTO - ACOMPAÑANTE */
   
/* ACOMPAÑANTE - PLATO */
INSERT INTO acompaniante_plato (cod_plato, cod_acompaniante) VALUES
(1, 1),
(2, 3),
(3, 4),
(4, 2),
(5, 5);

/* MENÚ - PLATO */
INSERT INTO menu_plato (cod_plato, cod_menu, cant_producir, existencia_actual) VALUES
(1, 1, 40, 25),
(2, 2, 30, 15),
(3, 3, 35, 20),
(4, 4, 25, 10),
(5, 5, 20, 12);

/* PRODUCTO - PROVEEDOR */
INSERT INTO producto_proveedor (cod_producto, cod_proveedor) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(5, 2),
(8, 5);

/* TELÉFONOS DE PROVEEDORES */
INSERT INTO tel_proveedor (cod_proveedor, telefono) VALUES
(1, '22334455'),
(1, '99776655'),
(2, '22446688'),
(3, '99663322'),
(4, '22331144'),
(5, '88552233'),
(5, '22998877'),
(6, '99112233'),
(7, '22332255'),
(8, '88334455'),
(9, '22997766'),
(10, '33445566'),
(10, '99775544');


/* CREACION DE TRIGGERS  PARA PLATOS*/

DELIMITER //

CREATE TRIGGER insercion_fecha_plato
BEFORE INSERT ON plato
FOR EACH ROW
BEGIN
    SET NEW.fecha_hora_creacion = NOW();
END;
//

DELIMITER ;


DELIMITER //

CREATE TRIGGER actualizar_fecha_plato
BEFORE UPDATE ON plato
FOR EACH ROW
BEGIN
    SET NEW.fecha_hora_modificacion = NOW();
END;
//

DELIMITER ;


/* CREACION DE TRIGGERS  PARA MENUS*/

DELIMITER //

CREATE TRIGGER insercion_fecha_menu
BEFORE INSERT ON menu
FOR EACH ROW
BEGIN
    SET NEW.fecha_hora_creacion = NOW();
END;
//


DELIMITER //

CREATE TRIGGER modificacion_fecha_menu
BEFORE UPDATE ON menu
FOR EACH ROW
BEGIN
    SET NEW.fecha_hora_modificacion = NOW();
END;
//