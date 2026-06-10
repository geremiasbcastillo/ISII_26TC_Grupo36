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
    public function mostrarFormularioRegistro()
    {
        // Cargamos los modelos necesarios para obtener los datos de marcas, tipos y modelos.
        $marcaModel  = new \App\Models\Marcas_model();
        $tipoModel   = new \App\Models\Tipos_Equipos_model();
        $modeloModel = new \App\Models\Modelos_Equipos_model();

        $data['titulo']  = 'Registrar Equipo';
        
        // Obtenemos los datos de marcas, tipos y modelos para llenar los dropdowns en la vista.
        $data['marcas']  = $marcaModel->findAll();
        $data['tipos']   = $tipoModel->findAll();
        $data['modelos'] = $modeloModel->findAll();

        return view('plantillas/nav_view', $data) 
             . view('frontend/agregarEquipo_view', $data) 
             . view('plantillas/footer_view', $data);
    }

    /**
     * Procesa y guarda el equipo verificando el DNI del cliente
     */
    public function registrarEquipo()
    {
        // Preparamos la herramienta de validación y la petición.
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();

        // Reglas de validación
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

        // Si la validación falla retorna a la vista con los errores y los datos para los dropdowns.
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
                 . view('frontend/agregarEquipo_view', $data) 
                 . view('plantillas/footer_view', $data);
        }

        // Si llegamos acá, los datos son correctos. Los guardamos en variables.
        $dni_cliente = $request->getPost('dni_cliente');
        $id_tipo     = $request->getPost('id_tipo');
        $id_modelo   = $request->getPost('id_modelo');
        $nroSerie    = $request->getPost('nroSerie');
        $falla       = $request->getPost('falla');
        $fecha       = $request->getPost('fechaIngreso');
        $equipo_estado = 1; // 1 para activo, 0 para inactivo

        // Validar que la fecha de ingreso no sea futura
        if (strtotime($fecha) > strtotime(date('Y-m-d'))) {
            return redirect()->back()->withInput()->with('mensaje_error', 'La fecha de ingreso no puede ser una fecha futura.');
        }

        // Verificación del DNI contra la tabla `cliente`
        $clienteModel = new \App\Models\Clientes_Model();
        $cliente = $clienteModel->verificarDni($dni_cliente);

        if (!$cliente) {
            return redirect()->route('agregar')->with('mensaje_error', 'Cliente no encontrado.');
        }

        $equipoModel = new \App\Models\Equipos_model();
        
        // Buscamos si ya existe algún equipo con ese número de serie en la BD
        $equipoExistente = $equipoModel->where('nroSerie', $nroSerie)->first();

        if($equipoExistente){
            return redirect()->route('agregar')->with('mensaje_error', 'Equipo ya se encuentra registrado.');
        }

        $equipoModel = new \App\Models\Equipos_model();
        
        // Cargamos los datos en un arreglo para insertar en la base de datos.
        $dataEquipo = [
            'nroSerie'     => $nroSerie,
            'falla'        => $falla,
            'fechaIngreso' => $fecha,
            'id_cliente'   => $cliente['id_cliente'], // Usamos el ID del cliente encontrado
            'id_tipo'      => $id_tipo,
            'id_modelo'    => $id_modelo,
            'equipo_estado' => $equipo_estado
        ];

        // El método insert() devuelve true si guardó bien en la BD, o false si falló.
        if ($equipoModel->insert($dataEquipo)) {
            return redirect()->route('agregar')->with('mensaje_success', 'El equipo de ' . $cliente['nombre'] . ' fue ingresado exitosamente.');
        } else {
            return redirect()->back()->withInput()->with('mensaje_error', 'Ocurrió un error en la base de datos al guardar el equipo.');
        }
    }


    /**
     * Muestra el listado de equipos activos con sus datos relacionados (tipo, marca, modelo).
     */
    public function listadoEquipos()
    {
        // Cargamos el modelo de equipos y hacemos un join con las tablas relacionadas para obtener toda la información necesaria.
        $equipo = new \App\Models\Equipos_model();
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
               ->orderBy('equipo.fechaIngreso', 'DESC')
               ->findAll();

        $data['titulo'] = 'Listado de Equipos';

        // También cargamos los datos de tipos, marcas y modelos para mostrar en la vista si es necesario.
        $data['tipos']   = $tipoModel->findAll();
        $data['marcas']  = $marcaModel->findAll();
        $data['modelos'] = $modeloModel->findAll();

        return view('plantillas/nav_view', $data)
             . view('frontend/listadoEquipos_view', $data)
             . view('plantillas/footer_view', $data);
    }

    /**
     * Procesa la edición de un equipo existente, validando los datos y actualizando el registro en la base de datos.
     */
    public function editarEquipo($id_equipo = null)
    {
        $request = \Config\Services::request();
        $equipoModel = new \App\Models\Equipos_model();
        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'id_tipo'         => 'required|numeric',
                'id_marca'        => 'required|numeric',
                'id_modelo'       => 'required|numeric',
                'nroSerie'        => 'required|min_length[3]|max_length[20]',
                'falla'           => 'required|min_length[10]'
            ],
            [
                'id_tipo' => [
                    'required' => 'Debe seleccionar el tipo de equipo.',
                    'numeric' => 'El tipo de equipo seleccionado no es válido.'
                ],
                'id_marca' => [
                    'required' => 'Debe seleccionar la marca del equipo.',
                    'numeric' => 'La marca seleccionada no es válida.'
                ],
                'id_modelo' => [
                    'required' => 'Debe seleccionar el modelo del equipo.',
                    'numeric' => 'El modelo del equipo seleccionado no es válido.'
                ],
                'nroSerie' => [
                    'required' => 'Debe ingresar el número de serie.',
                    'min_length' => 'El número de serie debe tener al menos 3 caracteres.',
                    'max_length' => 'El número de serie no puede exceder los 20 caracteres.'
                ],
                'falla' => [
                    'required' => 'Debe ingresar el motivo o falla del equipo.',
                    'min_length' => 'La falla debe tener al menos 10 caracteres.'
                ]
            ]
        );

        if (!$validation->withRequest($request)->run()) {
            return redirect()->back()->withInput()->with('errores_validation', $validation->getErrors());
        }

        // Captura el ID del equipo que viene oculto en el formulario (input type="hidden")
        $id_equipo = $request->getPost('id_equipo');

        // Buscamos el equipo en la base de datos para recuperar su fecha de ingreso original
        $equipoActual = $equipoModel->find($id_equipo);
        $fechaOriginal = $equipoActual ? $equipoActual['fechaIngreso'] : date('Y-m-d');
        
        // Prepara el arreglo con los nuevos datos actualizados, conservando la fecha de ingreso
        $dataUpdate = [
            'id_tipo'      => $request->getPost('id_tipo'),
            'id_marca'     => $request->getPost('id_marca'),
            'id_modelo'    => $request->getPost('id_modelo'),
            'nroSerie'     => $request->getPost('nroSerie'),
            'falla'        => $request->getPost('falla'),
            'fechaIngreso' => $fechaOriginal
        ];

        // Actualiza el equipo en la base de datos usando el ID capturado y los nuevos datos. 
        // Si la actualización es exitosa, redirige con un mensaje de éxito, de lo contrario muestra un error.
        if ($equipoModel->update($id_equipo, $dataUpdate)) {
            return redirect()->back()->with('mensaje_success', 'El equipo fue modificado correctamente.');
        } else {
            return redirect()->back()->with('mensaje_error', 'Ocurrió un error al intentar modificar el equipo.');
        }
    }

    /**
     * Procesa la eliminación lógica de un equipo, cambiando su estado a inactivo en la base de datos.
     */
    public function eliminarEquipo($id_equipo = null)
    {
        $equipoModel = new \App\Models\Equipos_model();

        // En lugar de usar $equipoModel->delete(), hace un UPDATE del estado a 0 (inactivo).
        if ($equipoModel->update($id_equipo, ['equipo_estado' => 0])) {
            return redirect()->route('listado')->with('mensaje_success', 'El equipo fue eliminado con éxito.');
        } else {
            return redirect()->route('listado')->with('mensaje_error', 'Ocurrió un error al intentar eliminar el equipo.');
        }
    }
}