<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Tecnico implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Si no está logueado o su rol NO es 2 (Técnico), lo pateamos al inicio
        if (!session('login') || session('rol') != 2) {
            return redirect()->to(base_url('inicio'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}