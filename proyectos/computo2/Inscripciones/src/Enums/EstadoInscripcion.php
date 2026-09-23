<?php
namespace App\Enums;
enum EstadoInscripcion: string
{
    case Inscrita = 'INS';
    case Retirada = 'RET';
    case Aprobada = 'APR';
    case Reprobada = 'REP';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Inscrita => 'Inscrita',
            self::Retirada => 'Retirada',
            self::Aprobada => 'Aprobada',
            self::Reprobada => 'Reprobada',
        };
    }
}

?>
