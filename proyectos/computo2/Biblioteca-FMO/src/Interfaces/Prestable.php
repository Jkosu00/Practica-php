<?php

    namespace App\Interfaces;

    use App\Models\{Usuario,Prestamo};

    Interface Prestable{

        public function Prestar(Usuario $u): Prestamo;
    }