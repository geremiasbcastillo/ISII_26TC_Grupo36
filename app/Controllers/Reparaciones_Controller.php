<?php
namespace App\Controllers;

use App\Models\Clientes_Model;
use App\Models\Marcas_model;
use App\Models\Tipos_equipos_model;
use App\Models\Modelos_equipos_model;
use App\Models\Equipos_model;
use App\Models\Repuestos_Model;
use App\Models\Diagnosticos_model;
use App\Models\Reparaciones_Model;

/**
 * Clase Reparaciones_Controller
 * 
 * Controlador encargado de gestionar el proceso de registro de reparaciones de los equipos,
 * el control de inventario de repuestos utilizados y el envío de alertas de stock.
 */
class Reparaciones_Controller extends BaseController
{
    /**
     * Muestra la pantalla principal de registro de reparaciones.
     * Carga el listado de equipos que ya cuentan con diagnóstico y los repuestos disponibles en stock.
     */
    public function index()
    {
        $equiposModel = new Equipos_model();
        $repuestosModel = new Repuestos_Model();
        $diagnosticosModel = new Diagnosticos_model();

        // Obtener IDs de equipos que tienen diagnósticos registrados
        $equiposDiagnosticados = $diagnosticosModel->distinct()->select('id_equipo')->findAll();
        $equiposDiagnosticadosIds = array_column($equiposDiagnosticados, 'id_equipo');

        // Obtener equipos que tienen diagnósticos y están activos (estado 1)
        $equipos = $equiposModel->select('equipo.id_equipo, equipo.nroSerie, equipo.equipo_estado, 
                                         tipo_equipo.nombre as tipo_nombre, 
                                         marca.nombre as marca_nombre, 
                                         modelo_equipo.nombre as modelo_nombre')
                                ->join('tipo_equipo', 'tipo_equipo.id_tipo = equipo.id_tipo')
                                ->join('modelo_equipo', 'modelo_equipo.id_modelo = equipo.id_modelo')
                                ->join('marca', 'marca.id_marca = modelo_equipo.id_marca')
                                ->where('equipo.equipo_estado', 1)
                                ->whereIn('equipo.id_equipo', !empty($equiposDiagnosticadosIds) ? $equiposDiagnosticadosIds : [0])
                                ->findAll();

        // Obtener todos los repuestos disponibles con stock mayor a cero
        $repuestos = $repuestosModel->select('id_repuesto, nombre, cantidad')
                                    ->where('cantidad >', 0)
                                    ->findAll();

        $data = [
            'equipos' => $equipos,
            'repuestos' => $repuestos,
            'titulo' => 'Reparación'
        ];

        return view('plantillas/nav_view', $data) . view('frontend/reparacion_view', $data) . view('plantillas/footer_view', $data);
    }

    /**
     * Procesa el formulario de guardado de una reparación.
     * Valida la información, calcula los montos, reduce el stock, asocia el diagnóstico y
     * persiste los datos en las tablas correspondientes.
     */
    public function guardarReparacion()
    {
        $request = \Config\Services::request();
        $equiposModel = new Equipos_model();
        $repuestosModel = new Repuestos_Model();
        $diagnosticosModel = new Diagnosticos_model();

        // Obtener los datos enviados por POST del formulario
        $id_equipo = $request->getPost('id_equipo');
        $observaciones = $request->getPost('observaciones');
        $repuestos_json = $request->getPost('repuestos_json');

        // Validaciones
        $validation = \Config\Services::validation();

        $validation->setRules([
            'id_equipo' => 'required',
            'repuestos_json' => 'required'],
            ['id_equipo' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debes seleccionar un equipo.'
                ]
            ],
            'repuestos_json' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debes agregar al menos un repuesto.'
                ]
            ]
        ]);

        // Si falla la validación, retornar a la vista con los errores
        if (!$validation->withRequest($request)->run()) {
            $data['titulo'] = 'Reparación';
            $data['validation'] = $validation->getErrors();
            return redirect()->back()->withInput()->with('validation', $validation->getErrors());
        }

        // Decodificar el JSON de repuestos utilizados
        $repuestosUsados = json_decode($repuestos_json, true);

        // Verificar que el equipo exista y este activo
        $equipo = $equiposModel->encontrarEquipoActivo($id_equipo);
        if (!$equipo) {
            return redirect()->back()->with('mensaje_error', 'Equipo no encontrado o ya no está activo.');
        }

        // Obtener el diagnóstico asociado al equipo para poder registrar la reparación
        $diagnostico = $diagnosticosModel->where('id_equipo', $id_equipo)->orderBy('id_diagnostico', 'DESC')->first();
        if (!$diagnostico) {
            return redirect()->back()->with('mensaje_error', 'No se encontró un diagnóstico registrado para este equipo.');
        }
        $id_diagnostico = $diagnostico['id_diagnostico'];

        $monto_total = 0;

        // Procesar cada repuesto utilizado
        foreach ($repuestosUsados as $repuestoInfo) {
            $id_repuesto = $repuestoInfo['id_repuesto'];
            $cantidad_usada = $repuestoInfo['cantidad'];

            // Buscamos y construimos el objeto de negocio Repuesto
            $repuestoObj = \App\Libraries\Repuesto::buscarRepuesto($id_repuesto);

            if (!$repuestoObj) {
                return redirect()->back()->with('mensaje_error', 'Repuesto no encontrado: ' . $repuestoInfo['nombre']);
            }

            // Verificar stock disponible
            if ($cantidad_usada > $repuestoObj->cantidad) {
                return redirect()->back()->with('mensaje_error', 'Stock insuficiente para: ' . $repuestoObj->nombre);
            }

            // Actualizar stock del repuesto en el objeto
            $repuestoObj->cantidad -= $cantidad_usada;

            // Registrar el observador para enviar el email
            $repuestoObj->agregarObservador(new \App\Libraries\NotificadorEmail());

            // Guardar edición (esto persistirá en la BD y verificará/notificará si el stock está bajo)
            $repuestoObj->guardarEdicion();

            // Acumular el precio del repuesto multiplicado por la cantidad utilizada
            $monto_total += $cantidad_usada * $repuestoObj->monto;
        }

        // guardamos la reparación
        $reparacionesModel = new Reparaciones_Model();
        $id_reparacion = $reparacionesModel->insert([
            'fecha_reparacion' => date('Y-m-d'),
            'id_diagnostico' => $id_diagnostico,
            'monto_total' => $monto_total
        ]);

        if ($id_reparacion) {
            // Guardamos la relación de repuestos utilizados en la tabla intermedia
            $db = \Config\Database::connect();
            foreach ($repuestosUsados as $repuesto) {
                $db->table('repuesto_reparacion')->insert([
                    'id_reparacion' => $id_reparacion,
                    'id_repuesto'   => $repuesto['id_repuesto']
                ]);
            }
        }
        
        // Actualizar el estado del equipo a inactivo (reparado y fuera del sistema)
        $equiposModel->update($id_equipo, [
            'equipo_estado' => 0
        ]);

        // Obtener los datos actualizados para la vista
        $equiposModel = new Equipos_model();
        $repuestosModel = new Repuestos_Model();
        $diagnosticosModel = new Diagnosticos_model();

        $equiposDiagnosticados = $diagnosticosModel->distinct()->select('id_equipo')->findAll();
        $equiposDiagnosticadosIds = array_column($equiposDiagnosticados, 'id_equipo');

        $equipos = $equiposModel->select('equipo.id_equipo, equipo.nroSerie, equipo.equipo_estado, 
                                         tipo_equipo.nombre as tipo_nombre, 
                                         marca.nombre as marca_nombre, 
                                         modelo_equipo.nombre as modelo_nombre')
                                ->join('tipo_equipo', 'tipo_equipo.id_tipo = equipo.id_tipo')
                                ->join('modelo_equipo', 'modelo_equipo.id_modelo = equipo.id_modelo')
                                ->join('marca', 'marca.id_marca = modelo_equipo.id_marca')
                                ->where('equipo.equipo_estado', 1)
                                ->whereIn('equipo.id_equipo', !empty($equiposDiagnosticadosIds) ? $equiposDiagnosticadosIds : [0])
                                ->findAll();

        $repuestos = $repuestosModel->select('id_repuesto, nombre, cantidad')
                                    ->where('cantidad >', 0)
                                    ->findAll();

        $data = [
            'equipos' => $equipos,
            'repuestos' => $repuestos,
            'titulo' => 'Reparación',
            'mensaje_success' => 'Reparación registrada exitosamente.'
        ];

        return redirect()->to('reparacion')->with('data', $data);
    }
}