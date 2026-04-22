<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data['titulo'] = 'Inicio';
        return view('plantillas/nav_view', $data) . view('frontend/inicio_view', $data) . view('plantillas/footer_view', $data);
    }

    public function principal()
    {
        $data['titulo'] = 'Principal';
        return view('plantillas/nav_view', $data) . view('frontend/principal_view', $data) . view('plantillas/footer_view', $data);
    }

    public function registro_usuario() {
        $data['titulo'] = 'Principal';
        return view('plantillas/nav_view', $data) . view('frontend/registro_view', $data) . view('plantillas/footer_view', $data);
    }

    public function agregar_equipo()
    {
        $data['titulo'] = 'Agregar Equipo';
        return view('plantillas/nav_view', $data) . view('frontend/agregar_equipo_view', $data) . view('plantillas/footer_view', $data);
    }

    public function tecnico()
    {
        $data['titulo'] = 'Técnico';
        return view('plantillas/nav_view', $data) . view('frontend/tecnico_view', $data) . view('plantillas/footer_view', $data);
    }
}
