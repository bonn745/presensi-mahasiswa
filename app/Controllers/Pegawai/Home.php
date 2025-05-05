<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LokasiPresensiMOdel;
use App\Models\PegawaiModel;
use App\Models\PresensiModel;

class Home extends BaseController
{
    public function index()
    {
        $lokasi_presensi = new LokasiPresensiModel();
        $pegawai_model = new PegawaiModel();
        $presensi_model = new PresensiModel();

        $id_pegawai = session()->get('id_pegawai');
        $pegawai = $pegawai_model->where('id', $id_pegawai)->first();

        $ambil_presensi_masuk = $presensi_model
            ->where('id_pegawai', $id_pegawai)
            ->where('tanggal_masuk', date('Y-m-d'))
            ->first();

        $ambil_presensi_keluar = null;
        if ($ambil_presensi_masuk) {
            $ambil_presensi_keluar = $presensi_model
                ->where('id', $ambil_presensi_masuk['id'])
                ->where('jam_keluar IS NOT NULL') // 🔹 Hanya ambil jika sudah presensi keluar
                ->first();
        }

        $data = [
            'title' => 'home',
            'lokasi_presensi' => $lokasi_presensi->where('id', $pegawai['lokasi_presensi'])->first(),
            'cek_presensi' => $presensi_model->where('id_pegawai', $id_pegawai)->where('tanggal_masuk', date('Y-m-d'))->countAllResults(),
            'ambil_presensi_masuk' => $ambil_presensi_masuk, // 🔹 Variabel sudah dipastikan ada
            'ambil_presensi_keluar' => $ambil_presensi_keluar,
        ];


        return view('pegawai/home', $data);
    }

    public function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $radiusBumi = 6371000; // Radius bumi dalam meter

        // Konversi derajat ke radian
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Haversine formula
        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $jarak_meter = $radiusBumi * $c; // Jarak dalam meter

        return floor($jarak_meter); // Bulatkan ke bawah
    }

    public function presensi_masuk()
    {
        $latitude_pegawai = (float) $this->request->getPost('latitude_pegawai');
        $longitude_pegawai = (float) $this->request->getPost('longitude_pegawai');
        $latitude_kantor = (float) $this->request->getPost('latitude_kantor');
        $longitude_kantor = (float) $this->request->getPost('longitude_kantor');
        $radius = (int) $this->request->getPost('radius'); // Radius dalam meter

        // Cek apakah variabel berisi nilai
        if (!$latitude_pegawai || !$longitude_pegawai || !$latitude_kantor || !$longitude_kantor || !$radius) {
            session()->setFlashdata('gagal', 'Data lokasi tidak lengkap.');
            return redirect()->to(base_url('pegawai/home'));
        }

        // Hitung jarak
        $jarak_meter = $this->hitungJarak($latitude_pegawai, $longitude_pegawai, $latitude_kantor, $longitude_kantor);

        if ($jarak_meter > $radius) {
            session()->setFlashdata('gagal', 'Presensi gagal, lokasi Anda berada di luar radius kantor.');
            return redirect()->to(base_url('pegawai/home'));
        } else {
            $data = [
                'title' => "Ambil Foto Selfie",
                'id_pegawai' => $this->request->getPost('id_pegawai'),
                'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
                'jam_masuk' => $this->request->getPost('jam_masuk'),
            ];
            return view('pegawai/ambil_foto', $data);
        }
    }

    public function presensi_masuk_aksi()
    {
        $request = service('request');

        $id_pegawai = $request->getPost('id_pegawai');
        $tanggal_masuk = $request->getPost('tanggal_masuk');
        $jam_masuk = $request->getPost('jam_masuk');

        // Simpan Foto di Folder
        if ($file = $request->getFile('foto_masuk')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $namaFile = 'absensi_' . $id_pegawai . '_' . time() . '.jpg';
                $file->move(ROOTPATH . 'public/uploads/absensi', $namaFile);
                $fotoPath = 'uploads/absensi/' . $namaFile;
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Foto tidak valid!']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Foto tidak ditemukan!']);
        }

        // Simpan Data ke Database
        $presensiModel = new PresensiModel();
        $presensiModel->insert([
            'id_pegawai'    => $id_pegawai,
            'tanggal_masuk' => $tanggal_masuk,
            'jam_masuk'     => $jam_masuk,
            'foto_masuk'    => $namaFile,
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function presensi_keluar($id)
    {
        $latitude_pegawai = (float) $this->request->getPost('latitude_pegawai');
        $longitude_pegawai = (float) $this->request->getPost('longitude_pegawai');
        $latitude_kantor = (float) $this->request->getPost('latitude_kantor');
        $longitude_kantor = (float) $this->request->getPost('longitude_kantor');
        $radius = (int) $this->request->getPost('radius'); // Radius dalam meter

        // Cek apakah variabel berisi nilai
        if (!$latitude_pegawai || !$longitude_pegawai || !$latitude_kantor || !$longitude_kantor || !$radius) {
            session()->setFlashdata('gagal', 'Data lokasi tidak lengkap.');
            return redirect()->to(base_url('pegawai/home'));
        }

        // Hitung jarak
        $jarak_meter = $this->hitungJarak($latitude_pegawai, $longitude_pegawai, $latitude_kantor, $longitude_kantor);

        if ($jarak_meter > $radius) {
            session()->setFlashdata('gagal', 'Presensi gagal, lokasi Anda berada di luar radius kantor.');
            return redirect()->to(base_url('pegawai/home'));
        } else {
            $data = [
                'title' => "Ambil Foto Selfie",
                'id_presensi' => $id,
                'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
                'jam_keluar' => $this->request->getPost('jam_keluar'),
            ];
            return view('pegawai/ambil_foto_keluar', $data);
        }
    }

    public function presensi_keluar_aksi($id)
    {
        $request = service('request');


        $tanggal_keluar = $request->getPost('tanggal_keluar');
        $jam_keluar = $request->getPost('jam_keluar');

        // Simpan Foto di Folder
        if ($file = $request->getFile('foto_keluar')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $namaFile = 'absensi_' . $id . '_' . time() . '.jpg';
                $file->move(ROOTPATH . 'public/uploads/absensi', $namaFile);
                $fotoPath = 'uploads/absensi/' . $namaFile;
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Foto tidak valid!']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Foto tidak ditemukan!']);
        }

        // Simpan Data ke Database
        $presensiModel = new PresensiModel();
        $presensiModel->update($id, [
            'tanggal_keluar' => $tanggal_keluar,
            'jam_keluar'     => $jam_keluar,
            'foto_keluar'    => $namaFile,
        ]);

        return $this->response->setJSON(['success' => true]);
    }
}
