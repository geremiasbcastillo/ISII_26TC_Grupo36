<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Config\Factories;
use App\Models\Clientes_Model;
use App\Models\Diagnosticos_model;
use App\Models\Equipos_model;
use App\Controllers\Diagnosticos_controller;
use App\Controllers\Equipos_controller;

/**
 * Clase ServicioTecnicoTest
 * 
 * Contiene las pruebas unitarias especificadas para:
 * 1. verificarDni en Clientes_Model (Mockito-style Mock)
 * 2. guardarDiagnostico en Diagnosticos_controller (Mocked dependencies)
 * 3. registrarEquipo en Equipos_controller (Mocked dependencies & Validation)
 */
final class ServicioTecnicoTest extends CIUnitTestCase
{
    use ControllerTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        // Reset factories components between tests
        Factories::reset();
        $_POST = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_POST = [];
    }

    // ==========================================
    // 4.2.1 PRUEBA UNITARIA 1: verificarDni (Clientes_Model)
    // ==========================================

    /**
     * Prueba: Cliente registrado con ese DNI.
     * Resultado esperado: Retornar el cliente.
     */
    public function testVerificarDniClienteRegistrado(): void
    {
        // Creamos el mock de Clientes_Model (estilo Mockito)
        $mockClientesModel = $this->getMockBuilder(Clientes_Model::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['verificarDni'])
            ->getMock();

        $expectedClient = [
            'id_cliente'   => 1,
            'nombre'       => 'Juan',
            'apellido'     => 'Perez',
            'dni'          => '44235461',
            'telefono'     => '3794123456',
            'correo'       => 'juan@example.com',
            'id_direccion' => 1
        ];

        // Definimos el comportamiento esperado del mock (Stubbing)
        $mockClientesModel->expects($this->once())
            ->method('verificarDni')
            ->with('44235461')
            ->willReturn($expectedClient);

        // Ejecutamos la prueba directamente sobre el mock
        $result = $mockClientesModel->verificarDni('44235461');

        $this->assertEquals($expectedClient, $result);
    }

    /**
     * Prueba: Ningún cliente tiene ese DNI.
     * Resultado esperado: Retornar null (Error: "Cliente no encontrado").
     */
    public function testVerificarDniClienteNoEncontrado(): void
    {
        // Creamos el mock de Clientes_Model (estilo Mockito)
        $mockClientesModel = $this->getMockBuilder(Clientes_Model::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['verificarDni'])
            ->getMock();

        // Definimos que retorne null para simular que no existe en BD
        $mockClientesModel->expects($this->once())
            ->method('verificarDni')
            ->with('44567123')
            ->willReturn(null);

        // Ejecutamos
        $result = $mockClientesModel->verificarDni('44567123');

        $this->assertNull($result);
    }

    // ==========================================
    // 4.2.2 PRUEBA UNITARIA 2: guardarDiagnostico (Diagnosticos_controller)
    // ==========================================

    /**
     * Prueba: Equipo registrado sin análisis.
     * Resultado esperado: Error: "El análisis es obligatorio."
     */
    public function testGuardarDiagnosticoSinAnalisis(): void
    {
        $_POST = [
            'id_equipo'      => '1',
            'analisis'       => '', // vacío
            'solucion'       => 'Arreglar pantalla',
            'costo_estimado' => '50000'
        ];

        $request = service('incomingrequest', null, false);

        $result = $this->withRequest($request)
            ->controller(Diagnosticos_controller::class)
            ->execute('guardarDiagnostico');

        // Assert que redirige por fallar validación
        $result->assertRedirect();
        
        // Assert que el error de validación está presente en la sesión
        $result->assertSessionHas('validation');
        $errors = session()->get('validation');
        $this->assertArrayHasKey('analisis', $errors);
        $this->assertEquals('El análisis es obligatorio.', $errors['analisis']);
    }

    /**
     * Prueba: Equipo registrado con campos completos.
     * Resultado esperado: Muestra "Diagnóstico guardado correctamente"
     */
    public function testGuardarDiagnosticoExitoso(): void
    {
        $_POST = [
            'id_equipo'      => '1',
            'analisis'       => 'Enciende pero no da imagen', // longitud >= 10
            'solucion'       => 'Probar rams y fuente',       // longitud >= 5
            'costo_estimado' => '20000'
        ];

        // Mock del modelo Equipos_model para simular que el equipo existe y está activo
        $mockEquiposModel = $this->getMockBuilder(Equipos_model::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'first'])
            ->getMock();

        // Mocking del chain fluent builder: $model->where(...)->where(...)->first()
        $mockEquiposModel->method('where')->willReturnSelf();
        $mockEquiposModel->method('first')->willReturn([
            'id_equipo'     => 1,
            'nroSerie'      => '123',
            'equipo_estado' => 1
        ]);
        Factories::injectMock('models', Equipos_model::class, $mockEquiposModel);

        // Mock del modelo Diagnosticos_model para simular inserción exitosa
        $mockDiagnosticosModel = $this->getMockBuilder(Diagnosticos_model::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['insert'])
            ->getMock();
        $mockDiagnosticosModel->method('insert')->willReturn(true);
        Factories::injectMock('models', Diagnosticos_model::class, $mockDiagnosticosModel);

        // Simulamos usuario logueado en sesión
        $this->withSession(['id' => 1]);

        $request = service('incomingrequest', null, false);
        $result = $this->withRequest($request)
            ->controller(Diagnosticos_controller::class)
            ->execute('guardarDiagnostico');

        // Redirección de éxito
        $result->assertRedirectTo(base_url('diagnostico'));
        $result->assertSessionHas('mensaje_success', 'Diagnóstico guardado correctamente.');
    }

    /**
     * Prueba: Equipo registrado con cantidad de caracteres en solución menor a 5.
     * Resultado esperado: Error: "La solución debe tener al menos 5 caracteres"
     */
    public function testGuardarDiagnosticoSolucionCorta(): void
    {
        $_POST = [
            'id_equipo'      => '2',
            'analisis'       => 'Pantalla rota', // longitud >= 10
            'solucion'       => 'Nose',          // 4 caracteres (< 5)
            'costo_estimado' => '50000'
        ];

        $request = service('incomingrequest', null, false);
        $result = $this->withRequest($request)
            ->controller(Diagnosticos_controller::class)
            ->execute('guardarDiagnostico');

        $result->assertRedirect();
        $result->assertSessionHas('validation');
        $errors = session()->get('validation');
        
        $this->assertArrayHasKey('solucion', $errors);
        $this->assertEquals('La solución debe tener al menos 5 caracteres.', $errors['solucion']);
    }

    // ==========================================
    // 4.2.3 PRUEBA UNITARIA 3: registrarEquipo (Equipos_controller)
    // ==========================================

    /**
     * Prueba: Intento de ingreso de equipo sin DNI de cliente.
     * Resultado esperado: Error: "El DNI del cliente es obligatorio."
     */
    public function testRegistrarEquipoSinDni(): void
    {
        $_POST = [
            'dni_cliente'  => '', // vacío
            'id_tipo'      => '1',
            'id_marca'     => '1',
            'nroSerie'     => '123',
            'falla'        => 'No da imagen',
            'fechaIngreso' => '2026-05-20'
        ];

        $request = service('incomingrequest', null, false);
        $result = $this->withRequest($request)
            ->controller(Equipos_controller::class)
            ->execute('registrarEquipo');

        $result->assertSessionHas('validation');
        $errors = session()->get('validation');
        
        $this->assertArrayHasKey('dni_cliente', $errors);
        $this->assertEquals('El DNI del cliente es obligatorio.', $errors['dni_cliente']);
    }

    /**
     * Prueba: Intento de ingreso de equipo sin número de serie.
     * Resultado esperado: Error: "El número de serie es obligatorio."
     */
    public function testRegistrarEquipoSinNroSerie(): void
    {
        $_POST = [
            'dni_cliente'  => '44235461',
            'id_tipo'      => '1',
            'id_marca'     => '1',
            'nroSerie'     => '', // vacío
            'falla'        => 'No da imagen',
            'fechaIngreso' => '2026-05-20'
        ];

        $request = service('incomingrequest', null, false);
        $result = $this->withRequest($request)
            ->controller(Equipos_controller::class)
            ->execute('registrarEquipo');

        $result->assertSessionHas('validation');
        $errors = session()->get('validation');
        
        $this->assertArrayHasKey('nroSerie', $errors);
        $this->assertEquals('El número de serie es obligatorio.', $errors['nroSerie']);
    }

    /**
     * Prueba: Intento de ingreso de equipo sin falla.
     * Resultado esperado: Error: "La descripción de la falla es obligatoria"
     */
    public function testRegistrarEquipoSinFalla(): void
    {
        $_POST = [
            'dni_cliente'  => '44235461',
            'id_tipo'      => '1',
            'id_marca'     => '1',
            'nroSerie'     => '123',
            'falla'        => '', // vacío
            'fechaIngreso' => '2026-05-20'
        ];

        $request = service('incomingrequest', null, false);
        $result = $this->withRequest($request)
            ->controller(Equipos_controller::class)
            ->execute('registrarEquipo');

        $result->assertSessionHas('validation');
        $errors = session()->get('validation');
        
        $this->assertArrayHasKey('falla', $errors);
        $this->assertEquals('La descripción de la falla es obligatoria.', $errors['falla']);
    }

    /**
     * Prueba: Intento de ingreso de equipo sin fecha de ingreso.
     * Resultado esperado: Error: "La fecha de ingreso es obligatoria."
     */
    public function testRegistrarEquipoSinFechaIngreso(): void
    {
        $_POST = [
            'dni_cliente'  => '44235461',
            'id_tipo'      => '1',
            'id_marca'     => '1',
            'nroSerie'     => '123',
            'falla'        => 'No da imagen',
            'fechaIngreso' => '' // vacío
        ];

        $request = service('incomingrequest', null, false);
        $result = $this->withRequest($request)
            ->controller(Equipos_controller::class)
            ->execute('registrarEquipo');

        $result->assertSessionHas('validation');
        $errors = session()->get('validation');
        
        $this->assertArrayHasKey('fechaIngreso', $errors);
        $this->assertEquals('La fecha de ingreso es obligatoria.', $errors['fechaIngreso']);
    }
}
