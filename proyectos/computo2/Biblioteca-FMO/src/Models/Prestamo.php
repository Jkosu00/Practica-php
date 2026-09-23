<?php

namespace App\Models;

use App\Enums\EstadoPrestamo;

class Prestamo
{

    public function __construct(
        private readonly Usuario $usuario,
        private readonly Material $material,
        private EstadoPrestamo $estado
    ){}

    public function getEstado(): EstadoPrestamo{
        return $this->estado;
    }

    public function getMaterial(): Material{
        return $this->material;
    }

}