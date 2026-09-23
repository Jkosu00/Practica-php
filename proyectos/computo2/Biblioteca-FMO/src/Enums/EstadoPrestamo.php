<?php
namespace App\Enums;

enum EstadoPrestamo: string{
    case Activo = 'Act';

    case Devuelto = 'Dev';

    case Vencido = 'Ven';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Activo => 'Activo',
            self::Devuelto => 'Devuelto',
            self::Vencido => 'Vencido'
        };
    }

}