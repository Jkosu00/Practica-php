<?php

session_start();

// Si todavía no existe el arreglo de registros, lo crea
$_SESSION["registro"] ??= [];

// Categorías disponibles
$categorias = [
    "Programación",
    "Base de Datos",
    "Redes",
    "Matemática",
    "Sistemas Operativos"
];

// Cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre_estudiante"] ?? "");
    $carnet = trim($_POST["carnet_estudiante"] ?? "");
    $categoria = $_POST["categoria"] ?? "";
    $notas = $_POST["notas"] ?? [];

    // Convertimos las notas a números
    $notas = array_map("floatval", $notas);

    // Validamos que haya exactamente 3 notas
    if (
        $nombre !== "" &&
        $carnet !== "" &&
        $categoria !== "" &&
        count($notas) === 3
    ) {

        $_SESSION["registro"][] = [
            "nombre" => $nombre,
            "carnet" => $carnet,
            "categoria" => $categoria,
            "notas" => $notas
        ];

        // Evita que al actualizar la página se vuelva a insertar
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro de notas</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }

        .contenedor {
            width: 90%;
            max-width: 1300px;
            margin: 40px auto;
        }

        h1 {
            margin-bottom: 5px;
            font-size: 32px;
        }

        .descripcion {
            color: #64748b;
            font-style: italic;
            margin-bottom: 35px;
        }

        /* FORMULARIO */

        .formulario {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.06);

            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .campo {
            display: flex;
            flex-direction: column;
        }

        .campo label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 13px 15px;
            border-radius: 7px;
            border: 1px solid #cbd5e1;
            font-size: 16px;
            outline: none;
            background: white;
        }

        input:focus,
        select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
        }

        .boton-contenedor {
            grid-column: 1 / -1;
        }

        button {
            background: #172554;
            color: white;
            border: none;
            padding: 14px 25px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1e3a8a;
        }

        /* TABLA */

        .lista {
            margin-top: 45px;
        }

        .lista h2 {
            margin-bottom: 20px;
        }

        .tabla-contenedor {
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #172554;
            color: white;
            text-align: left;
            padding: 15px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:hover {
            background: #f8fafc;
        }

        .sin-registros {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            color: #64748b;
        }

        .aprobado {
            color: #15803d;
            font-weight: bold;
        }

        .reprobado {
            color: #dc2626;
            font-weight: bold;
        }

        .promedio {
            font-weight: bold;
        }

        /* RESPONSIVE */

        @media (max-width: 850px) {

            .formulario {
                grid-template-columns: 1fr;
            }

            .boton-contenedor {
                grid-column: auto;
            }
        }

    </style>
</head>

<body>

<div class="contenedor">

    <h1>Registro de notas</h1>

    <p class="descripcion">
        Formularios, arreglos, condicionales y bucles en un solo archivo PHP.
    </p>


    <!-- FORMULARIO -->
    <form action="" method="POST" class="formulario">

        <div class="campo">
            <label>Nombre del alumno</label>

            <input
                type="text"
                name="nombre_estudiante"
                placeholder="Ej: Juan Pérez"
                required
            >
        </div>


        <div class="campo">
            <label>Carnet</label>

            <input
                type="text"
                name="carnet_estudiante"
                placeholder="Ej: JP25001"
                required
            >
        </div>


        <!-- COMBOBOX -->
        <div class="campo">

            <label>Categoría</label>

            <select name="categoria" required>

                <option value="">
                    -- Seleccione --
                </option>

                <?php foreach ($categorias as $categoria): ?>

                    <option value="<?= htmlspecialchars($categoria) ?>">

                        <?= htmlspecialchars($categoria) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <div class="campo">
            <label>Primer parcial</label>

            <input
                type="number"
                name="notas[]"
                min="0"
                max="10"
                step="0.01"
                placeholder="0 a 10"
                required
            >
        </div>


        <div class="campo">
            <label>Segundo parcial</label>

            <input
                type="number"
                name="notas[]"
                min="0"
                max="10"
                step="0.01"
                placeholder="0 a 10"
                required
            >
        </div>


        <div class="campo">
            <label>Tercer parcial</label>

            <input
                type="number"
                name="notas[]"
                min="0"
                max="10"
                step="0.01"
                placeholder="0 a 10"
                required
            >
        </div>


        <div class="boton-contenedor">

            <button type="submit">
                Registrar alumno
            </button>

        </div>

    </form>


    <!-- LISTA DE ESTUDIANTES -->
    <section class="lista">

        <h2>Alumnos registrados</h2>


        <?php if (empty($_SESSION["registro"])): ?>

            <div class="sin-registros">

                Todavía no hay registros.
                Complete el formulario para empezar.

            </div>

        <?php else: ?>


            <div class="tabla-contenedor">

                <table>

                    <thead>

                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Carnet</th>
                        <th>Categoría</th>
                        <th>Nota 1</th>
                        <th>Nota 2</th>
                        <th>Nota 3</th>
                        <th>Promedio</th>
                        <th>Estado</th>
                    </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($_SESSION["registro"] as $indice => $estudiante): ?>

                        <?php

                        $notas = $estudiante["notas"];

                        $promedio = array_sum($notas) / count($notas);

                        $estado = $promedio >= 6
                            ? "Aprobado"
                            : "Reprobado";

                        ?>

                        <tr>

                            <td>
                                <?= $indice + 1 ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($estudiante["nombre"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($estudiante["carnet"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($estudiante["categoria"]) ?>
                            </td>

                            <td>
                                <?= number_format($notas[0], 2) ?>
                            </td>

                            <td>
                                <?= number_format($notas[1], 2) ?>
                            </td>

                            <td>
                                <?= number_format($notas[2], 2) ?>
                            </td>

                            <td class="promedio">

                                <?= number_format($promedio, 2) ?>

                            </td>

                            <td>

                                <?php if ($estado === "Aprobado"): ?>

                                    <span class="aprobado">
                                        Aprobado
                                    </span>

                                <?php else: ?>

                                    <span class="reprobado">
                                        Reprobado
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>

</div>

</body>

</html>