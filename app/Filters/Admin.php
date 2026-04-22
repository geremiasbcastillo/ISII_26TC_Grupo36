<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Admin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session('login') || session('rol') != 1) {
            return redirect()->to(base_url('inicio'));
        }
        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}