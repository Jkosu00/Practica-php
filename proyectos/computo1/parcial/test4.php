<?php

session_start();

$_SESSION["alquileres"] ??= [];

$catalogo = [
    "Balon de futbol" => [
        "categoria" => "balones",
        "precio" => 1.50,
        "existencias" => 8
    ],
    "Raqueta de tennis" => [
        "categoria" => "raquetas",
        "precio" => 2.00,
        "existencias" => 6
    ],
    "Bicicleta" => [
        "categoria" => "ciclismo",
        "precio" => 3.00,
        "existencias" => 4
    ]
];

$opciones = [
    "tipo" => ["estudiante", "general"]
];

$errores = [];
$datos = [];

function escapar($valor)
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

function calcular($datos, $precio)
{
    $subtotal = $precio * $datos["cantidad"] * $datos["horas"];

    $porcentaje = 0;

    if ($datos["tipo"] === "estudiante") {
        $porcentaje += 0.10;
    }

    if ($datos["horas"] >= 4) {
        $porcentaje += 0.05;
    }

    $descuento = $subtotal * $porcentaje;

    $entrega = $datos["domicilio"] ? 2 : 0;

    $deposito = 3 * $datos["cantidad"];

    $total = $subtotal - $descuento + $entrega + $deposito;

    return [
        "clasificacion" => $datos["horas"] >= 4
            ? "Larga duración"
            : "Corta duración",

        "precio por hora" => round($precio, 2),
        "subtotal" => round($subtotal, 2),
        "porcentaje" => ($porcentaje * 100) . "%",
        "descuento" => round($descuento, 2),
        "entrega" => round($entrega, 2),
        "deposito" => round($deposito, 2),
        "total" => round($total, 2)
    ];
}

/*
|--------------------------------------------------------------------------
| Calcular existencias disponibles
|--------------------------------------------------------------------------
*/

$disponibles = [];

foreach ($catalogo as $nombre => $equipo) {

    $alquileresEquipo = array_filter(
        $_SESSION["alquileres"],
        fn($alquiler) => $alquiler["equipo"] === $nombre
    );

    $cantidades = array_column($alquileresEquipo, "cantidad");

    $totalAlquilado = array_sum($cantidades);

    $disponibles[$nombre] =
        $equipo["existencias"] - $totalAlquilado;
}

