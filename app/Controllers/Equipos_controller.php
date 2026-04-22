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
    public function registrarEquipo()
    {
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();

        // 1. Reglas de validación adaptadas a los dropdowns
        $validation->setRules(
            [
                'dni_cliente'  => 'required|numeric',
                'id_tipo'      => 'required',
                'id_marca'     => 'required',
                'nroSerie'     => 'required|numeric',
                'falla'        => 'required',
                'fechaIngreso' => 'required|valid_date'
            ],
            [
                'dni_cliente' => [
                    'required' => 'El DNI del cliente es obligatorio.',
                    'numeric'  => 'El DNI debe contener solo números.'
                ],
                'nroSerie' => [
                    'required' => 'El número de serie es obligatorio.',
                    'numeric'  => 'El número de serie debe contener solo números.'
                ],
                'fechaIngreso' => [
                    'required' => 'La fecha de ingreso es obligatoria.',
                    'valid_date' => 'Ingrese una fecha válida.'
                ],
                'falla' => [
                    'required' => 'La descripción de la falla es obligatoria.'
                ],
                'id_tipo'   => ['required' => 'Los datos ingresados son invalidos.'],
                'id_marca'  => ['required' => 'Los datos ingresados son invalidos.'],
            ]
        );

        // 2. Si la validación falla, recargamos la vista con los errores y los combos
        if (!$validation->withRequest($request)->run()) {
            $data['titulo'] = 'Registrar Equipo';
            $data['validation'] = $validation->getErrors();
            
            $marcaModel  = new \App\Models\Marcas_model();
            $tipoModel   = new \App\Models\Tipos_Equipos_model();
            $modeloModel = new \App\Models\Modelos_Equipos_model();
            
            $data['marcas']  = $marcaModel->findAll();
            $data['tipos']   = $tipoModel->findAll();
            $data['modelos'] = $modeloModel->findAll();

            return view('plantillas/nav_view', $data) 
                 . view('frontend/agregar_equipo_view', $data) 
                 . view('plantillas/footer_view', $data);
        }

        // 3. Obtener datos limpios del POST
        $dni_cliente = $request->getPost('dni_cliente');
        $id_tipo     = $request->getPost('id_tipo');
        $id_modelo   = $request->getPost('id_modelo');
        $nroSerie    = $request->getPost('nroSerie');
        $falla       = $request->getPost('falla');
        $fecha       = $request->getPost('fechaIngreso');
        $equipo_estado = 1; // 1 para activo, 0 para inactivo

        // 4. Verificación del DNI contra la tabla `cliente`
        $cliente = $this->verficarDni($dni_cliente);

        // Si escriben un DNI que no es 12345678 o 45115264 (los que tienes en BD), rebota
        if (!$cliente) {
            return redirect()->route('agregar')->with('mensaje_error', 'Cliente no encontrado.');
        }

        $equipoModel = new \App\Models\Equipos_model();
        
        // Buscamos si ya existe algún equipo con ese número de serie en la BD
        $equipoExistente = $equipoModel->where('nroSerie', $nroSerie)->first();

        if($equipoExistente){
            return redirect()->route('agregar')->with('mensaje_error', 'Equipo ya se encuentra registrado.');
        }

        // 5. Preparar datos para la tabla `equipo`
        $equipoModel = new \App\Models\Equipos_model();
        
        $dataEquipo = [
            'nroSerie'     => $nroSerie,
            'falla'        => $falla,
            'fechaIngreso' => $fecha,
            'id_cliente'   => $cliente['id_cliente'], // Usamos el ID del cliente encontrado
            'id_tipo'      => $id_tipo,
            'id_modelo'    => $id_modelo,
            'equipo_estado' => $equipo_estado
        ];

        // 6. Insertar y redirigir
        if ($equipoModel->insert($dataEquipo)) {
            return redirect()->route('principal')->with('mensaje_success', 'El equipo de ' . $cliente['nombre'] . ' fue ingresado exitosamente.');
        } else {
            return redirect()->back()->withInput()->with('mensaje_error', 'Ocurrió un error en la base de datos al guardar el equipo.');
        }
    }

    public function verficarDni($dni)
    {
        $clienteModel = new \App\Models\Clientes_Model();
        // Retorna el arreglo del cliente si lo encuentra, o null si no existe
        return $clienteModel->where('dni', $dni)->first(); 
    }

    public function listado_equipos()
    {
        $equipo = new \App\Models\Equipos_model();
        
        // 1. Instanciamos los modelos extra que necesita el Modal
        $tipoModel   = new \App\Models\Tipos_equipos_model();
        $marcaModel  = new \App\Models\Marcas_model();
        $modeloModel = new \App\Models\Modelos_equipos_model();

        $data['equipos'] = $equipo->select('
                equipo.*, 
                modelo_equipo.id_marca, 
                tipo_equipo.nombre as tipo_nombre, 
                modelo_equipo.nombre as modelo_nombre, 
                marca.nombre as marca_nombre
               ')
               ->join('tipo_equipo', 'tipo_equipo.id_tipo = equipo.id_tipo')
               ->join('modelo_equipo', 'modelo_equipo.id_modelo = equipo.id_modelo')
               ->join('marca', 'marca.id_marca = modelo_equipo.id_marca')
               ->where('equipo.equipo_estado', 1) // Solo equipos activos
               ->findAll();

        $data['titulo'] = 'Listado de Equipos';

        // 2. Cargamos los datos en el array para que la Vista los encuentre
        $data['tipos']   = $tipoModel->findAll();
        $data['marcas']  = $marcaModel->findAll();
        $data['modelos'] = $modeloModel->findAll();

        return view('plantillas/nav_view', $data)
             . view('frontend/listado_equipos_view', $data)
             . view('plantillas/footer_view', $data);
    }

    public function editar_equipo($id = null)
    {
        $request = \Config\Services::request();
        $equipoModel = new \App\Models\Equipos_model();

        $id_equipo = $request->getPost('id_equipo');
        
        $dataUpdate = [
            'id_tipo'   => $request->getPost('id_tipo'),
            'id_marca'  => $request->getPost('id_marca'),
            'id_modelo' => $request->getPost('id_modelo'),
            'nroSerie'  => $request->getPost('nroSerie'),
            'falla'     => $request->getPost('falla')
        ];

        // Usamos el método update() propio de CodeIgniter
        if ($equipoModel->update($id_equipo, $dataUpdate)) {
            return redirect()->back()->with('mensaje_success', 'El equipo fue modificado correctamente.');
        } else {
            return redirect()->back()->with('mensaje_error', 'Ocurrió un error al intentar modificar el equipo.');
        }
    }

    public function eliminarEquipo($id_equipo = null)
    {
        $equipoModel = new \App\Models\Equipos_model();

        if ($equipoModel->update($id_equipo, ['equipo_estado' => 0])) {
            return redirect()->route('principal')->with('mensaje_success', 'El equipo fue eliminado correctamente.');
        } else {
            return redirect()->route('listado')->with('mensaje_error', 'Ocurrió un error al intentar eliminar el equipo.');
        }
    }
}