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

    public function tecnico()
    {
        $data['titulo'] = 'Técnico';
        return view('plantillas/nav_view', $data) . view('frontend/tecnico_view', $data) . view('plantillas/footer_view', $data);
    }

    public function registroUsuario() {
        $data['titulo'] = 'Principal';
        return view('plantillas/nav_view', $data) . view('frontend/registro_view', $data) . view('plantillas/footer_view', $data);
    }

    public function agregarEquipo()
    {
        $data['titulo'] = 'Agregar Equipo';
        return view('plantillas/nav_view', $data) . view('frontend/agregarEquipo_view', $data) . view('plantillas/footer_view', $data);
    }

    
    public function diagnostico()
    {
        $data['titulo'] = 'Diagnóstico';
        return view('plantillas/nav_view', $data) . view('frontend/diagnostico_view', $data) . view('plantillas/footer_view', $data);
    }
}
