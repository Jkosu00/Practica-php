<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Models\{Libro, Revista, Usuario};
use App\Services\PrestamoService;
use App\Exceptions\MaterialNoDisponibleException;


$juan = new Usuario(
    "Juan Pérez",
    "JP21001"
);

$libro = new Libro(
    "LIB001",
    "Programación Orientada a Objetos en PHP",
    2026
);

$revista = new Revista(
    "REV001",
    "Tecnología y Desarrollo",
    2025
);

$servicio = new PrestamoService();

foreach ([$libro, $revista, $libro] as $material) {

    try{
        $prestamo = $servicio->prestar(
            $juan,
            $material
        );


        echo "Prestado: {$material->titulo} ";
        echo "- Estado: {$prestamo->getEstado()->etiqueta()}";
        echo "<br>";

    } catch (MaterialNoDisponibleException $e) {

        echo "Error: {$e->getMessage()}";
        echo PHP_EOL;

    }

}