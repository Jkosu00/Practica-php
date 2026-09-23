<?php

session_start();

// Si el usuario no inició sesión, regresa al formulario
if (!isset($_SESSION["usuario_logeado"])) {
    header("Location: index.html");
    exit;
}

$usuario = $_SESSION["usuario_logeado"];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

    <h1>Panel principal</h1>

    <p>
        Bienvenido,
        <?= htmlspecialchars($usuario, ENT_QUOTES, "UTF-8") ?>
    </p>

    <nav>
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Mi perfil</a></li>
            <li><a href="#">Configuración</a></li>
        </ul>
    </nav>

    <a href="login.php">Cerrar sesión</a>

</body>
</html>