<?php

namespace App\Servicios;

use App\Excepciones\CupoAgotadoException;
use App\Modelos\{Estudiante, Inscripcion, Materia};

final class InscripcionServicio
{
    /** @var Inscripcion[] */
    private array $inscripciones = [];
    public function inscribir(Estudiante $e, Materia $m): Inscripcion
    {
        if (!$m->tieneCupo()) {
            throw new CupoAgotadoException($m->codigo);
        }

        $m->ocuparCupo();
        $inscripcion = new Inscripcion($e, $m);
        $this->inscripciones[] = $inscripcion;
        return $inscripcion;
    }
}