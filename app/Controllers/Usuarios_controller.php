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

        $validation->setRules(
            [
                'correo'  => 'required|valid_email',
                'contrasena' => 'required|min_length[2]'
            ],
            [   'correo' => [
                    'required'    => 'El correo es obligatorio.',
                    'valid_email' => 'El correo debe tener un formato válido.'
                ],
                'contrasena' => [
                    'required'   => 'La contraseña es obligatoria.',
                    'min_length' => 'La contraseña debe tener al menos 2 caracteres.'
                ]
            ]
        );

        if (!$validation->withRequest($request)->run()) {
            $data['titulo'] = 'Inicio de sesión';
            $data['validation'] = $validation->getErrors();
            
            return view('plantillas/nav_view', $data) . view('frontend/inicio_view', $data) . view('plantillas/footer_view');
        }

        $email = $request->getPost('correo'); // En el HTML lo llamaste "correo"
        $pass  = $request->getPost('contrasena');

        $model = new \App\Models\Usuarios_model();
        
        $user = $model->where('email', $email)->first();

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

        // Redirijo según roles
        switch ($user['id_rol']) {
            case '1': // Admin
                return redirect()->route('principal');
            case '2': 
                return redirect()->route('tecnico'); // Cliente
            default:
                return redirect()->route('principal'); 
        }
        } else {
                return redirect()->route('inicio')->with('mensaje_error', 'Usuario y/o contraseña incorrectos!. O su usuario se encuentra inactivo.');
        }
    }

    public function guardar_usuario()
    {
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();
        $session = session();

        // Reglas de validación
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

        if (!$validation->withRequest($request)->run()) {
            $data['titulo'] = 'Registro';
            $data['validation'] = $validation->getErrors();
            return view('plantillas/nav_view', $data) . view('frontend/registro_view', $data) . view('plantillas/footer_view');
        }

        $nombre = $request->getPost('nombre');
        $apellido = $request->getPost('apellido');
        $dni = $request->getPost('dni');
        $correo = $request->getPost('correo');
        $rol = $request->getPost('rol');
        $contrasena = $request->getPost('contrasena');

        // Mapeamos rol texto a ID
        $rol_id = ($rol === 'Administrador') ? 1 : 2; // 1 = Admin, 2 = Técnico

        $data_registro = [
            'nombre'      => $nombre,
            'apellido'    => $apellido,
            'dni'         => $dni,
            'email'       => $correo,
            'contrasena'  => $contrasena, // En producción usar hash: password_hash($contrasena, PASSWORD_DEFAULT)
            'id_rol'      => $rol_id
        ];

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