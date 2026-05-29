<?php

namespace App\Models;

use CodeIgniter\Model;

class Diagnosticos_model extends Model
{
    protected $table = 'diagnostico';
    protected $primaryKey = 'id_diagnostico';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['fechaDiagnostico', 'id_usuario', 'analisis', 'solucion', 'costo_estimado', 'id_equipo'];
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $validationRules = [];
}
