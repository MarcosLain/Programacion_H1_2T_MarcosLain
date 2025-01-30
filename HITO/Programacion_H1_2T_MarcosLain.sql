drop database if exists streamweb;
create database streamweb;
use streamweb;
create table usuarios (
id int auto_increment primary key,
nombre varchar (100) not null,
apellidos varchar (100) not null,
email varchar (100) not null unique,
edad int not null,
plan_base enum('Básico', 'Estándar', 'Premium') not null,
paquete_deporte boolean default 0,
paquete_cine boolean default 0,
paquete_infantil boolean default 0,
tipo_suscripcion enum ('Mensual', 'Anual') not null
);

INSERT INTO usuarios (nombre, apellidos, email, edad, plan_base, paquete_deporte, paquete_cine, paquete_infantil, tipo_suscripcion)
VALUES ('Juan', 'Pérez', 'juan.perez@example.com', 16, 'Estándar', 0, 0, 1, 'Mensual');

INSERT INTO usuarios (nombre, apellidos, email, edad, plan_base, paquete_deporte, paquete_cine, paquete_infantil, tipo_suscripcion)
VALUES ('Laura', 'Gómez', 'laura.gomez@example.com', 25, 'Básico', 0, 1, 0, 'Mensual');

INSERT INTO usuarios (nombre, apellidos, email, edad, plan_base, paquete_deporte, paquete_cine, paquete_infantil, tipo_suscripcion)
VALUES ('Carlos', 'Martínez', 'carlos.martinez@example.com', 30, 'Premium', 1, 0, 0, 'Anual');

INSERT INTO usuarios (nombre, apellidos, email, edad, plan_base, paquete_deporte, paquete_cine, paquete_infantil, tipo_suscripcion)
VALUES ('Ana', 'López', 'ana.lopez@example.com', 17, 'Estándar', 0, 0, 1, 'Anual');

select * from usuarios;

