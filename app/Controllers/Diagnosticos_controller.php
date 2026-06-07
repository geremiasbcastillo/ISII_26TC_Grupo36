<?php

namespace App\Controllers;

use App\Models\Diagnosticos_model;
use App\Models\Equipos_model;

class Diagnosticos_controller extends BaseController
{
    public function index()
    {
        $equipoModel = new Equipos_model();
        
        $data['equipos'] = $equipoModel->select(
                'equipo.id_equipo, equipo.nroSerie, tipo_equipo.nombre as tipo_nombre, modelo_equipo.nombre as modelo_nombre, marca.nombre as marca_nombre'
            )
            ->join('tipo_equipo', 'tipo_equipo.id_tipo = equipo.id_tipo')
            ->join('modelo_equipo', 'modelo_equipo.id_modelo = equipo.id_modelo')
            ->join('marca', 'marca.id_marca = modelo_equipo.id_marca')
            ->where('equipo.equipo_estado', 1)
            ->findAll();

        $data['titulo'] = 'Diagnóstico';
        return view('plantillas/nav_view', $data) . view('frontend/diagnostico_view', $data) . view('plantillas/footer_view', $data);
    }

    public function guardarDiagnostico()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'id_equipo'   => 'required|numeric',
                'diagnostico' => 'required|min_length[10]'
            ],
            [
                'id_equipo' => [
                    'required' => 'Debes seleccionar un equipo registrado.',
                    'numeric'  => 'El equipo seleccionado no es válido.'
                ],
                'diagnostico' => [
                    'required'   => 'El diagnóstico es obligatorio.',
                    'min_length' => 'El diagnóstico debe tener al menos 10 caracteres.'
                ]
            ]
        );

        if (!$validation->withRequest($request)->run()) {
            $equipoModel = new Equipos_model();

            $data['equipos'] = $equipoModel->select(
                    'equipo.id_equipo, equipo.nroSerie, tipo_equipo.nombre as tipo_nombre, modelo_equipo.nombre as modelo_nombre, marca.nombre as marca_nombre'
                )
                ->join('tipo_equipo', 'tipo_equipo.id_tipo = equipo.id_tipo')
                ->join('modelo_equipo', 'modelo_equipo.id_modelo = equipo.id_modelo')
                ->join('marca', 'marca.id_marca = modelo_equipo.id_marca')
                ->where('equipo.equipo_estado', 1)
                ->findAll();

            $data['titulo'] = 'Diagnóstico';
            $data['validation'] = $validation->getErrors();

            return view('plantillas/nav_view', $data) . view('frontend/diagnostico_view', $data) . view('plantillas/footer_view', $data);
        }

        $id_equipo = $request->getPost('id_equipo');
        $diagnostico = $request->getPost('diagnostico');

        $equipoModel = new Equipos_model();
        $equipo = $equipoModel->where('id_equipo', $id_equipo)->where('equipo_estado', 1)->first();

        if (!$equipo) {
            return redirect()->back()->withInput()->with('mensaje_error', 'Equipo no encontrado o ya no está activo.');
        }

        $diagnosticoModel = new Diagnosticos_model();
        $diagnosticoData = [
            'id_equipo'   => $id_equipo,
            'diagnostico' => $diagnostico,
        ];

        if ($diagnosticoModel->insert($diagnosticoData)) {
            return redirect()->route('diagnostico')->with('mensaje_success', 'Diagnóstico guardado correctamente.');
        }

        return redirect()->back()->withInput()->with('mensaje_error', 'Ocurrió un error al guardar el diagnóstico.');
    }
}
