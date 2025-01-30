<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
$host = 'localhost';
$dbname = 'streamweb';
$username = 'root';
$password = 'campusfp';
// Conectamos a la base de datos
try { // Intentamos conectar a la base de datos
    $conexionsql = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexionsql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { // Si no se puede conectar muestra mensaje de error
    echo "Error de conexión: " . $e->getMessage();
}
?>
</body>
</html>