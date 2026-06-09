<?php

namespace App\Libraries;

abstract class Usuario
{
    public $nombre;
    public $apellido;
    public $dni;
    public $email;
    public $contrasena;

    public function __construct(array $datos = [])
    {
        $this->nombre = $datos['nombre'] ?? '';
        $this->apellido = $datos['apellido'] ?? '';
        $this->dni = $datos['dni'] ?? '';
        $this->email = $datos['email'] ?? ($datos['correo'] ?? '');
        $this->contrasena = $datos['contrasena'] ?? '';
    }

    // Retorna el ID de rol de la base de datos (1 para Admin, 2 para Técnico)
    abstract public function getRolId(): int;

    // Retorna el nombre legible del rol
    abstract public function getRolNombre(): string;

    /**
     * Busca un usuario en la base de datos por su DNI.
     */
    public static function buscarUsuario($dni)
    {
        $model = new \App\Models\Usuarios_model();
        $userData = $model->where('dni', $dni)->first();
        if ($userData) {
            return UsuarioFactory::crearUsuario((string)$userData['id_rol'], $userData);
        }
        return null;
    }

    /**
     * Guarda o actualiza el usuario en la base de datos.
     */
    public function guardarUsuario()
    {
        $model = new \App\Models\Usuarios_model();
        
        $data = [
            'nombre'     => $this->nombre,
            'apellido'   => $this->apellido,
            'dni'        => $this->dni,
            'email'      => $this->email,
            'contrasena' => $this->contrasena,
            'id_rol'     => $this->getRolId()
        ];

        // Si ya existe por DNI, actualizamos, de lo contrario insertamos.
        $existing = $model->where('dni', $this->dni)->first();
        if ($existing) {
            return $model->update($existing['id_usuario'], $data);
        } else {
            return $model->insert($data);
        }
    }

    /**
     * Cierra la sesión activa.
     */
    public function cerrarSesion()
    {
        $session = session();
        $session->destroy();
    }
}
