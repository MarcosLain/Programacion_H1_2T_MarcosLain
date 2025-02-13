drop database if exists users;
create database users;
use users;

create table usuarios (
    nombre varchar(50) not null,
    correo varchar(100) primary key not null UNIQUE,
    contraseña varchar(255) not null
);

create table tareas (
    id int AUTO_INCREMENT primary key,
    correo varchar(100) not null,
    tarea varchar(255) not null,
    descripcion text not null,
    fecha date not null,
    estado ENUM('Pendiente', 'Completado') not null,
    foreign key (correo) REFERENCES usuarios(correo)
);

select * from usuarios;
select * from tareas;
