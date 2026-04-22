<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Admin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Si no está logueado o su rol NO es 1 (Admin), lo pateamos al inicio
        if (!session('login') || session('rol') != 1) {
            return redirect()->to(base_url('inicio'));
        }
        
        // Si es Admin, no hacemos nada, dejamos que pase.
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}