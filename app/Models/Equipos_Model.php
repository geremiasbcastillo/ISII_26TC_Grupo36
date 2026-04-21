<?php

namespace App\Models;

use CodeIgniter\Model;

class Equipos_model extends Model
{
    protected $table = 'equipo';
    protected $primaryKey = 'id_equipo';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['nroSerie', 'id_tipo', 'id_modelo', 'id_cliente', 'falla', 'fechaIngreso'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $validationRules = [];
} 