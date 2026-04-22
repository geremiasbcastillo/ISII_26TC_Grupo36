<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Guest implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session('login')) {
            if (session('rol') == 1) {
                return redirect()->to(base_url('principal'));
            } elseif (session('rol') == 2) {
                return redirect()->to(base_url('tecnico'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}