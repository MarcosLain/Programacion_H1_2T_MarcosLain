<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar usuario</title>
</head>
<body>
<?php 
 include 'db.php'; // incluye el archivo de la base de datos
 
 if (isset($_GET['id'])) { // si se ha enviado el id del usuario a eliminar
     $id = $_GET['id']; // se almacena el id en la variable $id
 
     try { // se intenta eliminar el usuario
         $stmt = $conexionsql->prepare("DELETE FROM usuarios WHERE id = :id"); // se prepara la sentencia SQL
         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
 
         if ($stmt->execute()) { // si se ha eliminado correctamente
             echo "Usuario eliminado correctamente.";
         } else { // si no se ha eliminado correctamente
             echo "Error al eliminar el usuario.";
         }
     } catch (PDOException $e) { // si hay un error
         echo "Error: " . $e->getMessage(); // se muestra el error
     }
 } else { 
     echo "No se proporcionó un ID válido.";
 }
 
 header("Location: index.php"); // se redirige a la página principal
 exit; 
?> 
</body>
<h1>Eliminar Usuario</h1> <!-- Este es el título de la página -->

<form method="POST"> <!-- Este es el formulario -->
        <label for="email">Introduzca el email del usuario que desea borrar:</label>  <!-- Este es el campo de texto -->
        <input type="text" id="email" name="email" required> <!-- Este es el campo de texto -->
        <button type="submit">Eliminar</button> <!-- Este es el botón -->
</html>
