<?php
namespace App\Modelos;

final class Estudiante extends Persona
{
    public function __construct(
        public readonly string $carnet,
        string $nombre,
        string $correo,
    ) {
        parent::__construct($nombre, $correo);
    }
}
