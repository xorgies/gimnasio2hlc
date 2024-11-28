-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS gimnasio DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE gimnasio;

-- Eliminar la tabla 'planes' si ya existe
DROP TABLE IF EXISTS planes;

-- Crear la tabla 'planes' con AUTO_INCREMENT
CREATE TABLE planes (
  id int(11) NOT NULL AUTO_INCREMENT,  -- La columna 'id' ahora es AUTO_INCREMENT
  plan varchar(50) NOT NULL,
  PRIMARY KEY (id)  -- Definir la clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insertar datos en la tabla 'planes', no es necesario especificar 'id' ya que es AUTO_INCREMENT
INSERT INTO planes (plan) VALUES
('basico'),
('premium'),
('vip');

-- Eliminar la tabla 'alumnos' si ya existe
DROP TABLE IF EXISTS alumnos;

-- Crear la tabla 'alumnos'
CREATE TABLE alumnos (
  dni varchar(9) NOT NULL,
  id_plan int(11) NOT NULL,
  nombre varchar(40) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  edad int(3) NOT NULL,
  fecha_nacimiento date NOT NULL,
  PRIMARY KEY (dni),  -- Definir la clave primaria en 'dni'
  KEY FK_PLANES (id_plan)  -- Crear índice para la clave foránea
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insertar datos en la tabla 'alumnos'
INSERT INTO alumnos (dni, id_plan, nombre, edad, fecha_nacimiento) VALUES
('12344544B', 3, 'Angel', 7, '2017-04-10'),
('33334444B', 2, 'Carlos', 5, '2015-03-10'),
('76544444R', 1, 'Luis', 5, '2013-10-08'),
('98764444A', 2, 'Jose', 5, '2015-07-08');

-- Agregar la clave foránea entre 'alumnos' y 'planes'
ALTER TABLE alumnos
  ADD CONSTRAINT FK_PLANES FOREIGN KEY (id_plan) REFERENCES planes (id);

-- Clases
DROP TABLE IF EXISTS clases;

CREATE TABLE clases (
  id_clase int(11) NOT NULL AUTO_INCREMENT,  
  tipo varchar(50) NOT NULL,                 
  horario varchar(5) NOT NULL,               
  id_entrenador int(11) NOT NULL,            
  sala varchar(50) NOT NULL,                 
  PRIMARY KEY (id_clase)                     
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO clases (tipo, horario, id_entrenador, sala) VALUES
('Virtual Cycling', '06:30', 1, 'Sala SPEED'),
('Virtual Cycling', '20:00', 1, 'Sala SPEED'),
('Virtual Cycling', '21:00', 1, 'Sala SPEED'),
('Pilates', '07:30', 2, 'Sala AGILITY'),
('Pilates', '10:30', 2, 'Sala AGILITY'),
('Pilates', '19:00', 2, 'Sala AGILITY'),
('Crossfit', '07:30', 3, 'Sala CROSS'),
('Crossfit', '08:30', 3, 'Sala CROSS'),
('Crossfit', '19:00', 3, 'Sala CROSS'),
('Zumba', '10:30', 3, 'Sala RYTHM'),
('Zumba', '18:00', 3, 'Sala RYTHM'),
('Zumba', '20:00', 3, 'Sala RYTHM'),
('GAP', '08:30', 2, 'Sala AGILITY'),
('GAP', '09:30', 2, 'Sala AGILITY'),
('GAP', '18:00', 2, 'Sala AGILITY'),
('Latino', '09:30', 4, 'Sala RYTHM'),
('Latino', '11:30', 4, 'Sala RYTHM'),
('Latino', '19:00', 4, 'Sala RYTHM'),
('HIIT', '11:30', 5, 'Sala CROSS'),
('HIIT', '18:00', 5, 'Sala CROSS'),
('Aerobic', '20:00', 6, 'Sala AGILITY'); -- cambié la sala y sólo puse un horario para que no se solape, cambiar también en clases.html





-- Eliminar la tabla intermedia 'alumnos_clases' si ya existe
DROP TABLE IF EXISTS alumnos_clases;

-- Crear la tabla intermedia 'alumnos_clases' para la relación N:M
CREATE TABLE alumnos_clases (
  dni_alumno varchar(9) NOT NULL,  -- Clave foránea hacia 'alumnos'
  id_clase int(11) NOT NULL,       -- Clave foránea hacia 'clases'
  PRIMARY KEY (dni_alumno, id_clase),  -- Clave primaria compuesta
  FOREIGN KEY (dni_alumno) REFERENCES alumnos (dni) ON DELETE CASCADE, -- Relación con 'alumnos'
  FOREIGN KEY (id_clase) REFERENCES clases (id_clase) ON DELETE CASCADE -- Relación con 'clases'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insertar datos en la tabla intermedia 'alumnos_clases'
INSERT INTO alumnos_clases (dni_alumno, id_clase) VALUES
('12344544B', 1), -- Angel está inscrito en Yoga
('33334444B', 2), -- Carlos está inscrito en Pilates
('76544444R', 3), -- Luis está inscrito en Crossfit
('98764444A', 4), -- Jose está inscrito en Zumba
('12344544B', 2), -- Angel también está inscrito en Pilates
('76544444R', 1); -- Luis también está inscrito en Yoga



-- Eliminar la tabla 'salas' si ya existe
DROP TABLE IF EXISTS salas;

-- Crear la tabla 'salas' con AUTO_INCREMENT
CREATE TABLE salas (
  id int(11) NOT NULL AUTO_INCREMENT,  -- La columna 'id' ahora es AUTO_INCREMENT
  sala varchar(50) NOT NULL,
  PRIMARY KEY (id)  -- Definir la clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insertar datos en la tabla 'salas', no es necesario especificar 'id' ya que es AUTO_INCREMENT
INSERT INTO salas (sala) VALUES
('SALA SPEED'),
('SALA AGILITY'),
('SALA CROSS'),
('SALA RYTHM');


-- Eliminar la tabla 'especialidades' si ya existe
DROP TABLE IF EXISTS especialidades;

-- Crear la tabla 'especialidades' con AUTO_INCREMENT
CREATE TABLE especialidades (
  id int(11) NOT NULL AUTO_INCREMENT,  -- La columna 'id' ahora es AUTO_INCREMENT
  especialidad varchar(50) NOT NULL,
  PRIMARY KEY (id)  -- Definir la clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insertar datos en la tabla 'especialidades', no es necesario especificar 'id' ya que es AUTO_INCREMENT
INSERT INTO especialidades (especialidad) VALUES
('Pilates'),
('GAP'),
('CrossFit'),
('Aerobic'),
('Yoga Nidra'),
('Zumba');


-- Eliminar la tabla 'entrenadores' si ya existe
DROP TABLE IF EXISTS entrenadores;

-- Crear la tabla 'entrenadores'
CREATE TABLE entrenadores (
  dni varchar(9) NOT NULL,
  enfoques varchar(100) NOT NULL,
  nombre varchar(40) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  edad int(3) NOT NULL,
  fecha_nacimiento date NOT NULL,
  PRIMARY KEY (dni)  -- Definir la clave primaria en 'dni'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insertar datos en la tabla 'entrenadores'
INSERT INTO entrenadores (dni, enfoques, nombre, edad, fecha_nacimiento) VALUES
('11111111B', 'enfoque1', 'Sergio', 25, '1997-04-10'),
('22222222B', 'enfoque2', 'Victor', 34, '1994-03-10'),
('33333333R', 'enfoque3', 'Paula', 28, '1992-10-08'),
('44444444A', 'enfoque4, enfoque5', 'Noelia', 36, '1991-07-08');


-- Eliminar la tabla intermedia 'entrenadores_salas' si ya existe
DROP TABLE IF EXISTS entrenadores_salas;

-- Crear la tabla intermedia 'entrenadores_salas' para la relación N:M
CREATE TABLE entrenadores_salas (
  dni_entrenador varchar(9) NOT NULL,  -- Clave foránea hacia 'entrenadores'
  id_sala int(11) NOT NULL,       -- Clave foránea hacia 'salas'
  PRIMARY KEY (dni_entrenador, id_sala),  -- Clave primaria compuesta
  FOREIGN KEY (dni_entrenador) REFERENCES entrenadores (dni) ON DELETE CASCADE, -- Relación con 'entrenadores'
  FOREIGN KEY (id_sala) REFERENCES salas (id) ON DELETE CASCADE -- Relación con 'salas'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insertar datos en la tabla intermedia 'entrenadores_salas'
INSERT INTO entrenadores_salas (dni_entrenador, id_sala) VALUES
('11111111B', 1),
('11111111B', 2),
('22222222B', 1),
('22222222B', 3),
('33333333R', 1),
('44444444A', 3);

-- Eliminar la tabla intermedia 'entrenadores_especialidades' si ya existe
DROP TABLE IF EXISTS entrenadores_especialidades;

-- Crear la tabla intermedia 'entrenadores_especialidades' para la relación N:M
CREATE TABLE entrenadores_especialidades (
  dni_entrenador varchar(9) NOT NULL,  -- Clave foránea hacia 'entrenadores'
  id_especialidad int(11) NOT NULL,       -- Clave foránea hacia 'especialidades'
  PRIMARY KEY (dni_entrenador, id_especialidad),  -- Clave primaria compuesta
  FOREIGN KEY (dni_entrenador) REFERENCES entrenadores (dni) ON DELETE CASCADE, -- Relación con 'entrenadores'
  FOREIGN KEY (id_especialidad) REFERENCES especialidades (id) ON DELETE CASCADE -- Relación con 'especialidades'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insertar datos en la tabla intermedia 'entrenadores_especialidades'
INSERT INTO entrenadores_especialidades (dni_entrenador, id_especialidad) VALUES
('11111111B', 1),
('11111111B', 2),
('22222222B', 1),
('22222222B', 3),
('33333333R', 1),
('44444444A', 3);

-- Confirmar las transacciones
COMMIT;
