<?php 

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $nombre=trim($_POST["nombre_estudiante"]);
    $carnet=trim($_POST["carnet_estudiante"]);
    $notas = $_POST["notas"];
    echo "Nombre: ",$nombre, " Carnet: ",$carnet;
}else{
    echo "Datos por get";
}

?>