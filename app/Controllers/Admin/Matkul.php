<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MatkulModel; // Pastikan penamaan namespace benar

class Matkul extends BaseController
{
    public function index()
    {
        $matkulModel = new MatkulModel(); // Gunakan huruf kapital untuk nama kelas
        $data = [
            'title' => 'Daftar Matkul',
            'matkul' => $matkulModel->findAll() // Mengambil semua data Matkul
        ];
        return view('admin/matkul/matkul', $data); // Pastikan view ini ada
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Matkul',
            'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
        ];
        return view('admin/matkul/create', $data);
    }

    public function store()
    {
        $rules = [
            'matkul' => [ // Pastikan nama field sesuai dengan input form
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Matkul Wajib Diisi"
                ],
            ],
            'dosen_pengampu' => [ // Pastikan nama field sesuai dengan input form
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Dosen Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Tambah Matkul',
                'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
            ];
            return view('admin/matkul/create', $data);
        }

        // Jika validasi berhasil, simpan data matkul
        $matkulModel = new MatkulModel();
        $matkulModel->save([
            'matkul' => $this->request->getPost('matkul'),
            'dosen_pengampu' => $this->request->getPost('dosen_pengampu') // Ambil data dari input form
        ]);


        return redirect()->to('/admin/matkul')->with('success', 'Matkul berhasil ditambahkan.'); // Redirect setelah menyimpan
    }

    public function edit($id) // Tambahkan $id sebagai parameter
    {
        $matkulModel = new MatkulModel();

        // Cek apakah Matkul dengan ID yang diberikan ada
        $matkul = $matkulModel->find($id);
        if (!$matkul) {
            // Jika tidak ada, redirect atau tampilkan pesan error
            return redirect()->to('/admin/matkul')->with('error', 'Matkul tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Matkul',
            'matkul' => $matkul,
            'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
        ];

        return view('admin/matkul/edit', $data);
    }

    public function update($id)
    {
        $matkulModel = new MatkulModel();

        // Validasi input
        $rules = [
            'matkul' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Matkul Wajib Diisi'
                ],
            ],
            'dosen_pengampu' => [ // Pastikan nama field sesuai dengan input form
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Dosen Wajib Diisi"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, ambil data matkul dan tampilkan kembali form edit
            return redirect()->to('/admin/matkul/edit/' . $id)->withInput()->with('validation', \Config\Services::validation());
        }

        // Ambil data dari input
        $data = [
            'matkul' => $this->request->getPost('matkul'),
            'dosen_pengampu' => $this->request->getPost('dosen_pengampu')
        ];

        // Update data matkul
        $matkulModel->update($id, $data);

        // Redirect setelah berhasil memperbarui
        return redirect()->to('/admin/matkul')->with('success', 'Matkul berhasil diperbarui.');
    }

    public function delete($id)
    {
        $matkulModel = new MatkulModel();

        // Cek apakah Matkul dengan ID yang diberikan ada
        $matkul = $matkulModel->find($id);
        if (!$matkul) {
            return redirect()->to('/admin/matkul')->with('error', 'Matkul tidak ditemukan.');
        }

        // Hapus Matkul
        $matkulModel->delete($id);

        // Redirect dengan pesan sukses khusus untuk penghapusan
        return redirect()->to('/admin/matkul')->with('success', 'Matkul berhasil dihapus.');
    }
}
