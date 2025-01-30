<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Usuario</title>
    <link rel="stylesheet" href="estiloañadir.css">
</head>
<body>
    <!-- Barra de navegación !-->
<nav>
    <a href="index.php">
    <img src="STREAMWEB.png" alt="STREAMWEB" width="250" height="200">
    </a>
    <a href="index.php">Inicio</a>
    <a href="añadirusuario.php">Añadir Usuario</a>
</nav>

<h1>Añadir Usuario</h1>
 <!-- Formulario de registro !-->
<form method="POST" action="añadirusuario.php">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required><br>

    <label for="apellidos">Apellidos:</label>
    <input type="text" id="apellidos" name="apellidos" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="edad">Edad:</label>
    <input type="number" id="edad" name="edad" required><br>

    <label for="plan_base">Plan Base:</label>
    <select id="plan_base" name="plan_base" required>
        <option value="Básico">Básico</option>
        <option value="Estándar">Estándar</option>
        <option value="Premium">Premium</option>
    </select><br>

    <label for="paquete_deporte">Paquete Deporte:</label>
    <input type="checkbox" id="paquete_deporte" name="paquete_deporte"><br>

    <label for="paquete_cine">Paquete Cine:</label>
    <input type="checkbox" id="paquete_cine" name="paquete_cine"><br>

    <label for="paquete_infantil">Paquete Infantil:</label>
    <input type="checkbox" id="paquete_infantil" name="paquete_infantil"><br>

    <label for="tipo_suscripcion">Tipo de Suscripción:</label>
    <select id="tipo_suscripcion" name="tipo_suscripcion" required>
        <option value="Mensual">Mensual</option>
        <option value="Anual">Anual</option>
    </select><br>
    <!-- Botón de envío !-->

    <button type="submit">Añadir Usuario</button>
</form>

<?php
// Conexión a la base de datos
include 'db.php';
// Iniciamos el mensaje de error vacío.
$errorMessage = "";
// Si el formulario se ha enviado.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenemos los datos del formulario.
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];
    $plan_base = $_POST['plan_base'];
    $paquete_deporte = isset($_POST['paquete_deporte']) ? 1 : 0;
    $paquete_cine = isset($_POST['paquete_cine']) ? 1 : 0;
    $paquete_infantil = isset($_POST['paquete_infantil']) ? 1 : 0;
    $tipo_suscripcion = $_POST['tipo_suscripcion'];
    // Creamos la sentencia SQL para insertar los datos en la base de datos.
    $stmt = $conexionsql->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    // Si el correo existe, mostramos un mensaje de error.
    if ($existingUser) {
        $errorMessage = "ERROR. El correo electrónico $email ya está en uso.";
    // Si el correo no existe, insertamos los datos en la base de datos.
    } else {

    // Comprobamos los requisitos segun la edad.
    if ($edad < 18 && $paquete_infantil == 0) {
        echo "Los menores de 18 años solo pueden contratar el Pack Infantil.";
        exit;
    }

    if ($edad < 18 && $paquete_infantil == 1 && $paquete_deporte == 1 && $paquete_cine == 1)  {
        echo "Los menores de 18 años solo pueden contratar el Pack Infantil.";
        exit;
    }
    // Comprobamos los requisitos para el plan básico
    if ($plan_base == 'Básico' && ($paquete_deporte + $paquete_cine + $paquete_infantil) > 1) {
        echo "El Plan Básico solo permite un paquete adicional.";
        exit;
    }
    // Comprobamos los requisitos para el plan deporte
    if ($paquete_deporte && $tipo_suscripcion != 'Anual') {
        echo "El Pack Deporte solo puede ser contratado con suscripción anual.";
        exit;
    }
        $stmt = $conexionsql->prepare("INSERT INTO usuarios (nombre, apellidos, email, edad, plan_base, paquete_deporte, paquete_cine, paquete_infantil, tipo_suscripcion)
                                      VALUES (:nombre, :apellidos, :email, :edad, :plan_base, :paquete_deporte, :paquete_cine, :paquete_infantil, :tipo_suscripcion)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':plan_base', $plan_base);
        $stmt->bindParam(':paquete_deporte', $paquete_deporte);
        $stmt->bindParam(':paquete_cine', $paquete_cine);
        $stmt->bindParam(':paquete_infantil', $paquete_infantil);
        $stmt->bindParam(':tipo_suscripcion', $tipo_suscripcion);
    // Ejecutamos la sentencia SQL.
        $stmt->execute();
    }
}
?>
    

<?php if ($errorMessage): ?>    <!-- Si hay un mensaje de error, mostramos un mensaje de error -->
    <div class="error-message"> <!-- Mostramos el mensaje de error -->
        <p><?php echo $errorMessage; ?></p> 
    </div>
<?php endif; ?> <!-- Si no hay un mensaje de error, mostramos el formulario -->

</body>
</html>
