<?php
namespace App\Modelos;
use App\Enums\EstadoInscripcion as Estado;
final class Inscripcion
{
    public function __construct(
        public readonly Estudiante $estudiante,
        public readonly Materia $materia,
        private Estado $estado = Estado::Inscrita,
    ) {}
    public function estado(): Estado
    {
        return $this->estado;
    }
}
