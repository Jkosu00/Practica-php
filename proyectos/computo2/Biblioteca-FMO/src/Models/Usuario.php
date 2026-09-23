<?php

namespace App\Models;

readonly class Usuario
{

    public function __construct(
        private string $nombre,
        private string $carnet
    ) {}


    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getCarnet(): string
    {
        return $this->carnet;
    }

}