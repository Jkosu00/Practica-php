<?php

session_start();

$_SESSION["Database"] ??= [];

$metodos = [
    "Tarjeta",
    "Efectivo",
    "Credito"
];

$productos = [
    "Laptop" => 400.67,
    "Tablet" => 367.00,
    "RAM" => 67.67
];


// ==========================
// ELIMINAR REGISTRO
// ==========================

if(isset($_GET["eliminar"])){

    $id = $_GET["eliminar"];

    foreach($_SESSION["Database"] as $indice => $venta){

        if($venta["id"] == $id){

            unset($_SESSION["Database"][$indice]);

            break;
        }
    }

    $_SESSION["Database"] = array_values($_SESSION["Database"]);

    header("Location: ".$_SERVER["PHP_SELF"]);
    exit;
}



// ==========================
// CARGAR REGISTRO PARA EDITAR
// ==========================

$editar = null;

if(isset($_GET["editar"])){

    foreach($_SESSION["Database"] as $venta){

        if($venta["id"] == $_GET["editar"]){

            $editar = $venta;
            break;
        }
    }
}



// ==========================
// GUARDAR / ACTUALIZAR
// ==========================

if($_SERVER["REQUEST_METHOD"] === "POST"){


    $nombre = $_POST["nombre"] ?? "";
    $documento = $_POST["documento"] ?? "";
    $correo = $_POST["correo"] ?? "";
    $producto = $_POST["producto"] ?? "";
    $metodo = $_POST["metodo"] ?? "";
    $cantidad = $_POST["cantidad"] ?? 0;


    $subtotal = $cantidad * $productos[$producto];


    // EDITAR

    if(isset($_POST["id"]) && $_POST["id"] != ""){


        foreach($_SESSION["Database"] as $indice=>$venta){


            if($venta["id"] == $_POST["id"]){


                $_SESSION["Database"][$indice] = [

                    "id"=>$venta["id"],
                    "nombre"=>$nombre,
                    "documento"=>$documento,
                    "correo"=>$correo,
                    "producto"=>$producto,
                    "cantidad"=>$cantidad,
                    "metodo"=>$metodo,
                    "subtotal"=>$subtotal

                ];


                break;

            }

        }



    }else{


        // CREAR NUEVO

        $_SESSION["Database"][] = [

            "id"=>uniqid(),

            "nombre"=>$nombre,
            "documento"=>$documento,
            "correo"=>$correo,
            "producto"=>$producto,
            "cantidad"=>$cantidad,
            "metodo"=>$metodo,
            "subtotal"=>$subtotal

        ];

    }


    header("Location: ".$_SERVER["PHP_SELF"]);
    exit;

}



// ==========================
// ESTADISTICAS
// ==========================

$total = 0;

$contadorProductos = [];

$contadorMetodos = [];



foreach($_SESSION["Database"] as $venta){


    $total += $venta["subtotal"];



    // Productos vendidos

    $producto = $venta["producto"];


    if(!isset($contadorProductos[$producto])){

        $contadorProductos[$producto] = 0;

    }


    $contadorProductos[$producto] += $venta["cantidad"];





    // Métodos usados

    $metodo = $venta["metodo"];


    if(!isset($contadorMetodos[$metodo])){

        $contadorMetodos[$metodo] = 0;

    }


    $contadorMetodos[$metodo]++;


}



$productoMasVendido = "Sin datos";

if(count($contadorProductos)>0){

    $productoMasVendido = array_search(
        max($contadorProductos),
        $contadorProductos
    );

}



$metodoMasUsado = "Sin datos";

if(count($contadorMetodos)>0){

    $metodoMasUsado = array_search(
        max($contadorMetodos),
        $contadorMetodos
    );

}


?>


<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Sistema de ventas</title>

<link href="style.css" rel="stylesheet">

</head>


<body>


<h1>Sistema de ventas</h1>



<form method="POST">


<input 
type="hidden"
name="id"
value="<?= $editar["id"] ?? "" ?>"
>


<div>

<label>Nombre</label>

<input 
type="text"
name="nombre"
required
value="<?= $editar["nombre"] ?? "" ?>"
>

</div>



<div>

<label>Documento</label>

<input 
type="text"
name="documento"
required
value="<?= $editar["documento"] ?? "" ?>"
>

</div>



<div>

<label>Correo</label>

<input 
type="email"
name="correo"
required
value="<?= $editar["correo"] ?? "" ?>"
>

</div>



<div>

<label>Cantidad</label>

<input 
type="number"
name="cantidad"
min="1"
required
value="<?= $editar["cantidad"] ?? "" ?>"
>

</div>




<div>

<label>Método</label>

<select name="metodo">


<?php foreach($metodos as $metodo): ?>


<option

value="<?= $metodo ?>"

<?= 
(isset($editar["metodo"]) && $editar["metodo"]==$metodo)
?"selected":""
?>

>

<?= $metodo ?>


</option>


<?php endforeach; ?>


</select>


</div>





<div>

<label>Producto</label>


<select name="producto">


<?php foreach($productos as $producto=>$precio): ?>


<option

value="<?= $producto ?>"

<?= 
(isset($editar["producto"]) && $editar["producto"]==$producto)
?"selected":""
?>

>

<?= $producto ?> - $<?= $precio ?>


</option>


<?php endforeach; ?>


</select>


</div>




<button type="submit">

<?= $editar ? "Actualizar" : "Guardar" ?>

</button>


</form>





<section>


<h2>
Productos registrados:
<?= count($_SESSION["Database"]) ?>
</h2>


<h2>
Total generado:
$<?= number_format($total,2) ?>
</h2>



<h2>
Producto más vendido:
<?= $productoMasVendido ?>
</h2>



<h2>
Método más usado:
<?= $metodoMasUsado ?>
</h2>


</section>





<table>


<thead>

<tr>

<th>Nombre</th>
<th>Documento</th>
<th>Correo</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Método</th>
<th>Subtotal</th>
<th>Acciones</th>

</tr>

</thead>



<tbody>


<?php foreach($_SESSION["Database"] as $venta): ?>


<tr>


<td><?= $venta["nombre"] ?></td>

<td><?= $venta["documento"] ?></td>

<td><?= $venta["correo"] ?></td>

<td><?= $venta["producto"] ?></td>

<td><?= $venta["cantidad"] ?></td>

<td><?= $venta["metodo"] ?></td>

<td>
$<?= number_format($venta["subtotal"],2) ?>
</td>


<td>


<a href="?editar=<?= $venta["id"] ?>">
Editar
</a>


|

<a href="?eliminar=<?= $venta["id"] ?>">
Eliminar
</a>


</td>


</tr>


<?php endforeach; ?>


</tbody>


</table>



</body>

</html>