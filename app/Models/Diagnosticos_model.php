<?php

namespace App\Models;

use CodeIgniter\Model;

class Diagnosticos_model extends Model
{
    protected $table = 'diagnostico';
    protected $primaryKey = 'id_diagnostico';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['id_equipo', 'diagnostico'];
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $validationRules = [];
}
