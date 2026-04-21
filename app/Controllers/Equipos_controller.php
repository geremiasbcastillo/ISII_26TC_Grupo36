<?php

namespace App\Controllers;

use App\Models\Clientes_Model;
use App\Models\Marcas_model;
use App\Models\Tipos_equipos_model;
use App\Models\Modelos_equipos_model;
use App\Models\Equipos_model;

class Equipos_controller extends BaseController
{
    /**
     * Muestra la vista del formulario cargando los datos para los dropdowns
     */
    public function formulario_registro()
    {
        $marcaModel  = new \App\Models\Marcas_model();
        $tipoModel   = new \App\Models\Tipos_Equipos_model();
        $modeloModel = new \App\Models\Modelos_Equipos_model();

        $data['titulo']  = 'Registrar Equipo';
        
        // Cargamos todos los datos para llenar los <select>
        $data['marcas']  = $marcaModel->findAll();
        $data['tipos']   = $tipoModel->findAll();
        $data['modelos'] = $modeloModel->findAll();

        return view('plantillas/nav_view', $data) 
             . view('frontend/agregar_equipo_view', $data) 
             . view('plantillas/footer_view', $data);
    }

    /**
     * Procesa y guarda el equipo verificando el DNI del cliente
     */
    public function guardar()
    {
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();

        // 1. Reglas de validación adaptadas a los dropdowns
        $validation->setRules(
            [
                'dni_cliente'  => 'required|numeric',
                'id_tipo'      => 'required',
                'id_marca'     => 'required',
                'id_modelo'    => 'required',
                'nroSerie'     => 'required|numeric',
                'falla'        => 'required',
                'fechaIngreso' => 'required|valid_date'
            ],
            [
                'dni_cliente' => [
                    'required' => 'El DNI del cliente es obligatorio.',
                    'numeric'  => 'El DNI debe contener solo números.'
                ],
                'id_tipo'   => ['required' => 'Debe seleccionar un tipo de equipo.'],
                'id_marca'  => ['required' => 'Debe seleccionar una marca.'],
                'id_modelo' => ['required' => 'Debe seleccionar un modelo.']
            ]
        );

        // 2. Si la validación falla, recargamos la vista con los errores y los combos
        if (!$validation->withRequest($request)->run()) {
            $data['titulo'] = 'Registrar Equipo';
            $data['validation'] = $validation->getErrors();
            
            $marcaModel  = new \App\Models\Marcas_model();
            $tipoModel   = new \App\Models\Tipos_Equipos_model();
            $modeloModel = new \App\Models\Modelo_Equipos_model();
            
            $data['marcas']  = $marcaModel->findAll();
            $data['tipos']   = $tipoModel->findAll();
            $data['modelos'] = $modeloModel->findAll();

            return view('plantillas/nav_view', $data) 
                 . view('frontend/registrar_equipo_view', $data) 
                 . view('plantillas/footer_view', $data);
        }

        // 3. Obtener datos limpios del POST
        $dni_cliente = $request->getPost('dni_cliente');
        $id_tipo     = $request->getPost('id_tipo');
        $id_modelo   = $request->getPost('id_modelo');
        $nroSerie    = $request->getPost('nroSerie');
        $falla       = $request->getPost('falla');
        $fecha       = $request->getPost('fechaIngreso');

        // 4. Verificación del DNI contra la tabla `cliente`
        $clienteModel = new \App\Models\Clientes_Model();
        $cliente = $clienteModel->where('dni', $dni_cliente)->first();

        // Si escriben un DNI que no es 12345678 o 45115264 (los que tienes en BD), rebota
        if (!$cliente) {
            return redirect()->route('agregar')->with('mensaje_error', 'El DNI ingresado no pertenece a un cliente registrado en el sistema.');
        }

        // 5. Preparar datos para la tabla `equipo`
        $equipoModel = new \App\Models\Equipos_model();
        
        $dataEquipo = [
            'nroSerie'     => $nroSerie,
            'falla'        => $falla,
            'fechaIngreso' => $fecha,
            'id_cliente'   => $cliente['id_cliente'], // Usamos el ID del cliente encontrado
            'id_tipo'      => $id_tipo,
            'id_modelo'    => $id_modelo
        ];

        // 6. Insertar y redirigir
        if ($equipoModel->insert($dataEquipo)) {
            return redirect()->route('principal')->with('mensaje_success', 'El equipo de ' . $cliente['nombre'] . ' fue ingresado exitosamente.');
        } else {
            return redirect()->back()->withInput()->with('mensaje_error', 'Ocurrió un error en la base de datos al guardar el equipo.');
        }
    }
}