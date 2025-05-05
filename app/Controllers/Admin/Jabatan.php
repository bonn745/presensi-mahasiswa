<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JabatanModel; // Pastikan penamaan namespace benar

class Jabatan extends BaseController
{
    public function index()
    {
        $jabatanModel = new JabatanModel(); // Gunakan huruf kapital untuk nama kelas
        $data = [
            'title' => 'Daftar Jabatan',
            'jabatan' => $jabatanModel->findAll() // Mengambil semua data jabatan
        ];
        return view('admin/jabatan/jabatan', $data); // Pastikan view ini ada
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Jabatan',
            'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
        ];
        return view('admin/jabatan/create', $data);
    }

    public function store()
    {
        $rules = [
            'jabatan' => [ // Pastikan nama field sesuai dengan input form
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Jabatan Wajib Diisi"
                ],
            ]
        ];

        if (!$this->validate($rules)) {
            $data = [
                'title' => 'Tambah Jabatan',
                'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
            ];
            return view('admin/jabatan/create', $data);
        }

        // Jika validasi berhasil, simpan data jabatan
        $jabatanModel = new JabatanModel();
        $jabatanModel->save([
            'jabatan' => $this->request->getPost('jabatan') // Ambil data dari input form
        ]);


        return redirect()->to('/admin/jabatan')->with('success', 'Jabatan berhasil ditambahkan.'); // Redirect setelah menyimpan
    }

    public function edit($id) // Tambahkan $id sebagai parameter
    {
        $jabatanModel = new JabatanModel();

        // Cek apakah jabatan dengan ID yang diberikan ada
        $jabatan = $jabatanModel->find($id);
        if (!$jabatan) {
            // Jika tidak ada, redirect atau tampilkan pesan error
            return redirect()->to('/admin/jabatan')->with('error', 'Jabatan tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Jabatan',
            'jabatan' => $jabatan,
            'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
        ];

        return view('admin/jabatan/edit', $data);
    }

    public function update($id)
    {
        $jabatanModel = new JabatanModel();

        // Validasi input
        $rules = [
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Jabatan Wajib Diisi'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, ambil data jabatan dan tampilkan kembali form edit
            return redirect()->to('/admin/jabatan/edit/' . $id)->withInput()->with('validation', \Config\Services::validation());
        }

        // Ambil data dari input
        $data = [
            'jabatan' => $this->request->getPost('jabatan')
        ];

        // Update data jabatan
        $jabatanModel->update($id, $data);

        // Redirect setelah berhasil memperbarui
        return redirect()->to('/admin/jabatan')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $jabatanModel = new JabatanModel();

        // Cek apakah jabatan dengan ID yang diberikan ada
        $jabatan = $jabatanModel->find($id);
        if (!$jabatan) {
            return redirect()->to('/admin/jabatan')->with('error', 'Jabatan tidak ditemukan.');
        }

        // Hapus jabatan
        $jabatanModel->delete($id);

        // Redirect dengan pesan sukses khusus untuk penghapusan
        return redirect()->to('/admin/jabatan')->with('success', 'Jabatan berhasil dihapus.');
    }
}
