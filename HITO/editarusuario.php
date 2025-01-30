<?php
include 'db.php'; // Conectamos a la base de datos

if (isset($_GET['id'])) { // Si el id existe en la URL
    $id = $_GET['id']; // Obtenemos el id de la URL
    $stmt = $conexionsql->prepare("SELECT * FROM usuarios WHERE id = :id"); // Preparamos la consulta
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Enlazamos el id a la consulta
    $stmt->execute(); // Ejecutamos la consulta
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtenemos el usuario de la base de datos
    if (!$usuario) { // Si el usuario no existe
        echo "Usuario no encontrado."; // Mostramos un mensaje de error
        exit; // Salimos del script
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Si el método de solicitud es POST
    $nombre = $_POST['nombre']; // Obtenemos los datos del formulario
    $apellidos = $_POST['apellidos'];
    $edad = $_POST['edad'];
    $plan_base = $_POST['plan_base'];
    $paquete_deporte = isset($_POST['paquete_deporte']) ? 1 : 0;
    $paquete_cine = isset($_POST['paquete_cine']) ? 1 : 0;
    $paquete_infantil = isset($_POST['paquete_infantil']) ? 1 : 0;
    $tipo_suscripcion = $_POST['tipo_suscripcion'];

    // Comprobamos si el email ya existe
    $stmt = $conexionsql->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email AND id != :id");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        echo "El correo electrónico ya está registrado en otro usuario.";
        exit;
    }

    // Comprobamos los requisitos según la edad.
    if ($edad < 18 && $paquete_infantil == 0) {
        echo "Los menores de 18 años solo pueden contratar el Pack Infantil.";
        exit;
    }

    if ($edad < 18 && $paquete_infantil == 1 && ($paquete_deporte == 1 || $paquete_cine == 1)) {
        echo "Los menores de 18 años solo pueden contratar el Pack Infantil.";
        exit;
    }

    // Comprobamos los requisitos para el plan básico
    if ($plan_base == 'Básico' && ($paquete_deporte + $paquete_cine + $paquete_infantil) > 1) {
        echo "El Plan Básico solo permite un paquete adicional.";
        exit;
    }

    // Comprobamos los requisitos para el Pack Deporte
    if ($paquete_deporte && $tipo_suscripcion != 'Anual') {
        echo "El Pack Deporte solo puede ser contratado con suscripción anual.";
        exit;
    }

    // Actualizamos los datos del usuario en la base de datos
    $stmt = $conexionsql->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, edad = ?, plan_base = ?, paquete_deporte = ?, paquete_cine = ?, paquete_infantil = ?, tipo_suscripcion = ? WHERE id = ?");
    $stmt->execute([$nombre, $apellidos, $edad, $plan_base, $paquete_deporte, $paquete_cine, $paquete_infantil, $tipo_suscripcion, $id]);

    // Redireccionamos a la página de inicio
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="estiloeditar.css">
</head>
<body>
    <nav> <!-- Barra de navegación -->
        <a href="index.php">
            <img src="STREAMWEB.png" alt="STREAMWEB" width="250" height="200">
        </a>
        <a href="index.php">Inicio</a>
        <a href="añadirusuario.php">Añadir Usuario</a>
    </nav>

    <h1>Editar Usuario</h1>
    <!-- Formulario de envío de datos -->
    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required> 
        
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
        
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled> <!-- AÑADIMOS EL DISABLED PARA QUE EL CORREO NO SE PUEDA MODIFICAR -->
        
        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($usuario['edad']); ?>" required> 
        
        <label for="plan_base">Plan Base:</label>
        <select id="plan_base" name="plan_base" required>
            <option value="Básico" <?php if ($usuario['plan_base'] == 'Básico') echo 'selected'; ?>>Básico</option>
            <option value="Estándar" <?php if ($usuario['plan_base'] == 'Estándar') echo 'selected'; ?>>Estándar</option>
            <option value="Premium" <?php if ($usuario['plan_base'] == 'Premium') echo 'selected'; ?>>Premium</option>
        </select>

        <label>Paquetes Adicionales:</label>
        <input type="checkbox" id="paquete_deporte" name="paquete_deporte" <?php if ($usuario['paquete_deporte']) echo 'checked'; ?> <?php if ($usuario['tipo_suscripcion'] == 'Mensual') echo 'disabled'; ?>> Deporte
        <input type="checkbox" id="paquete_cine" name="paquete_cine" <?php if ($usuario['paquete_cine']) echo 'checked'; ?>> Cine
        <input type="checkbox" id="paquete_infantil" name="paquete_infantil" <?php if ($usuario['paquete_infantil']) echo 'checked'; ?>> Infantil
        <br>

        <label for="tipo_suscripcion">Duración de la Suscripción:</label>
        <select id="tipo_suscripcion" name="tipo_suscripcion" required>
            <option value="Mensual" <?php if ($usuario['tipo_suscripcion'] == 'Mensual') echo 'selected'; ?>>Mensual</option>
            <option value="Anual" <?php if ($usuario['tipo_suscripcion'] == 'Anual') echo 'selected'; ?>>Anual</option>
        </select>
        
        <button type="submit">Actualizar</button> <!-- Botón de actualizar -->
    </form>
    
    <a href="index.php">Volver al inicio</a> <!-- Enlace para volver al inicio -->
    
</body>
</html>
