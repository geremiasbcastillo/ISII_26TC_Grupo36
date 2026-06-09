<?php

namespace App\Libraries;

class UsuarioFactory
{
    /**
     * Instancia el tipo de Usuario correcto según el rol provisto.
     * 
     * @param string|int $rol Nombre del rol ('Administrador', 'Tecnico') o ID (1, 2)
     * @param array $datos Atributos para inicializar el objeto
     * @return Usuario
     * @throws \InvalidArgumentException Si el rol no es válido
     */
    public static function crearUsuario($rol, array $datos): Usuario
    {
        // Normalizamos el rol para admitir tanto el nombre textual como su ID numérico o su representación de string de número.
        switch ($rol) {
            case 1:
            case '1':
                return new Administrador($datos);
            case 2:
            case '2':
                return new Tecnico($datos);
            default:
                throw new \InvalidArgumentException("El rol especificado '{$rol}' no es válido para la fábrica de usuarios.");
        }
    }
}
