<?php

namespace App\Controllers;

use App\Models\Clientes_Model;
use App\Models\Marcas_model;
use App\Models\Tipos_equipos_model;
use App\Models\Modelos_equipos_model;
use App\Models\Equipos_model;

class Repuestos_controller extends BaseController
{
    public function guardarRepuesto()
    {
        // Preparamos la herramienta de validación y la petición.
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();

        // Reglas de validación basadas en los campos de tu tabla 'repuesto'
        $validation->setRules(
            [
                'nombre'                => 'required|max_length[60]',
                'id_categoria_repuesto' => 'required|is_natural',
                'cantidad'              => 'required|is_natural',
                'cantidad_minima'       => 'required|is_natural',
                'monto'                 => 'required|numeric'
            ],
            [
                'nombre' => [
                    'required'   => 'El nombre del repuesto es obligatorio.',
                    'max_length' => 'El nombre no puede exceder los 60 caracteres.'
                ],
                'id_categoria_repuesto' => [
                    'required'   => 'Debe seleccionar una categoría.',
                    'is_natural' => 'La categoría seleccionada no es válida.'
                ],
                'cantidad' => [
                    'required'   => 'La cantidad inicial en stock es obligatoria.',
                    'is_natural' => 'La cantidad debe ser un número entero positivo o cero.'
                ],
                'cantidad_minima' => [
                    'required'   => 'El stock mínimo es obligatorio.',
                    'is_natural' => 'El stock mínimo debe ser un número entero positivo o cero.'
                ],
                'monto' => [
                    'required' => 'El monto (precio unitario) es obligatorio.',
                    'numeric'  => 'El monto debe tener un formato numérico válido.'
                ]
            ]
        );

        // Si la validación falla retorna a la vista con los errores y los datos para los dropdowns.
        if (!$validation->withRequest($request)->run()) {
            $data['titulo'] = 'Registrar Repuesto';
            $data['validation'] = $validation->getErrors();
            
            // Cargamos el modelo de categorías para poblar el select en caso de error de validación
            // (Asegúrate de que el nombre del modelo coincida con el que tienes en tu carpeta Models)
            $categoriaModel = new \App\Models\Categorias_model(); 
            $data['categorias'] = $categoriaModel->findAll();

            return view('plantillas/nav_view', $data) 
                . view('frontend/agregar_repuesto_view', $data) // Ajusta si el nombre de tu vista es distinto
                . view('plantillas/footer_view', $data);
        }

        // Si llegamos acá, los datos son correctos. Los guardamos en variables.
        $nombre                = $request->getPost('nombre');
        $id_categoria_repuesto = $request->getPost('id_categoria_repuesto');
        $cantidad              = $request->getPost('cantidad');
        $cantidad_minima       = $request->getPost('cantidad_minima');
        $monto                 = $request->getPost('monto');

        // Instanciamos el modelo de Repuestos
        $repuestoModel = new \App\Models\Repuestos_model(); 

        // Opcional: Verificamos si ya existe un repuesto con exactamente el mismo nombre para evitar duplicados
        $repuestoExistente = $repuestoModel->where('nombre', $nombre)->first();

        if ($repuestoExistente) {
            return redirect()->back()->withInput()->with('mensaje_error', 'Ya existe un repuesto registrado con ese nombre.');
        }

        // Cargamos los datos en un arreglo para insertar en la base de datos (respetando los nombres de tu tabla).
        $dataRepuesto = [
            'nombre'                => $nombre,
            'cantidad'              => $cantidad,
            'monto'                 => $monto,
            'cantidad_minima'       => $cantidad_minima,
            'id_categoria_repuesto' => $id_categoria_repuesto
        ];

        // El método insert() devuelve true si guardó bien en la BD, o false si falló.
        if ($repuestoModel->insert($dataRepuesto)) {
            // Redirige a donde consideres adecuado (ej. la lista de repuestos o el menú principal)
            return redirect()->route('principal')->with('mensaje_success', 'El repuesto "' . $nombre . '" fue registrado exitosamente.');
        } else {
            return redirect()->back()->withInput()->with('mensaje_error', 'Ocurrió un error en la base de datos al intentar guardar el repuesto.');
        }
    }

    public function guardar_edicion()
    {
        // 1. Obtener la petición y la validación
        $request = service('request');
        $validation = service('validation');
        
        // 2. Definir las reglas de validación (similar a guardar, pero ahora permitimos el ID)
        $validation->setRules([
            'id_repuesto'           => 'required|is_natural',
            'nombre'                => 'required|max_length[60]',
            'id_categoria_repuesto' => 'required|is_natural',
            'cantidad'              => 'required|is_natural',
            'cantidad_minima'       => 'required|is_natural',
            'monto'                 => 'required|numeric'
        ]);

        // 3. Ejecutar la validación
        if (!$validation->withRequest($request)->run()) {
            // Si hay errores, redirigir al usuario a donde estaba (lista o editar)
            return redirect()->back()->withInput()->with('errores_validacion', $validation->getErrors());
        }

        // 4. Obtener los datos limpios
        $id_repuesto           = $request->getPost('id_repuesto');
        $nombre                = $request->getPost('nombre');
        $id_categoria_repuesto = $request->getPost('id_categoria_repuesto');
        $cantidad              = $request->getPost('cantidad');
        $cantidad_minima       = $request->getPost('cantidad_minima');
        $monto                 = $request->getPost('monto');

        // 5. Instanciar el modelo
        $repuestoModel = new \App\Models\Repuestos_Model();

        // 6. (Opcional) Verificar unicidad del nombre (excluyendo el propio registro)
        $repuestoExistente = $repuestoModel->where('nombre', $nombre)
                                          ->where('id_repuesto !=', $id_repuesto)
                                          ->first();

        if ($repuestoExistente) {
            return redirect()->back()->withInput()->with('mensaje_error', 'Ya existe otro repuesto con ese nombre.');
        }

        // 7. Preparar datos para actualizar
        $data = [
            'nombre'                => $nombre,
            'cantidad'              => $cantidad,
            'monto'                 => $monto,
            'cantidad_minima'       => $cantidad_minima,
            'id_categoria_repuesto' => $id_categoria_repuesto
        ];

        // 8. Ejecutar la actualización usando el ID
        if ($repuestoModel->update($id_repuesto, $data)) {
            // Éxito
            return redirect()->route('stock_repuestos')->with('mensaje_success', 'Repuesto actualizado correctamente.');
        } else {
            // Fallo en la base de datos
            return redirect()->back()->withInput()->with('mensaje_error', 'Error al actualizar en la base de datos.');
        }
    }

    
}