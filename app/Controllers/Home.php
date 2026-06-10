<?php
namespace App\Controllers;

use App\Models\Categorias_Model;

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

    public function repuestos()
    {
        $data['titulo'] = 'Verificación de Stock';
        return view('plantillas/nav_view', $data) . view('frontend/repuestos_view', $data) . view('plantillas/footer_view', $data);
    }
    
    /**
     * Muestra el formulario para registrar nuevos repuestos.
     * 
     * @return void 
     */
    public function registrar_repuestos()
    {
        $categoriaModel = new \App\Models\Categorias_Model();
        $data['categorias'] = $categoriaModel->findAll();
        $data['titulo'] = 'Verificación de Stock';
        return view('plantillas/nav_view', $data) . view('frontend/registrarRep_view', $data) . view('plantillas/footer_view', $data);
    }

    /**
     * Muestra una vista con el stock actual de repuestos.
     * 
     * @return void 
     */
    public function stock_repuestos()
    {
        $repuestoModel = new \App\Models\Repuestos_Model();
        $categoriaModel = new \App\Models\Categorias_Model();

        $repuestos = $repuestoModel->select('repuesto.*, categoria_repuesto.nombre AS categoria_nombre')
                               ->join('categoria_repuesto', 'categoria_repuesto.id_categoria_repuesto = repuesto.id_categoria_repuesto')
                               ->findAll();
        
        $data['repuestos'] = $repuestos;
        $data['categorias'] = $categoriaModel->findAll();

        $data['titulo'] = 'Stock Actual de Repuestos';
        return view('plantillas/nav_view', $data) . view('frontend/stockRepuestos_view', $data) . view('plantillas/footer_view', $data);
    }

    /**
     * Muestra una vista con los repuestos a actualizar.
     * 
     * @return void 
     */
    public function actualizar_repuestos()
    {
        $repuestoModel = new \App\Models\Repuestos_Model();
        $categoriaModel = new \App\Models\Categorias_Model();
        $data['repuestos'] = $repuestoModel->findAll();
        $data['categorias'] = $categoriaModel->findAll();

        $data['titulo'] = 'Actualizar Repuestos';
        return view('plantillas/nav_view', $data) . view('frontend/actualizarRepuestos_view', $data) . view('plantillas/footer_view', $data);
    }
}
