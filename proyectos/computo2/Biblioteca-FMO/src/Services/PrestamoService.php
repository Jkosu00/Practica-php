<?php

namespace App\Services;

use App\Enums\EstadoPrestamo;
use App\Exceptions\MaterialNoDisponibleException;
use App\Models\Material;
use App\Models\Prestamo;
use App\Models\Usuario;

final class PrestamoService{
    private array $prestamos = [];

    public function prestar(Usuario $u, Material $m) : Prestamo{
        foreach($this->prestamos as $value){
            if($value->getMaterial()->codigo == $m->codigo && $value->getEstado() === EstadoPrestamo::Activo){
                throw new MaterialNoDisponibleException($m->titulo);
            }   
        }

        $p = $m->Prestar($u);
        $this->prestamos[] = $p;
        return $p;
    }

    public function regresar(Usuario $u, Material $m) : Prestamo{
        foreach($this->prestamos as $value){
            if($value->getMaterial()->codigo == $m->codigo && $value->getEstado() === EstadoPrestamo::Activo){
                throw new MaterialNoDisponibleException($m->titulo);
            }   
        }

        $p = $m->Prestar($u);
        $this->prestamos[] = $p;
        return $p;
    }    
}