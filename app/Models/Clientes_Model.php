<?php

namespace App\Models;

use CodeIgniter\Model;

class Clientes_Model extends Model
{
    protected $table = 'Cliente';
    protected $primaryKey = 'id_cliente';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['nombre', 'apellido', 'dni', 'telefono', 'correo', 'id_direccion'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $validationRules = [];
} 