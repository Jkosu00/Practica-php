<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Excepciones\CupoAgotadoException;
use App\Modelos\{Estudiante, Materia};
use App\Servicios\InscripcionServicio;

$tpi = new Materia('TPI115', 'Téc. de Prog. para Internet', cupo: 1);
$servicio = new InscripcionServicio();
$ana = new Estudiante('PR21001', 'Ana Pérez', 'pr21001@ues.edu.sv');
$luis = new Estudiante('GL21002', 'Luis Gómez', 'gl21002@ues.edu.sv');

foreach ([$ana, $luis] as $est) {
    try {
        $ins = $servicio->inscribir($est, $tpi);
        echo "{$est->nombre}: {$ins->estado()->etiqueta()}\n";
    } catch (CupoAgotadoException $e) {
        echo "{$est->nombre}: {$e->getMessage()}\n";
    }
}