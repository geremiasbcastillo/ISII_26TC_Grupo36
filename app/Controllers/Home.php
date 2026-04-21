<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('plantillas/nav_view').view('frontend/inicio_view').view('plantillas/footer_view');
    }

    public function pruebas()
    {
        $data['titulo'] = 'Pruebas';
        return view('plantillas/nav_view', $data) . view('frontend/pruebas_view', $data) . view('plantillas/footer_view', $data);
    }
}
