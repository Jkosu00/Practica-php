<?php 

namespace App\Models;

use App\Enums\EstadoPrestamo;
use App\Interfaces\Prestable;

abstract class Material implements Prestable
{
    public function __construct(
        public readonly string $codigo,
        public readonly string $titulo,
        public readonly int $año) {}

    abstract public function diasPrestamo(): int;

    public function Prestar(Usuario $u): Prestamo
    {
        return new Prestamo($u,$this, EstadoPrestamo::Activo);
    }
}

?>