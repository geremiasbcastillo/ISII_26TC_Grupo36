<?php

namespace App\Libraries;

class Tecnico extends Usuario
{
    public function getRolId(): int
    {
        return 2;
    }

    public function getRolNombre(): string
    {
        return 'Tecnico';
    }
}
