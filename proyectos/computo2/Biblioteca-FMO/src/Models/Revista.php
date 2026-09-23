<?php

namespace App\Models;

use Override;

final class Revista extends Material{

    #[Override]
    public function diasPrestamo(): int
    {
        return 3;
    }
}