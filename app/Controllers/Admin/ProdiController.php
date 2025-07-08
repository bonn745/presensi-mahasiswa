<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DosenModel;
use App\Models\MatkulModel;
use App\Models\ProdiModel;

class ProdiController extends BaseController
{
    public function index()
    {
        $prodiModel = new ProdiModel();

        $data = [
            'title' => 'Daftar Program Studi',
            'prodi' => $prodiModel->select('prodi.id, prodi.nama')->findAll()
        ];

        return view('admin/prodi/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Program Studi',
            'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
        ];
        return view('admin/prodi/create', $data);
    }

    public function store()
    {
        $rules = [
            'prodi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Program Studi Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', array_values($this->validator->getErrors())[0]);
        }


        $data = array(
            'nama' => $this->request->getPost('prodi'),
        );

        $prodiModel = new ProdiModel();
        $prodiModel->insert($data);

        return redirect()->to('/admin/prodi')->with('success', 'Program studi berhasil ditambahkan.'); // Redirect setelah menyimpan
    }

    public function edit($id)
    {
        $prodiModel = new ProdiModel();

        $prodi = $prodiModel->find($id);
        if (!$prodi) {
            return redirect()->to('/admin/prodi')->with('error', 'Program Studi tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Program Studi',
            'prodi' => $prodi,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/prodi/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'prodi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Program Studi Wajib Diisi'
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/prodi/edit/' . $id)->withInput()->with('validation', \Config\Services::validation());
        }

        $prodiModel = new ProdiModel();

        $data = [
            'nama' => $this->request->getPost('prodi'),
        ];

        $prodiModel->update($id, $data);

        return redirect()->to('/admin/prodi')->with('success', 'Program studi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $prodiModel = new ProdiModel();

        $prodi = $prodiModel->find($id);
        if (!$prodi) {
            return redirect()->to('/admin/prodi')->with('error', 'Program studi tidak ditemukan.');
        }

        $prodiModel->delete($id);

        return redirect()->to('/admin/prodi')->with('success', 'Program studi berhasil dihapus.');
    }
}
