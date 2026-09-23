<?php
session_start();

$_SESSION["datos"] = [];

$VIDEO_JUEGOS = [
    "MARIO" => ["nombre" => "MARIO BROS", "categoria" => "Plataforma", "costo" => 1.00],
    "MORTAL" => ["nombre" => "MORTAL KOMBAT", "categoria" => "Pelea", "costo" => 1.00],
    "ZELDA" => ["nombre" => "THE LEGEND OF ZELDA", "categoria" => "Aventura", "costo" => 2.00],
];

$error = [];
$categoria = "";
$descuento = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["nombre"]) and empty($_POST["nombre"])) {
        $error[] = "Error el campo nombre no existe o esta vacio";
    }
    if (!(isset($_POST["edad"])) or empty($_POST["edad"])) {
        $error[] = "Error el campo edad no existe o esta vacio o no es un formato valido";
    }
    if (!(isset($_POST["correo"])) or empty($_POST["correo"])) {
        $error[] = "Error el correo edad no existe o esta vacio o no es un formato valido";
    }
    if (!(isset($_POST["modalidad"])) or empty($_POST["modalidad"])) {
        $error[] = "Error el modalidad edad no existe o esta vacio o no es un formato valido";
    }
    if (!(isset($_POST["experiencia"])) or empty($_POST["experiencia"])) {
        $error[] = "Error el experiencia edad no existe o esta vacio o no es un formato valido";
    }

    if (empty($error)) {
        
      
           if ($_POST["edad"] > 15 and $_POST["experiencia"] == "Sin experencia") {
            $categoria="Aprendiendo";
           } 
           elseif($_POST["edad"] > 15 and $_POST["edad"] <= 20  and $_POST["experiencia"] == "Novato"){
            $categoria =  "Ya sabe";
           }
        

        $descuento = $_POST["experiencia"] == "Sin experencia" ? 0.1 : 0;

       // echo "Nombre" . $_POST["nombre"];
        //var_dump($VIDEO_JUEGOS[$_POST["videojuego"]]);

        $_SESSION["datos"][] = [
            "nombre" => $_POST["nombre"],
            "edad" => $_POST["edad"],
            "correo" => $_POST["correo"],
            "modalidad" => $_POST["modalidad"],
            "experiencia" => $_POST["experiencia"],
            "nombreJuego" => $VIDEO_JUEGOS[$_POST["videojuego"]]["nombre"],
            "costoJuego" => $VIDEO_JUEGOS[$_POST["videojuego"]]["costo"],
            "costoTotal" => calcularPrecio($VIDEO_JUEGOS[$_POST["videojuego"]]["costo"], $descuento)
        ];

        var_dump($_SESSION["datos"]);

    }
}

function calcularPrecio($valorInscripcion, $descuento)
{
    return $valorInscripcion - ($valorInscripcion * $descuento);
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
    <h1>
        SOLUCION DE EXAMEN
    </h1>
    <div>
        <form action="" method="post">
            <label for="">Nombre</label>
            <input type="text" name="nombre">
            <label for="">edad</label>
            <input type="text" name="edad">
            <label for="">correo</label>
            <input type="text" name="correo">
            <label for="">Seleccione un juego</label>
            <select name="videojuego" id="">
                <?php foreach ($VIDEO_JUEGOS as $key => $value): ?>
                    <option value="<?= $key ?>"><?= $value["nombre"] ?></option>
                <?php endforeach; ?>
            </select>
            <label for="">Modalidad</label>
            <input type="radio" name="modalidad" value="Presencial"> <label>Presencial</label>
            <input type="radio" name="modalidad" value="Virtual"> <label>Virtual</label>

            <label for="">Nivel de experiencia</label>
            <input type="radio" name="experiencia" value="Sin experencia"> <label>Sin experencia</label>
            <input type="radio" name="experiencia" value="Novato"> <label>Novato</label>
            <input type="submit">Enviar
        </form>
        <?php if (!empty($error)): ?>
            <?php foreach ($error as $value): ?>
                <p style="color: brown;"><?= $value ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

        <table>
            <thead>
                <th>Nombre del participante</th>
                <th>Edad</th>
                <th>correo</th>
                <th>modalidad</th>
                <th>experiencia</th>
                <th>nombre del juego</th>
                <th>total inscipcion</th>
            </thead>
            <tbody>
                <?php foreach ($_SESSION["datos"] as $value):   echo json_encode($value);?>
                    <tr>
                        <td><?= $value["nombre"] ?></td>
                        <td><?= $value["edad"] ?></td>
                        <td><?= $value["correo"] ?></td>
                        <td><?= $value["modalidad"] ?></td>
                        <td><?= $value["experiencia"] ?></td>
                        <td><?= $value["nombreJuego"] ?></td>
                        <td><?= $value["costoTotal"] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($_POST["nombre"])): ?>
            <div>
                <h1>RECIBO</h1>
                <p>Nombre: <?= htmlspecialchars($_POST["nombre"] ?? "") ?></p>
                <p>Edad: <?= htmlspecialchars($_POST["edad"] ?? "") ?></p>
                <p>Correo: <?= htmlspecialchars($_POST["correo"] ?? "") ?></p>
                <p>Juego: <?= htmlspecialchars($_POST["videojuego"] ?? "") ?></p>
            </div>
        <?php endif; ?>
        </div>
</body>

</html>