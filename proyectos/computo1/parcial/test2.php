<?php

session_start();

$_SESSION["reservas"] ??= [];

$opciones = [
    "usuario" => ["profesor", "estudiante", "general"],
    "disciplina" => ["futbol", "basket", "tennis", "volleyball"]
];

$espacios = [
    "Espacio futbol" => ["disciplina" => "futbol","capacidad" => 12, "precio" => 5],
    "Espacio basket" => ["disciplina" => "basket","capacidad" => 10,"precio" => 6],
    "Espacio volleyball" => ["disciplina" => "volleyball","capacidad" => 10,"precio" => 7],
    "Espacio tennis" => ["disciplina" => "tennis","capacidad" => 4,"precio" => 8]
];

$errores = [];
$datos = [];

function calcular_precio(array $datos): float
{
    $subtotal = $datos["espacio"]["precio"] * $datos["horas"];

    if ($datos["usuario"] === "profesor") {
        return $subtotal * 0.9;
    } elseif ($datos["usuario"] === "estudiante") {
        return $subtotal * 0.8;
    } else {
        return $subtotal;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach (
        [
            "nombre",
            "correo",
            "participantes",
            "horas",
            "usuario",
            "disciplina",
            "espacio"
        ] as $campo
    ) {
        $datos[$campo] = is_string($_POST[$campo] ?? null)
            ? trim($_POST[$campo])
            : "";

        if ($datos[$campo] === "") {
            $errores[] = "El campo $campo no puede estar vacío";
        }
    }

    $nombre_espacio = $datos["espacio"];

    $datos["espacio"] = $espacios[$datos["espacio"]];

        foreach ($_SESSION["reservas"] as $reserva) {
            if ($reserva["espacio"] === $nombre_espacio) {
                $errores[] = "El espacio está ocupado";
                break;
            }
        }

        if ( $datos["participantes"] > $datos["espacio"]["capacidad"]) 
        {
            $errores[] = "El espacio no cuenta con dicha capacidad";
        }

        if (
            $datos["disciplina"] !==
            $datos["espacio"]["disciplina"]
        ) {
            $errores[] =
                "El espacio no es para esta disciplina";
        }
    }

    if (!$errores) {
        $precio_final = calcular_precio($datos);

        $subtotal =
            $datos["espacio"]["precio"] *
            $datos["horas"];

        $recibo = [
            "nombre" => $datos["nombre"],
            "correo" => $datos["correo"],
            "participantes" => $datos["participantes"],
            "horas" => $datos["horas"],
            "usuario" => $datos["usuario"],
            "disciplina" => $datos["disciplina"],
            "espacio" => $nombre_espacio,
            "precio_base" => $datos["espacio"]["precio"],
            "subtotal" => $subtotal,
            "descuento" => $subtotal - $precio_final,
            "precio_final" => $precio_final
        ];

        $_SESSION["reservas"][] = $recibo;
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
        <label for="nombre" >nombre</label><input type="text" required="required" name="nombre"><br>
        <label for="correo" >correo</label><input type="email" required="required" name="correo"><br>
        <label for="participantes" >cantidad de participantes</label><input type="number" required="required" name="participantes" min="1"><br>
        <label for="horas" ></label>cantidad de horas<input type="number" required="required" name="horas" min="1" max="5"><br>

        <?php foreach($opciones as $key => $value): ?>
            <label> <?= $key ?></label>
            <select name="<?= $key ?>">
                <?php foreach($value as $opcion): ?>
                    <option value="<?= $opcion ?>">
                        <?= $opcion ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
        <?php endforeach; ?>
        <label> Espacios</label>
        <select name="espacio">
            
            <?php foreach($espacios as $opcion => $value): ?>
                    <option value="<?= $opcion ?>">
                        <?= $opcion ?>
                    </option>
            <?php endforeach; ?>
        </select>
        <button>Subir</button>
    </form>

    <?php foreach ($errores as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <?php if (isset($recibo)): ?>
        <h2>Comprobante</h2>
        <?php foreach ($recibo as $campo => $valor): ?>
            <p><b><?= $campo ?>:</b> <?= $valor ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($_SESSION['reservas']): ?>
    <table border="1">
        <thead>
            <TR>
            <?php foreach(array_keys($_SESSION["reservas"][0]) as $titulo): ?>
                <th><?= $titulo ?></th>
            <?php endforeach; ?>
            </TR>
        </thead>
        <tbody>
            <?php foreach($_SESSION["reservas"] as $key => $value): ?>
                <tr>
                    <?php foreach($value as $campo):?>
                       <td> <?= $campo ?> </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>