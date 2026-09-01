<?php 

session_start();    

$_SESSION["registro"] ??= [];

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $nombre=trim($_POST["nombre_estudiante"]);
    $carnet=trim($_POST["carnet_estudiante"]);
    $notas = $_POST["notas"];
    
    $_SESSION['registro'][]=["nombre" => $nombre, "carnet" => $carnet, "notas"=> $notas];
    
    echo $nombre," ";
    var_dump($_SESSION["registro"]);
}else{
    echo "Datos por get";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<div>
    <form action="" method="post">
        <label for="">Nombre</label>
        <input type="text" name="nombre_estudiante">
        <label for="">Carnet</label>
        <input type="text" name="carnet_estudiante">
        <label for="">Nota 1</label>
        <input type="text" name="notas[]">
        <label for="">Nota 2</label>
        <input type="text" name="notas[]">
        <label for="">Nota 3</label>
        <input type="text" name="notas[]">

        <button type="submit">Enviar</button>
    </form>
</div>

</body>
</html>