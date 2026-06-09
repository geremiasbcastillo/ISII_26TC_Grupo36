<?php

namespace App\Controllers;

use App\Models\Diagnosticos_model;
use App\Models\Equipos_model;

class Diagnosticos_controller extends BaseController
{
    public function formularioDiagnostico()
    {
        $equipoModel = new Equipos_model();
        $diagnosticosModel = new Diagnosticos_model();
        
        // Obtener los IDs de equipos que ya tienen un diagnóstico
        $equiposDiagnosticados = $diagnosticosModel->distinct()->select('id_equipo')->findAll();
        $equiposDiagnosticadosIds = array_column($equiposDiagnosticados, 'id_equipo');

        $query = $equipoModel->select(
                'equipo.id_equipo, equipo.nroSerie, tipo_equipo.nombre as tipo_nombre, modelo_equipo.nombre as modelo_nombre, marca.nombre as marca_nombre'
            )
            ->join('tipo_equipo', 'tipo_equipo.id_tipo = equipo.id_tipo')
            ->join('modelo_equipo', 'modelo_equipo.id_modelo = equipo.id_modelo')
            ->join('marca', 'marca.id_marca = modelo_equipo.id_marca')
            ->where('equipo.equipo_estado', 1);

        if (!empty($equiposDiagnosticadosIds)) {
            $query->whereNotIn('equipo.id_equipo', $equiposDiagnosticadosIds);
        }

        $data['equipos'] = $query->findAll();

        $data['titulo'] = 'Diagnóstico';
        return view('plantillas/nav_view', $data) . view('frontend/diagnostico_view', $data) . view('plantillas/footer_view', $data);
    }

    public function guardarDiagnostico()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'id_equipo'      => 'required|numeric',
                'analisis'       => 'required|min_length[10]',
                'solucion'       => 'permit_empty|min_length[5]',
                'costo_estimado' => 'permit_empty|numeric'
            ],
            [
                'id_equipo' => [
                    'required' => 'Debes seleccionar un equipo registrado.',
                    'numeric'  => 'El equipo seleccionado no es válido.'
                ],
                'analisis' => [
                    'required'   => 'El análisis es obligatorio.',
                    'min_length' => 'El análisis debe tener al menos 10 caracteres.'
                ],
                'solucion' => [
                    'min_length' => 'La solución debe tener al menos 5 caracteres.'
                ],
                'costo_estimado' => [
                    'numeric'  => 'El costo estimado debe ser un número válido.'
                ]
            ]
        );

        if (!$validation->withRequest($request)->run()) {
            return redirect()->back()->withInput()->with('validation', $validation->getErrors());
        }

        $id_equipo = $request->getPost('id_equipo');
        $analisis = $request->getPost('analisis');
        $solucion = $request->getPost('solucion');
        $costo_estimado = $request->getPost('costo_estimado');

        $equipoModel = new Equipos_model();
        $equipo = $equipoModel->where('id_equipo', $id_equipo)->where('equipo_estado', 1)->first();

        if (!$equipo) {
            return redirect()->back()->withInput()->with('mensaje_error', 'Equipo no encontrado o ya no está activo.');
        }

        $diagnosticoModel = new Diagnosticos_model();
        $diagnosticoData = [
            'id_equipo'        => $id_equipo,
            'analisis'         => $analisis,
            'solucion'         => empty($solucion) ? null : $solucion,
            'costo_estimado'   => empty($costo_estimado) ? null : $costo_estimado,
            'id_usuario'       => session()->get('id'),
            'fechaDiagnostico' => date('Y-m-d')
        ];

        if ($diagnosticoModel->insert($diagnosticoData)) {
            return redirect()->to(base_url('diagnostico'))->with('mensaje_success', 'Diagnóstico guardado correctamente.');
        }

        return redirect()->back()->withInput()->with('mensaje_error', 'Ocurrió un error al guardar el diagnóstico.');
    }
}
