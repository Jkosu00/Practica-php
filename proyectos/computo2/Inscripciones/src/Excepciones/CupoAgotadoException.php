<?php
namespace App\Excepciones;
use RuntimeException;
final class CupoAgotadoException extends RuntimeException
{
    public function __construct(
        public readonly string $codigoMateria,
    ) {
        parent::__construct("Sin cupo en $codigoMateria");
    }
}