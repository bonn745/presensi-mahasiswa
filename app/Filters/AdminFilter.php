<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('pesan', 'Anda Belum Login');
            return redirect()->to('/');
        }

        if (session()->get('role_id') != 'admin') { // Perbaikan dari ≠ menjadi !=
            session()->setFlashdata('pesan', 'Anda tidak memiliki Akses');
            return redirect()->to('/'); // Perbaikan dari redirect()to('/') menjadi redirect()->to('/')
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
