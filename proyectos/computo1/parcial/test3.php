<?php 

session_start();

$_SESSION["reservas"] ??= [];

$productos = [
    "Sandwich de pollo" => ["categoria" => "comida","precio" => 3.50,"existencias"=> 10],
    "porcion de pastel" => ["categoria" => "postre","precio" => 2.00,"existencias"=> 8],
    "Cafe americano" => ["categoria" => "bebida","precio" => 1.25,"existencias"=> 15]
];

$opciones = [
    "tipo" => ["estudiante","general"],
    "entrega" => ["en local","para llevar"]
];

$errores = [];
$datos = [];

function calcular(array $datos){
    $subtotal = $datos["producto"]["precio"]*$datos["cantidad"];
    $descuento = 0;

    if($datos["tipo"] === "estudiante"){
        $descuento += 0.1;
    }

    if($datos["cantidad"] > 5){
        $descuento += 0.05;
    }

    $precio_final = $subtotal * (1-$descuento);

    if($datos["entrega"] === "para llevar"){
        $precio_final += 0.5*$datos["cantidad"];
    }

    if($datos["cubiertos"] === "si"){
        $precio_final += 0.1*$datos["cantidad"];
    }
    
    return ["subtotal" => $subtotal,"descuento" => ($descuento * 100) . " %","precio_final" => $precio_final, "clasificacion" => $datos["cantidad"]<5 ? "individual" : "grupal"];
}

if($_SERVER["REQUEST_METHOD"] ==="POST"){
    foreach([
        "nombre",
        "correo",
        "cantidad",
        "tipo",
        "entrega",
        "producto"
    ] as $value){
        $datos[$value] = is_string($_POST[$value] ?? null) ? trim($_POST[$value]) : "";
        if($datos[$value] === ""){ $errores[] = "el campo de ".$value." no puede estar vacio";}
    }

    if(strlen($datos["nombre"]) < 3){ $errores[] = "no se pueden nombres de menos de 3 letras";}

    $nombre_producto = $datos["producto"];
    $datos["producto"] = $productos[$nombre_producto];

    $datos["cubiertos"] = isset($_POST["cubiertos"]) ? "si" : "no";

    if($datos["producto"]["categoria"] === "bebida" && $datos["cubiertos"] === "si"){
        $errores[] = "no se puede pedir bebidas con cubiertos";
    }

    $pollo = 0;
    $pastel = 0;
    $cafe = 0;
    foreach($_SESSION["reservas"] as $value){
       switch ($value["producto"]){
        case "Sandwich de pollo":
            $pollo += $value["cantidad"];
            break;
        case "porcion de pastel":
            $pastel+= $value["cantidad"];
            break;
        case "Cafe americano":
            $cafe+= $value["cantidad"];
            break; 
       }
    }

    switch($nombre_producto){
        case "Sandwich de pollo":
            if($datos["cantidad"] > $productos[$nombre_producto]['existencias']-$pollo){
                $errores[] = "No hay suficientes existencias";
            }
            break;
        case "porcion de pastel":
            if($datos["cantidad"] > $productos[$nombre_producto]['existencias']-$pastel){
                $errores[] = "No hay suficientes existencias";
            }
            break;
        case "Cafe americano":
            if($datos["cantidad"] > $productos[$nombre_producto]['existencias']-$cafe){
                $errores[] = "No hay suficientes existencias";
            }
            break; 
       }



    if(!$errores){
        $datos2 = calcular($datos);
        $recibo =[
            "nombre" => $datos["nombre"],"correo"=>$datos["correo"],"producto"=>$nombre_producto,"cantidad"=>$datos["cantidad"],
            "precio unitario" => $datos["producto"]["precio"],"tipo de cliente" => $datos["tipo"],"forma de entrega" => $datos["entrega"],
            "clasificacion" => $datos2["clasificacion"], "subtotal" => $datos2["subtotal"],"descuento" => $datos2["descuento"],
            "precio_final" => $datos2["precio_final"], "cargos extra" => "0.5 por orden de cubiertos: ".($datos["cubiertos"])
        ];

        $_SESSION["reservas"][] = $recibo;
    }



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
    <form method="post">
        <label for="nombre">nombre</label>
        <input type="text" name="nombre" ><br>
        <label for="correo">correo</label>
        <input type="email" name="correo" ><br>
        <label for="cantidad">cantidad</label>
        <input type="number" name="cantidad" min="0"><br>

        <?php foreach($opciones as $key => $value): ?>
            <label for="<?= $key ?>"><?= $key ?></label>
            <select name="<?= $key ?>">
                <?php foreach($value as $option) : ?>
                    <option value="<?= $option ?>"><?= $option ?></option>
                <?php endforeach; ?>
            </select><br>
        <?php endforeach; ?>
        <select name="producto">
                <?php foreach($productos as $key => $option) : ?>
                    <option value="<?= $key ?>">  <?= $key ?>  ---  precio: <?= $option["precio"] ?>  --- existencias: <?= $option["existencias"] ?></option>
                <?php endforeach; ?>
        </select><br>

        <label for="cubiertos">Cubiertos</label>
        <input type="checkbox" name="cubiertos" value=""><br>
        <button>pedir</button>
    </form>

    <?php foreach ($errores as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <?php if(isset($recibo)): ?>
        <h2>Comprobante</h2>
        <?php foreach ($recibo as $key => $value): ?>
            <p><?= $key ?>: <?= $value ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($_SESSION["reservas"]): ?>
        <table border="1">
            <thead>
                <tr>
                    <?php foreach(array_keys($_SESSION["reservas"][0]) as $key):?>
                        <th><?= $key ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($_SESSION["reservas"] as $key => $value): ?>
                    <tr>
                        <?php foreach($value as $option): ?>
                             <td><?= $option ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>