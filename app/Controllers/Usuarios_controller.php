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
                return redirect()->route('tecnico'); // Cliente
            default:
                return redirect()->route('principal'); 
        }
        } else {
                // En caso de error, redirige de vuelta al login
                return redirect()->route('inicio')->with('mensaje_error', 'Usuario y/o contraseña incorrectos!. O su usuario se encuentra inactivo.');
        }
    }

    public function guardar_usuario()
    {
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();
        $session = session();

        // 1. Reglas de validación
        $validation->setRules(
            [
                'nombre'      => 'required|alpha_space|min_length[2]',
                'apellido'    => 'required|alpha_space|min_length[2]',
                'dni'         => 'required|min_length[5]',
                'correo'      => 'required|valid_email|is_unique[usuario.email]',
                'rol'         => 'required',
                'contrasena'  => 'required|min_length[4]'
            ],
            [
                'nombre' => [
                    'required'   => 'El nombre es obligatorio.',
                    'alpha_space' => 'El nombre solo puede contener letras y espacios.',
                    'min_length' => 'El nombre debe tener al menos 2 caracteres.'
                ],
                'apellido' => [
                    'required'   => 'El apellido es obligatorio.',
                    'alpha_space' => 'El apellido solo puede contener letras y espacios.',
                    'min_length' => 'El apellido debe tener al menos 2 caracteres.'
                ],
                'dni' => [
                    'required'   => 'El DNI es obligatorio.',
                    'min_length' => 'El DNI debe tener al menos 5 caracteres.'
                ],
                'correo' => [
                    'required'   => 'El correo es obligatorio.',
                    'valid_email' => 'El correo debe tener un formato válido.',
                    'is_unique'  => 'Este correo ya está registrado.'
                ],
                'rol' => [
                    'required' => 'Debe seleccionar un rol.'
                ],
                'contrasena' => [
                    'required'   => 'La contraseña es obligatoria.',
                    'min_length' => 'La contraseña debe tener al menos 4 caracteres.'
                ]
            ]
        );

        // 2. Validar formulario
        if (!$validation->withRequest($request)->run()) {
            $data['titulo'] = 'Registro';
            $data['validation'] = $validation->getErrors();
            return view('plantillas/nav_view', $data) . view('frontend/registro_view', $data) . view('plantillas/footer_view');
        }

        // 3. Obtener datos del formulario
        $nombre = $request->getPost('nombre');
        $apellido = $request->getPost('apellido');
        $dni = $request->getPost('dni');
        $correo = $request->getPost('correo');
        $rol = $request->getPost('rol');
        $contrasena = $request->getPost('contrasena');

        // 4. Mapear rol texto a ID
        $rol_id = ($rol === 'Administrador') ? 1 : 2; // 1 = Admin, 2 = Técnico

        // 5. Preparar datos para guardar
        $data_registro = [
            'nombre'      => $nombre,
            'apellido'    => $apellido,
            'dni'         => $dni,
            'email'       => $correo,
            'contrasena'  => $contrasena, // En producción usar hash: password_hash($contrasena, PASSWORD_DEFAULT)
            'id_rol'      => $rol_id
        ];

        // 6. Usar el modelo para insertar
        $model = new \App\Models\Usuarios_model();
        
        if ($model->insert($data_registro)) {
            // Éxito
            return redirect()->route('inicio')->with('mensaje_exito', '¡Usuario registrado correctamente! Por favor inicia sesión.');
        } else {
            // Error al insertar
            return redirect()->route('registro')->with('mensaje_error', 'Error al registrar el usuario. Intenta de nuevo.');
        }
    }

    public function cerrar_sesion()
    {
        $session = session();
        $session->destroy();
        return redirect()->route('inicio');
    }
}