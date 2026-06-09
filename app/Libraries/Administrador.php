<?php

namespace App\Libraries;

class Administrador extends Usuario
{
    public function getRolId(): int
    {
        return 1;
    }

    public function getRolNombre(): string
    {
        return 'Administrador';
    }
}
