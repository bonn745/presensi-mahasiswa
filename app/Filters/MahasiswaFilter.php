<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MahasiswaFilter implements FilterInterface
{
    private $routeToCheck = 'mahasiswa/lengkapi/data';
    private $routeStoreToCheck = 'mahasiswa/lengkapi/store';

    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('pesan', 'Anda Belum Login');
            return redirect()->to('/');
        }

        if (session()->get('role_id') != 'mahasiswa') { // Perbaikan dari ≠ menjadi !=
            session()->setFlashdata('pesan', 'Anda tidak memiliki akses');
            return redirect()->to('/'); // Perbaikan dari redirect()to('/') menjadi redirect()->to('/')
        }

        if (session()->get('lengkapi_data') && !str_contains(uri_string(), $this->routeToCheck) && !str_contains(uri_string(), $this->routeStoreToCheck)) {
            session()->setFlashdata('pesan', 'Silakan lengkapi data untuk melanjutkan.');
            return redirect()->route('mahasiswa.lengkapi.data', [session()->get('id_mahasiswa')]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
