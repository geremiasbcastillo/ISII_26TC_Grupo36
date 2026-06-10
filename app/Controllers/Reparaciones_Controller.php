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
        foreach ($repuestosUsados as $repuesto) {
            $id_repuesto = $repuesto['id_repuesto'];
            $cantidad_usada = $repuesto['cantidad'];

            // Obtener el repuesto
            $repuestoData = $repuestosModel->find($id_repuesto);

            if (!$repuestoData) {
                return redirect()->back()->with('mensaje_error', 'Repuesto no encontrado: ' . $repuesto['nombre']);
            }

            // Verificar stock disponible
            if ($cantidad_usada > $repuestoData['cantidad']) {
                return redirect()->back()->with('mensaje_error', 'Stock insuficiente para: ' . $repuestoData['nombre']);
            }

            // Calcular nuevo stock
            $nuevo_stock = $repuestoData['cantidad'] - $cantidad_usada;

            // Actualizar stock del repuesto
            $repuestosModel->update($id_repuesto, ['cantidad' => $nuevo_stock]);

            // Acumular el precio del repuesto multiplicado por la cantidad utilizada
            $monto_total += $cantidad_usada * $repuestoData['monto'];

            // Verificar si el stock está por debajo del mínimo
            if ($nuevo_stock <= $repuestoData['cantidad_minima']) {
                $this->enviarAlertaStock($repuestoData['nombre'], $nuevo_stock, $repuestoData['cantidad_minima']);
            }
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

    /**
     * Envía una alerta por correo electrónico si el stock de un repuesto cae bajo el límite mínimo.
     * 
     * @param string $nombreRepuesto Nombre del repuesto con stock crítico.
     * @param int $stockRestante Cantidad restante disponible.
     * @param int $stockMinimo Límite de stock mínimo definido.
     * 
     * @return bool Retorna falso si el correo del destinatario no se encuentra en sesión.
     */
    private function enviarAlertaStock($nombreRepuesto, $stockRestante, $stockMinimo)
    {
        // Obtenemos el correo de la sesión actual
        // (Asumo que lo guardaste como 'correo' basándome en tu método de guardar_usuario)
        $correoDestino = session()->get('email'); 

        // Si por alguna razón el usuario no tiene correo en sesión, abortamos el envío
        if (empty($correoDestino)) {
            log_message('error', 'Intento de enviar alerta de stock sin correo en sesión.');
            return false; 
        }

        // Cargamos el servicio de Email
        $email = \Config\Services::email();

        // Configuramos el remitente y destinatario
        $email->setFrom('serviciotecnicounne@gmail.com', 'Sistema de Servicio Técnico');
        $email->setTo($correoDestino);
        
        // Asunto y cuerpo del correo (En formato HTML para que se vea profesional)
        $email->setSubject('⚠️ ALERTA: Stock Bajo de Repuesto');
        
        $mensajeHTML = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;'>
                <div style='background-color: #dc3545; color: white; padding: 15px; text-align: center;'>
                    <h2 style='margin: 0;'>Alerta de Inventario</h2>
                </div>
                <div style='padding: 20px;'>
                    <p>Hola,</p>
                    <p>El sistema automático de inventario ha detectado que un repuesto ha alcanzado o superado su límite mínimo tras la última reparación registrada.</p>
                    
                    <div style='background-color: #f8f9fa; border-left: 4px solid #dc3545; padding: 15px; margin: 20px 0;'>
                        <p style='margin: 0 0 10px 0;'><strong>Repuesto:</strong> {$nombreRepuesto}</p>
                        <p style='margin: 0 0 10px 0; color: #dc3545;'><strong>Stock Actual:</strong> {$stockRestante} unidades</p>
                        <p style='margin: 0;'><strong>Stock Mínimo Permitido:</strong> {$stockMinimo} unidades</p>
                    </div>
                    
                    <p>Por favor, gestiona la reposición con el proveedor correspondiente a la brevedad posible.</p>
                    <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;' />
                    <p style='font-size: 12px; color: #999;'>Este es un mensaje automático generado por el Sistema de Servicio Técnico. No respondas a este correo.</p>
                </div>
            </div>
        ";
        
        $email->setMessage($mensajeHTML);

        // Enviamos el correo
        if (!$email->send()) {
            // Si falla, guardamos el error en los logs de CodeIgniter (writable/logs/)
            // No detenemos la aplicación porque la reparación ya se guardó con éxito.
            log_message('error', 'No se pudo enviar la alerta de stock: ' . $email->printDebugger(['headers']));
        }
    }
}