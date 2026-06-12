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

    /**
     * Obtiene los repuestos con el nombre de su categoría utilizando un procedimiento almacenado.
     */
    public function obtenerRepuestosConCategoria()
    {
        $db = \Config\Database::connect();
        $query = $db->query("CALL ObtenerRepuestosConCategoria()");
        
        return $query->getResultArray();
    }
    
    /**
     * Verifica la existencia de un repuesto por ID.
     * 
     * @param int|string $id_repuesto ID del repuesto a verificar
     */
    public function verificarRepuesto($id_repuesto)
    {
        // Obtener el repuesto
        $repuestoData = $this->find($id_repuesto);

        return $repuestoData;
    }
} 