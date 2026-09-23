<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
</head>
<body>
    <form action="procesar.php" method="post">
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" name="usuario" id="usuario" required> <br><br>
        <label for="contrasenia">Contraseña:</label>
        <input type="password" name="password" id="password" required> <br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>