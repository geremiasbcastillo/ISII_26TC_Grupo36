<?php

namespace App\Models;

use CodeIgniter\Model;

class Tipos_Equipos_model extends Model
{
    protected $table = 'tipo_equipo';
    protected $primaryKey = 'id_tipo';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['nombre'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $validationRules = [];
} 