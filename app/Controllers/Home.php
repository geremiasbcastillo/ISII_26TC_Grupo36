<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('plantillas/nav_view').view('frontend/inicio_view').view('plantillas/footer_view');
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
}
