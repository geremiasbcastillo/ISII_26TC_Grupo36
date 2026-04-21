<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelos_Equipos_model extends Model
{
    protected $table = 'modelo_equipo';
    protected $primaryKey = 'id_modelo';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['nombre', 'id_marca'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $validationRules = [];
} 