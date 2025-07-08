<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\MatkulModel; // Pastikan penamaan namespace benar
use App\Models\ProdiModel;

class Matkul extends BaseController
{
    public function index()
    {
        $matkulModel = new MatkulModel(); // Gunakan huruf kapital untuk nama kelas

        $data = [
            'title' => 'Daftar Mata Kuliah',
            'matkul' => $matkulModel->select('matkul.id, matkul.matkul, dosen.nama_dosen, prodi.nama as nama_prodi')
                ->join('dosen', 'dosen.id = matkul.dosen_pengampu')
                ->join('prodi', 'prodi.id = matkul.prodi_id')
                ->findAll() // Mengambil semua data Matkul
        ];

        return view('admin/matkul/matkul', $data); // Pastikan view ini ada
    }

    public function create()
    {
        $dosen = new DosenModel();
        $prodi = new ProdiModel();
        $data = [
            'title' => 'Tambah Mata Kuliah',
            'dosen' => $dosen->findAll(),
            'prodi' => $prodi->findAll(),
            'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
        ];
        return view('admin/matkul/create', $data);
    }

    public function store()
    {
        $rules = [
            'prodi' => [ // Pastikan nama field sesuai dengan input form
                'rules' => 'required',
                'errors' => [
                    'required' => "Program Studi Wajib Dipilih"
                ],
            ],
            'matkul' => [ // Pastikan nama field sesuai dengan input form
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Matkul Wajib Diisi"
                ],
            ],
            'dosen_pengampu' => [ // Pastikan nama field sesuai dengan input form
                'rules' => 'required',
                'errors' => [
                    'required' => "Dosen Wajib Dipilih"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', array_values($this->validator->getErrors())[0]);
        }

        $data = array(
            'prodi_id' => $this->request->getPost('prodi'),
            'matkul' => $this->request->getPost('matkul'),
            'dosen_pengampu' => $this->request->getPost('dosen_pengampu'),
        );

        $matkulModel = new MatkulModel();
        $matkulModel->insert($data);

        return redirect()->to('/admin/matkul')->with('success', 'Mata kuliah berhasil ditambahkan.'); // Redirect setelah menyimpan
    }

    public function edit($id) // Tambahkan $id sebagai parameter
    {
        $matkulModel = new MatkulModel();
        $dosenModel = new DosenModel();
        $prodiModel = new ProdiModel();

        // Cek apakah Matkul dengan ID yang diberikan ada
        $matkul = $matkulModel->find($id);
        if (!$matkul) {
            // Jika tidak ada, redirect atau tampilkan pesan error
            return redirect()->to('/admin/matkul')->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Mata Kuliah',
            'dosen' => $dosenModel->findAll(),
            'prodi' => $prodiModel->findAll(),
            'matkul' => $matkulModel->select('matkul.id, matkul.matkul, dosen.id as dosen_pengampu, prodi.id as prodi_id')
                ->join('dosen', 'dosen.id = matkul.dosen_pengampu')
                ->join('prodi', 'prodi.id = matkul.prodi_id')
                ->find($id),
            'validation' => \Config\Services::validation() // Pastikan ini menggunakan huruf kapital
        ];

        return view('admin/matkul/edit', $data);
    }

    public function update($id)
    {

        // Validasi input
        $rules = [
            'prodi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Program Studi Wajib Dipilih'
                ],
            ],
            'matkul' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Matkul Wajib Diisi'
                ],
            ],
            'dosen_pengampu' => [ // Pastikan nama field sesuai dengan input form
                'rules' => 'required',
                'errors' => [
                    'required' => "Dosen Pengampu Wajib Dipilih"
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, ambil data matkul dan tampilkan kembali form edit
            return redirect()->to('/admin/matkul/edit/' . $id)->withInput()->with('validation', \Config\Services::validation());
        }

        $matkulModel = new MatkulModel();

        $data = [
            'prodi_id' => $this->request->getPost('prodi'),
            'matkul' => $this->request->getPost('matkul'),
            'dosen_pengampu' => $this->request->getPost('dosen_pengampu')
        ];

        // Update data matkul
        $matkulModel->update($id, $data);

        // Redirect setelah berhasil memperbarui
        return redirect()->to('/admin/matkul')->with('success', 'Mata kuliah berhasil diperbarui.');
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
