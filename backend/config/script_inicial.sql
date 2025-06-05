-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS students_db
CHARACTER SET utf8 
COLLATE utf8_unicode_ci;

-- Crear usuario de la base de datos
CREATE USER IF NOT EXISTS 'students_user'@'localhost' IDENTIFIED BY '12345';

-- Otorgar todos los permisos sobre la base de datos
GRANT ALL PRIVILEGES ON students_db.* TO 'students_user'@'localhost';

-- Aplicar los cambios en los permisos
FLUSH PRIVILEGES;

-- Usar la base de datos
USE students_db;

-- Crear la tabla students
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    age INT NOT NULL
) ENGINE=INNODB;

-- Insertar algunos datos de prueba
INSERT INTO students (fullname, email, age) VALUES
('Ana García', 'ana@example.com', 21),
('Lucas Torres', 'lucas@example.com', 24),
('Marina Díaz', 'marina@example.com', 22);

-- Crear la tabla subjects
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=INNODB;

-- Insertar materias de prueba
INSERT INTO subjects (name) VALUES 
('Tecnologías A'), 
('Tecnologías B'), 
('Algoritmos y Estructura de Datos I'), 
('Fundamentos de Informática');

-- Crear la tabla intermedia students_subjects
-- ON DELETE RESTRICT impide eliminar estudiantes o materias si tienen relaciones activas
CREATE TABLE IF NOT EXISTS students_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    approved BOOLEAN DEFAULT FALSE,
    UNIQUE (student_id, subject_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE RESTRICT,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE RESTRICT
) ENGINE=INNODB;

-- Insertar relaciones de prueba
INSERT INTO students_subjects (student_id, subject_id, approved) VALUES
(1, 1, 1),
(2, 2, 0);

-- VOLVER TODO A CERO, BORRAR BASE DE DATOS Y USUARIO
-- REVOKE ALL PRIVILEGES, GRANT OPTION FROM 'students_user'@'localhost';
-- DROP USER 'students_user'@'localhost';
-- DROP DATABASE students_db;
