<?php

namespace App\Models;

use CodeIgniter\Model;

class Reparaciones_model extends Model
{
    protected $table            = 'reparacion';
    protected $primaryKey       = 'id_reparacion';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['fecha_reparacion', 'id_diagnostico', 'id_estadoReparacion', 'monto_total'];
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;
    protected $validationRules  = [];
}
