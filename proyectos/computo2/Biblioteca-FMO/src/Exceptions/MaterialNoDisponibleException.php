<?php
namespace App\Exceptions;
use RuntimeException;
final class MaterialNoDisponibleException extends RuntimeException
{
    public function __construct(
        public readonly string $codigoMaterial,
    ) {
        parent::__construct("$codigoMaterial no se encuentra disponible, otro usuario lo ha prestado");
    }
}