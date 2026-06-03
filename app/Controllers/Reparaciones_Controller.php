<?php
namespace App\Controllers;

use App\Models\Clientes_Model;
use App\Models\Marcas_model;
use App\Models\Tipos_equipos_model;
use App\Models\Modelos_equipos_model;
use App\Models\Equipos_model;

class Repuestos_controller extends BaseController
{

public function procesarReparacion()
    {
        $request = \Config\Services::request();
        $repuestoModel = new \App\Models\Repuestos_model();

        // 1. Recibimos los datos del formulario (qué repuesto se usó y cuánto)
        $id_repuesto = $request->getPost('id_repuesto');
        $cantidad_usada = $request->getPost('cantidad_usada');

        // 2. Buscamos el repuesto en la BD
        $repuesto = $repuestoModel->find($id_repuesto);

        if (!$repuesto) {
            return redirect()->back()->with('mensaje_error', 'Repuesto no encontrado.');
        }

        // 3. Calculamos el nuevo stock
        $nuevo_stock = $repuesto['cantidad'] - $cantidad_usada;

        // Validamos que no quede en negativo
        if ($nuevo_stock < 0) {
            return redirect()->back()->with('mensaje_error', 'No hay stock suficiente para esta reparación.');
        }

        // 4. Actualizamos el stock en la base de datos
        $repuestoModel->update($id_repuesto, ['cantidad' => $nuevo_stock]);

        // =========================================================
        // 5. LA MAGIA: EVALUAR STOCK MÍNIMO Y ENVIAR CORREO
        // =========================================================
        if ($nuevo_stock <= $repuesto['cantidad_minima']) {
            $this->enviarAlertaStock($repuesto['nombre'], $nuevo_stock, $repuesto['cantidad_minima']);
        }

        return redirect()->route('principal')->with('mensaje_success', 'Reparación registrada exitosamente.');
    }

    private function enviarAlertaStock($nombreRepuesto, $stockRestante, $stockMinimo)
    {
        // 1. Obtenemos el correo de la sesión actual
        // (Asumo que lo guardaste como 'correo' basándome en tu método de guardar_usuario)
        $correoDestino = session()->get('correo'); 

        // Si por alguna razón el usuario no tiene correo en sesión, abortamos el envío
        if (empty($correoDestino)) {
            log_message('error', 'Intento de enviar alerta de stock sin correo en sesión.');
            return false; 
        }

        // 2. Cargamos el servicio de Email
        $email = \Config\Services::email();

        // 3. Configuramos el remitente y destinatario
        // NOTA: El 'from' debe coincidir con el correo que configuraste en tu archivo .env
        $email->setFrom('serviciotecnicounne@gmail.com', 'Sistema de Servicio Técnico');
        $email->setTo($correoDestino);
        
        // 4. Asunto y cuerpo del correo (En formato HTML para que se vea profesional)
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

        // 5. Enviamos el correo
        if (!$email->send()) {
            // Si falla, guardamos el error en los logs de CodeIgniter (writable/logs/)
            // No detenemos la aplicación porque la reparación ya se guardó con éxito.
            log_message('error', 'No se pudo enviar la alerta de stock: ' . $email->printDebugger(['headers']));
        }
    }
}