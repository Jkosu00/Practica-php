<?php

namespace App\Models;

use Override;

final class Libro extends Material{

    #[Override]
    public function diasPrestamo(): int
    {
        return 15;
    }


}