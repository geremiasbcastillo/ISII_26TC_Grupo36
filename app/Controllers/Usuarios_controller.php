<?php

namespace App\Controllers;
use App\Models\Usuarios_model;

class Usuarios_controller extends BaseController
{
    public function buscar_usuario()
    {
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();
        $session = session();

        // 1. Reglas de validación ajustadas a los nombres del formulario en inicio_view.php
        $validation->setRules(
            [
                'correo'  => 'required|valid_email',
                'contrasena' => 'required|min_length[2]'
            ],
            [   // Mensajes de error personalizados
                'correo' => [
                    'required'    => 'El correo es obligatorio.',
                    'valid_email' => 'El correo debe tener un formato válido.'
                ],
                'contrasena' => [
                    'required'   => 'La contraseña es obligatoria.',
                    'min_length' => 'La contraseña debe tener al menos 2 caracteres.'
                ]
            ]
        );

        // 2. Validación de formulario
        if (!$validation->withRequest($request)->run()) {
            $data['titulo'] = 'Inicio de sesión';
            $data['validation'] = $validation->getErrors();
            
            // Ajustado a la estructura de carpetas que parece tener tu proyecto
            return view('plantillas/nav_view', $data) . view('frontend/inicio_view', $data) . view('plantillas/footer_view');
        }

        // 3. Obtención de datos del POST
        $email = $request->getPost('correo'); // En el HTML lo llamaste "correo"
        $pass  = $request->getPost('contrasena');

        // 4. Uso del Modelo Usuarios_model (con namespace)
        $model = new \App\Models\Usuarios_model();
        
        // Buscamos por la columna 'email' definida en tu modelo
        $user = $model->where('email', $email)->first();

        // 5. Verificación de contraseña y carga de sesión
        // Nota: Se usa 'contraseña' con ñ porque así está en tu $allowedFields
        if ($user && $pass === $user['contrasena']) {
        $sessionData = [
            'id'       => $user['id_usuario'],
            'nombre'   => $user['nombre'],
            'apellido' => $user['apellido'],
            'email'    => $user['email'],
            'rol'      => $user['id_rol'],
            'login'    => true,
        ];
        $session->set($sessionData);

        // 6. Redirección por roles
        switch ($user['id_rol']) {
            case '1': // Admin
                return redirect()->route('principal');
            case '2': 
                return redirect()->route('principal'); // Cliente
            default:
                return redirect()->route('principal'); 
        }
        } else {
                // En caso de error, redirige de vuelta al login
                return redirect()->route('inicio')->with('mensaje_error', 'Usuario y/o contraseña incorrectos!. O su usuario se encuentra inactivo.');
        }
    }
}