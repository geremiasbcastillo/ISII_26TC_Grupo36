<?php

use CodeIgniter\Test\CIUnitTestCase;
use App\Libraries\Repuesto;
use App\Libraries\IObservador;

final class RepuestoObserverTest extends CIUnitTestCase
{
    /**
     * Verifica que un observador pueda agregarse y recibir notificaciones cuando el stock es bajo.
     */
    public function testNotificarObservadorStockBajo()
    {
        // Creamos un repuesto con stock por debajo del mínimo (5 <= 10)
        $repuesto = new Repuesto([
            'nombre'          => 'Teclado Mecánico',
            'cantidad'        => 5,
            'cantidad_minima' => 10,
            'monto'           => 1500
        ]);

        // Creamos un mock para el observador
        $mockObservador = $this->createMock(IObservador::class);

        // Esperamos que se llame al método 'actualizar' exactamente una vez con el repuesto como argumento
        $mockObservador->expects($this->once())
            ->method('actualizar')
            ->with($this->equalTo($repuesto));

        // Registramos el observador
        $repuesto->agregarObservador($mockObservador);

        // Verificamos el repuesto (debería disparar la notificación)
        $repuesto->verificarRepuesto();
    }

    /**
     * Verifica que no se notifique al observador si el stock es suficiente (superior al mínimo).
     */
    public function testNoNotificarObservadorStockSuficiente()
    {
        // Creamos un repuesto con stock suficiente (15 > 10)
        $repuesto = new Repuesto([
            'nombre'          => 'Teclado Mecánico',
            'cantidad'        => 15,
            'cantidad_minima' => 10,
            'monto'           => 1500
        ]);

        // Creamos un mock para el observador
        $mockObservador = $this->createMock(IObservador::class);

        // No esperamos que se llame al método 'actualizar'
        $mockObservador->expects($this->never())
            ->method('actualizar');

        // Registramos el observador
        $repuesto->agregarObservador($mockObservador);

        // Verificamos el repuesto
        $repuesto->verificarRepuesto();
    }

    /**
     * Verifica que un observador eliminado de la lista ya no reciba notificaciones.
     */
    public function testEliminarObservador()
    {
        // Creamos un repuesto con stock bajo
        $repuesto = new Repuesto([
            'nombre'          => 'Teclado Mecánico',
            'cantidad'        => 3,
            'cantidad_minima' => 10,
            'monto'           => 1500
        ]);

        $mockObservador = $this->createMock(IObservador::class);

        // El observador no debe ser notificado porque será eliminado
        $mockObservador->expects($this->never())
            ->method('actualizar');

        $repuesto->agregarObservador($mockObservador);
        $repuesto->eliminarObservador($mockObservador);

        // Verificamos el repuesto
        $repuesto->verificarRepuesto();
    }
}
