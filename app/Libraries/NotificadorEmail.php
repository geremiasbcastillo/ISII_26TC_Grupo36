<?php

namespace App\Libraries;

class NotificadorEmail implements IObservador
{
    /**
     * Envía una alerta por correo electrónico si el stock de un repuesto cae bajo el límite mínimo.
     * 
     * @param Repuesto $repuesto El repuesto cuya cantidad ha disminuido por debajo del stock mínimo.
     */
    public function actualizar(Repuesto $repuesto)
    {
        // Obtenemos el correo de la sesión actual
        $correoDestino = session()->get('email'); 

        // Si por alguna razón el usuario no tiene correo en sesión, abortamos el envío
        if (empty($correoDestino)) {
            log_message('error', 'Intento de enviar alerta de stock sin correo en sesión.');
            return; 
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
                        <p style='margin: 0 0 10px 0;'><strong>Repuesto:</strong> {$repuesto->nombre}</p>
                        <p style='margin: 0 0 10px 0; color: #dc3545;'><strong>Stock Actual:</strong> {$repuesto->cantidad} unidades</p>
                        <p style='margin: 0;'><strong>Stock Mínimo Permitido:</strong> {$repuesto->stockMinimo} unidades</p>
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
            // Si falla, guardamos el error en los logs de CodeIgniter
            log_message('error', 'No se pudo enviar la alerta de stock: ' . $email->printDebugger(['headers']));
        }
    }
}
