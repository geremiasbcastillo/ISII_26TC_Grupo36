<?php

namespace App\Models;

use CodeIgniter\Model;

class Categorias_Model extends Model
{
    protected $table = 'categoria_repuesto';
    protected $primaryKey = 'id_categoria_repuesto';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['nombre'];

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $validationRules = [];
} 