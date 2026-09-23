<?php
namespace App\Modelos;
final class Materia
{
    private int $inscritos = 0;
    public function __construct(
        public readonly string $codigo,
        public readonly string $nombre,
        private readonly int $cupo,
    ) {}
    public function tieneCupo(): bool
    {
        return $this->inscritos < $this->cupo;
    }
    public function ocuparCupo(): void
    {
        $this->inscritos++;
    }
}
