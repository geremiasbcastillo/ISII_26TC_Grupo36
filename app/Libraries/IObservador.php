<?php

namespace App\Libraries;

interface IObservador
{
    /**
     * Recibe la actualización del sujeto observado.
     * 
     * @param Repuesto $repuesto El repuesto sujeto de la observación.
     */
    public function actualizar(Repuesto $repuesto);
}
