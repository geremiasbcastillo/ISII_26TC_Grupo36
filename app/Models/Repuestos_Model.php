<?php

namespace App\Models;

use CodeIgniter\Model;

class Repuestos_Model extends Model
{
    protected $table = 'Repuesto';
    protected $primaryKey = 'id_repuesto';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['nombre', 'cantidad', 'monto', 'cantidad_minima', 'id_categoria_repuesto'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $validationRules = [];

    public function verificarRepuesto($id_repuesto)
    {
        // Obtener el repuesto
        $repuestoData = $this->find($id_repuesto);

        return $repuestoData;
    }
} 