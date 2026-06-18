<?php

namespace App\Libraries;

class Repuesto
{
    public $id_repuesto;
    public $nombre;
    public $cantidad;
    public $monto;
    public $stockMinimo;
    public $id_categoria_repuesto;

    /**
     * @var IObservador[] Listado de observadores registrados.
     */
    private $observadores = [];

    /**
     * Constructor para inicializar el repuesto con datos opcionales.
     * 
     * @param array $datos Atributos para inicializar el objeto
     */
    public function __construct(array $datos = [])
    {
        $this->id_repuesto = $datos['id_repuesto'] ?? null;
        $this->nombre = $datos['nombre'] ?? '';
        $this->cantidad = $datos['cantidad'] ?? 0;
        $this->monto = $datos['monto'] ?? 0.0;
        // Mapeamos cantidad_minima de la base de datos a stockMinimo
        $this->stockMinimo = $datos['cantidad_minima'] ?? ($datos['stockMinimo'] ?? 0);
        $this->id_categoria_repuesto = $datos['id_categoria_repuesto'] ?? null;
    }

    /**
     * Busca un repuesto en la base de datos por su ID y retorna un objeto de negocio.
     * 
     * @param int|string $id_repuesto ID del repuesto a buscar.
     * @return Repuesto|null
     */
    public static function buscarRepuesto($id_repuesto)
    {
        $model = new \App\Models\Repuestos_Model();
        $repuestoData = $model->find($id_repuesto);
        if ($repuestoData) {
            return new self($repuestoData);
        }
        return null;
    }

    /**
     * Agrega un observador al listado.
     * 
     * @param IObservador $obs
     */
    public function agregarObservador(IObservador $obs)
    {
        $this->observadores[] = $obs;
    }

    /**
     * Elimina un observador del listado.
     * 
     * @param IObservador $obs
     */
    public function eliminarObservador(IObservador $obs)
    {
        $this->observadores = array_filter($this->observadores, function ($o) use ($obs) {
            return $o !== $obs;
        });
    }

    /**
     * Notifica a todos los observadores registrados.
     */
    private function notificarObservadores()
    {
        foreach ($this->observadores as $obs) {
            $obs->actualizar($this);
        }
    }

    /**
     * Verifica si el stock de repuesto es inferior o igual al stock mínimo.
     * Si se cumple la condición, notifica a los observadores.
     */
    public function verificarRepuesto()
    {
        if ($this->cantidad <= $this->stockMinimo) {
            $this->notificarObservadores();
        }
    }

    /**
     * Registra un nuevo repuesto en la base de datos.
     * 
     * @return bool
     */
    public function guardarRepuesto(): bool
    {
        $model = new \App\Models\Repuestos_Model();
        
        $data = [
            'nombre'                => $this->nombre,
            'cantidad'              => $this->cantidad,
            'monto'                 => $this->monto,
            'cantidad_minima'       => $this->stockMinimo,
            'id_categoria_repuesto' => $this->id_categoria_repuesto
        ];

        $insertId = $model->insert($data);
        if ($insertId) {
            $this->id_repuesto = $insertId;
            return true;
        }
        return false;
    }

    /**
     * Actualiza los datos de un repuesto existente en la base de datos.
     * Tras la actualización, verifica el stock para notificar observadores si es necesario.
     * 
     * @return bool
     */
    public function guardarEdicion(): bool
    {
        if (empty($this->id_repuesto)) {
            return false;
        }

        $model = new \App\Models\Repuestos_Model();
        
        $data = [
            'nombre'                => $this->nombre,
            'cantidad'              => $this->cantidad,
            'monto'                 => $this->monto,
            'cantidad_minima'       => $this->stockMinimo,
            'id_categoria_repuesto' => $this->id_categoria_repuesto
        ];

        if ($model->update($this->id_repuesto, $data)) {
            $this->verificarRepuesto();
            return true;
        }
        return false;
    }
}
