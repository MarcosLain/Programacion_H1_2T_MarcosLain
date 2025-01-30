<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav> <!-- Barra de navegación -->
    <a href="index.php">
        <img src="STREAMWEB.png" alt="STREAMWEB" width="250" height="200">
    </a>
    <a href="index.php">Inicio</a>
    <a href="añadirusuario.php">Añadir Usuario</a>
</nav>
<h1>USUARIOS REGISTRADOS</h1>
<table> <!-- Tabla de usuarios -->
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Edad</th>
            <th>Plan Base</th> 
            <th>Paquetes Adicionales</th> 
            <th>Tipo de Suscripción</th>
            <th>Coste Total Mensual</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>

<?php
include 'db.php'; // Conexión a la base de datos

$stmt = $conexionsql->query("SELECT * FROM usuarios"); 
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Carga los datos de la base de datos -->
<?php foreach ($usuarios as $usuario): ?> 
    <tr>
        <td><?php echo $usuario['nombre']; ?></td>
        <td><?php echo $usuario['apellidos']; ?></td>
        <td><?php echo $usuario['email']; ?></td>
        <td><?php echo $usuario['edad']; ?></td>

        <!-- Plan Base con su precio -->
        <td>
            <?php 
            $planBasePrecio = '';
            switch ($usuario['plan_base']) {
                case 'Básico': $planBasePrecio = 'Básico (9.99 €)'; break;
                case 'Estándar': $planBasePrecio = 'Estándar (13.99 €)'; break;
                case 'Premium': $planBasePrecio = 'Premium (17.99 €)'; break;
            }
            echo $planBasePrecio;
            ?>
        </td>

        <!-- Paquetes Adicionales con sus precios -->
        <td>
            <?php 
            $paquetes = [];
            if ($usuario['paquete_deporte']) $paquetes[] = 'Deporte (6.99 €)';
            if ($usuario['paquete_cine']) $paquetes[] = 'Cine (7.99 €)';
            if ($usuario['paquete_infantil']) $paquetes[] = 'Infantil (4.99 €)';
            echo implode(', ', $paquetes);
            ?>
        </td>

        <td><?php echo $usuario['tipo_suscripcion']; ?></td>

        <!-- Cálculo del coste mensual -->
        <td>
            <?php
            $cost = 0;
            switch ($usuario['plan_base']) {
                case 'Básico': $cost += 9.99; break;
                case 'Estándar': $cost += 13.99; break;
                case 'Premium': $cost += 17.99; break;
            }
            if ($usuario['paquete_deporte']) $cost += 6.99;
            if ($usuario['paquete_cine']) $cost += 7.99;
            if ($usuario['paquete_infantil']) $cost += 4.99;
            echo $cost . " €";
            ?>
        </td>

        <td>
            <a href="editarusuario.php?id=<?php echo $usuario['id']; ?>">Modificar</a>
            <a href="borrarusuario.php?id=<?php echo $usuario['id']; ?>">Eliminar</a>
        </td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>
<a href="añadirusuario.php">Añadir Usuario</a>
</body>
</html>