/*
|--------------------------------------------------------------------------
| Procesar formulario
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $campos = [
        "nombre",
        "correo",
        "cantidad",
        "horas",
        "equipo",
        "tipo"
    ];

    foreach ($campos as $campo) {

        $valor = $_POST[$campo] ?? "";

        $datos[$campo] = is_string($valor)
            ? trim($valor)
            : "";

        if ($datos[$campo] === "") {
            $errores[] = "El campo $campo es obligatorio.";
        }
    }

    $datos["correo"] = strtolower($datos["correo"]);

    $datos["domicilio"] = isset($_POST["domicilio"]);

    /*
    |--------------------------------------------------------------------------
    | Validaciones
    |--------------------------------------------------------------------------
    */

    if (strlen($datos["nombre"]) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres.";
    }

    if (!filter_var($datos["correo"], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no es válido.";
    }

    if (
        !ctype_digit($datos["cantidad"]) ||
        (int)$datos["cantidad"] < 1 ||
        (int)$datos["cantidad"] > 5
    ) {
        $errores[] = "La cantidad debe ser un entero entre 1 y 5.";
    }

    if (
        !ctype_digit($datos["horas"]) ||
        (int)$datos["horas"] < 1 ||
        (int)$datos["horas"] > 8
    ) {
        $errores[] = "Las horas deben ser un entero entre 1 y 8.";
    }

    if (!in_array($datos["tipo"], $opciones["tipo"], true)) {
        $errores[] = "El tipo de cliente no es válido.";
    }

    if (!isset($catalogo[$datos["equipo"]])) {
        $errores[] = "El equipo seleccionado no existe.";
    }

    /*
    |--------------------------------------------------------------------------
    | Validar equipo y existencias
    |--------------------------------------------------------------------------
    */

    if (!$errores) {

        $datos["cantidad"] = (int)$datos["cantidad"];
        $datos["horas"] = (int)$datos["horas"];

        $equipo = $catalogo[$datos["equipo"]];

        if (
            $datos["cantidad"] >
            $disponibles[$datos["equipo"]]
        ) {
            $errores[] = "No hay suficientes unidades disponibles.";
        }

        if (
            $datos["domicilio"] &&
            $equipo["categoria"] === "ciclismo"
        ) {
            $errores[] = "Las bicicletas no tienen entrega a domicilio.";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Crear y guardar recibo
    |--------------------------------------------------------------------------
    */

    if (!$errores) {

        $calculos = calcular($datos, $equipo["precio"]);

        $recibo = [
            "nombre" => $datos["nombre"],
            "correo" => $datos["correo"],
            "equipo" => $datos["equipo"],
            "cantidad" => $datos["cantidad"],
            "horas" => $datos["horas"],
            "tipo" => $datos["tipo"],
            "domicilio" => $datos["domicilio"] ? "Sí" : "No"
        ] + $calculos;

        $_SESSION["alquileres"][] = $recibo;

        $disponibles[$datos["equipo"]] -= $datos["cantidad"];

        // Limpiar el formulario después de guardar
        $datos = [];
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Alquiler de equipos</title>
</head>

<body>

    <h1>Alquiler de equipos deportivos</h1>

    <form method="post">

        <label for="nombre">Nombre</label>

        <input
            type="text"
            id="nombre"
            name="nombre"
            value="<?= escapar($datos["nombre"] ?? "") ?>"
            required
        >

        <br><br>

        <label for="correo">Correo</label>

        <input
            type="email"
            id="correo"
            name="correo"
            value="<?= escapar($datos["correo"] ?? "") ?>"
            required
        >

        <br><br>

        <label for="cantidad">Cantidad de unidades</label>

        <input
            type="number"
            id="cantidad"
            name="cantidad"
            min="1"
            max="5"
            step="1"
            value="<?= escapar($datos["cantidad"] ?? "") ?>"
            required
        >

        <br><br>

        <label for="horas">Horas de alquiler</label>

        <input
            type="number"
            id="horas"
            name="horas"
            min="1"
            max="8"
            step="1"
            value="<?= escapar($datos["horas"] ?? "") ?>"
            required
        >

        <br><br>

        <label for="domicilio">Entrega a domicilio</label>

        <input
            type="checkbox"
            id="domicilio"
            name="domicilio"
            value="si"
            <?= !empty($datos["domicilio"]) ? "checked" : "" ?>
        >

        <br><br>

        <?php foreach ($opciones as $campo => $valores): ?>

            <label for="<?= escapar($campo) ?>">
                <?= escapar(ucfirst($campo)) ?>
            </label>

            <select
                id="<?= escapar($campo) ?>"
                name="<?= escapar($campo) ?>"
                required
            >

                <?php foreach ($valores as $opcion): ?>

                    <option
                        value="<?= escapar($opcion) ?>"
                        <?= ($datos[$campo] ?? "") === $opcion
                            ? "selected"
                            : "" ?>
                    >
                        <?= escapar(ucfirst($opcion)) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <br><br>

        <?php endforeach; ?>

        <label for="equipo">Equipo</label>

        <select id="equipo" name="equipo" required>

            <?php foreach ($catalogo as $nombre => $equipo): ?>

                <option
                    value="<?= escapar($nombre) ?>"
                    <?= ($datos["equipo"] ?? "") === $nombre
                        ? "selected"
                        : "" ?>
                >
                    <?= escapar($nombre) ?>
                    — Precio: $<?= number_format($equipo["precio"], 2) ?>
                    — Disponibles: <?= $disponibles[$nombre] ?>
                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

        <button type="submit">Alquilar</button>

    </form>

    <?php if ($errores): ?>

        <h2>Errores</h2>

        <?php foreach ($errores as $error): ?>

            <p><?= escapar($error) ?></p>

        <?php endforeach; ?>

    <?php endif; ?>

    <?php if (isset($recibo)): ?>

        <h2>Recibo del alquiler</h2>

        <?php foreach ($recibo as $campo => $valor): ?>

            <p>
                <strong><?= escapar(ucfirst($campo)) ?>:</strong>
                <?= escapar($valor) ?>
            </p>

        <?php endforeach; ?>

    <?php endif; ?>

    <?php if (!empty($_SESSION["alquileres"])): ?>

        <h2>Alquileres registrados</h2>

        <table border="1">

            <thead>
                <tr>

                    <?php foreach (
                        array_keys($_SESSION["alquileres"][0])
                        as $columna
                    ): ?>

                        <th><?= escapar(ucfirst($columna)) ?></th>

                    <?php endforeach; ?>

                </tr>
            </thead>

            <tbody>

                <?php foreach (
                    $_SESSION["alquileres"]
                    as $alquiler
                ): ?>

                    <tr>

                        <?php foreach ($alquiler as $valor): ?>

                            <td><?= escapar($valor) ?></td>

                        <?php endforeach; ?>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</body>
</html>