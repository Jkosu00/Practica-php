<?php
session_start();
$_SESSION['participantes'] ??= [];
const DESCUENTO = 0.10;
const CARGO = 0.00; // El enunciado no especifica el monto.

$juegos = [
    'Mario Kart' => ['categoria' => 'Carreras', 'costo' => 5.00, 'maximo' => 20],
    'Smash' => ['categoria' => 'Pelea', 'costo' => 8.00, 'maximo' => 16]
];
$opciones = [
    'juego' => array_keys($juegos),
    'modalidad' => ['Presencial', 'Virtual'],
    'experiencia' => ['Principiante', 'Intermedio', 'Avanzado']
];

function escapar($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function calcular(float $costo, bool $principiante): array {
    $descuento = round($costo * ($principiante ? DESCUENTO : 0), 2);
    return [
        'Costo original' => $costo, 'Descuento' => $descuento,
        'Cargo administrativo' => CARGO, 'Total' => $costo - $descuento + CARGO
    ];
}

$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['nombre', 'edad', 'correo', 'juego', 'modalidad', 'experiencia'] as $campo) {
        $datos[$campo] = is_string($_POST[$campo] ?? null) ? trim($_POST[$campo]) : '';
        if (strlen($datos[$campo]) === 0) $errores[] = "Falta el campo $campo.";
    }
    $datos['correo'] = strtolower($datos['correo']);
    $edad = filter_var($datos['edad'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($edad === false) $errores[] = 'La edad debe ser un entero positivo.';
    
    if (!$errores) {
        $juego = $juegos[$datos['juego']];
        $inscritos = 0;
        foreach ($_SESSION['participantes'] as $participante) {
            if ($participante['Videojuego'] === $datos['juego']) $inscritos++;
        }
        if ($inscritos >= $juego['maximo']) $errores[] = 'El videojuego ya no tiene cupos.';
    }
    if (!$errores) {
        // Límite de edad de ejemplo: el enunciado no lo define.
        $categoria = ($edad < 18 ? 'Menor' : 'Adulto') . ' / ' . $datos['experiencia'];
        $recibo = [
            'Nombre' => $datos['nombre'], 'Edad' => $edad, 'Correo' => $datos['correo'],
            'Videojuego' => $datos['juego'], 'Modalidad' => $datos['modalidad'],
            'Experiencia' => $datos['experiencia'], 'Categoría' => $categoria
        ];
        foreach (calcular($juego['costo'], $datos['experiencia'] === 'Principiante') as $campo => $monto) {
            $recibo[$campo] = '$' . number_format($monto, 2);
        }
        $_SESSION['participantes'][] = $recibo;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<meta charset="UTF-8">
<title>Torneo universitario</title>
<body>
<h1>Inscripción al torneo</h1>
<form method="post">
    <p><label>Nombre: <input name="nombre" required></label></p>
    <p><label>Edad: <input type="number" name="edad" min="1" required></label></p>
    <p><label>Correo: <input type="email" name="correo" required></label></p>
    <?php foreach ($opciones as $campo => $valores): ?>
        <p><label><?= escapar(ucfirst($campo)) ?>:
            <select name="<?= escapar($campo) ?>" required>
                <option value="">Seleccionar</option>
                <?php foreach ($valores as $valor): ?>
                    <option value="<?= escapar($valor) ?>"><?= escapar($valor) ?></option>
                <?php endforeach; ?>
            </select>
        </label></p>
    <?php endforeach; ?>
    <button>Inscribirse</button>
</form>
<?php foreach ($errores as $error): ?>
    <p><?= escapar($error) ?></p>
<?php endforeach; ?>
<?php if (isset($recibo)): ?>
    <h2>Comprobante</h2>
    <?php foreach ($recibo as $campo => $valor): ?>
        <p><b><?= escapar($campo) ?>:</b> <?= escapar($valor) ?></p>
    <?php endforeach; ?>
<?php endif; ?>
<?php if ($_SESSION['participantes']): ?>
    <h2>Participantes registrados</h2>
    <table border="1">
        <tr>
            <?php foreach (array_keys($_SESSION['participantes'][0]) as $campo): ?>
                <th><?= escapar($campo) ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($_SESSION['participantes'] as $participante): ?>
            <tr>
                <?php foreach ($participante as $valor): ?>
                    <td><?= escapar($valor) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>
