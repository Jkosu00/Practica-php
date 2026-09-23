<?php

session_start();

$_SESSION["BD"] ??= [];

$talleres = [
    "Introduccion a php" => ["area"=>"Programacion","precio"=>12,"cupo"=>5],
    "Diseño con css"     => ["area"=> "diseño web","precio"=> 10,"cupo"=>4],
    "Git"                => ["area"=> "desarrollo","precio"=> 8,"cupo"=> 6],
];

$opciones = [
    "modalidad" => ["virtual","presencial"],
    "tipo"      => ["estudiante","publico"]
];

$errores = [];

function calcular_precio(array $persona, array $talleres){
    $precio_final = $talleres[$persona["taller"]]["precio"] ;

    $descuento = 0;

    if ($persona["tipo"] === "estudiante") {
        $descuento += 0.15;
    }

    if ($persona["modalidad"] === "virtual") {
        $descuento += 0.05;
    }

    $precio_final *= (1 - $descuento);

    if($persona["certificado"] === "Si"){
        $precio_final += 3;
    }

    $precio_final += 1;
    return $precio_final;

}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    foreach(["nombre","edad","correo","taller","modalidad","tipo"] as $campo){
          $datos[$campo] =  is_string($_POST[$campo] ?? null) ? trim($_POST[$campo]) : '';
    }

    $datos['certificado'] = isset($_POST['certificado']) ? "Si" : "No";
    if(strlen($datos["nombre"]) < 3){  
        $errores[] = "el nombre debe tener mas de 3 letras";
    }   

    $datos["precio_final"] = calcular_precio($datos,$talleres);

    if (!$errores) {
        $taller = $talleres[$datos['taller']];
        $inscritos = 0;
        foreach ($_SESSION['BD'] as $participante) {
            if ($participante['taller'] === $datos['taller']) $inscritos++;
        }
        if ($inscritos >= $taller['cupo']) $errores[] = 'El taller ya no tiene cupos.';
    }

    if (!$errores) {
        // Límite de edad de ejemplo: el enunciado no lo define.
        $categoria = ($datos["edad"] < 18 ? 'Juvenil' : 'Adulto');
        $datos["categoria"] = $categoria;
        $recibo = [
            'Nombre' => $datos['nombre'], 'Correo' => $datos['correo'], 'Precio_base' => $talleres[$datos['taller']]["precio"], 'categoria' => $categoria,
            'Descuento' => $datos["precio_final"]-$talleres[$datos['taller']]["precio"],'Certificado' => $datos["certificado"] === "Si" ? "Si: Cobro de $3" : "No",
            'PrecioFinal'  => $datos["precio_final"] , 
            
        ];

        $_SESSION['BD'][] = $datos;
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
    <h1>Inscripción al torneo</h1>
    <form  method="post">
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" type="text" required>

        <label for="edad">Edad</label>
        <input id="edad" name="edad" type="number" required min="15" max="80">

        <label for="correo">Correo</label>
        <input id="correo" name="correo" type="email" required>

        <label for="taller">Taller</label>
        <select name="taller">

            <?php foreach($talleres as $taller => $array):?>
                <option value="<?= $taller ?>"><?= $taller ?> ---- $<?= $array['precio'] ?></option>
            <?php endforeach; ?>
        </select>


        <?php foreach($opciones as $key => $value): ?>
            <label><?= $key ?></label>
            <select name="<?= $key?>">
                <?php foreach($value as $opcion): ?>
                    <option value="<?= $opcion ?>"><?= $opcion ?></option>
                <?php endforeach; ?>
            </select>
        <?php endforeach; ?>
        
        <label for="certificado">Certificado</label>
        <input type="checkbox" name="certificado" value="Si">
        <button>Inscribirse</button>
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


    <?php if ($_SESSION['BD']): ?>
        <h2>Participantes registrados</h2>
        <table border="1">
            <thead>
                <?php foreach(array_keys($_SESSION['BD'][0]) as $campo):  ?>
                    <th><?= $campo ?></th>
                <?php endforeach; ?>
            </thead>
            <tbody>
                <?php foreach($_SESSION['BD'] as $persona):  ?>
                    <tr>
                        <?php foreach($persona as $campo):  ?>
                            <td>
                                <?= $campo ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    <?php endif; ?>
</body>
</html>