<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\MahasiswaModel;
use App\Models\PresensiModel;
use App\Models\KetidakhadiranModel;
use App\Models\MatkulModel;
use CodeIgniter\Debug\Toolbar\Collectors\Logs;
use Exception;

class Home extends BaseController
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');

        $mahasiswa_model = new MahasiswaModel();
        $presensi_model = new PresensiModel();

        $data_matakuliah = $this->getMataKuliah();


        // Ambil tanggal dari input (GET) atau gunakan hari ini sebagai default
        $tanggal = $this->request->getGet('tanggal');
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        // Mengambil daftar mahasiswa yang hadir hari ini (unik berdasarkan id_mahasiswa)
        $db = \Config\Database::connect();
        $builder = $db->table('presensi');
        $mahasiswa_hadir = $builder->select('id_mahasiswa')
            ->where('tanggal', $tanggal)
            ->distinct()
            ->get()
            ->getResultArray();

        // Debug info
        log_message('debug', 'Jumlah mahasiswa hadir: ' . count($mahasiswa_hadir));

        // Hitung total mahasiswa
        $total_mahasiswa = $mahasiswa_model->countAllResults();

        // Hitung mahasiswa hadir (berdasarkan id unik)
        $total_hadir = count($mahasiswa_hadir);

        // Debug info
        log_message('debug', 'Total mahasiswa: ' . $total_mahasiswa);
        log_message('debug', 'Total hadir: ' . $total_hadir);

        $data = [
            'title' => 'Dashboard',
            'tanggal_filter' => $tanggal,
            'total_mahasiswa' => $total_mahasiswa,
            'total_hadir' => $total_hadir,
            'data_matakuliah' => $data_matakuliah,
        ];

        return view('dosen/home', $data);
    }

    public function ketidakhadiran()
    {
        $matkulModel = new MatkulModel();

        $matkulIds = $matkulModel->select('id')->where('dosen_pengampu', session()->get('id_dosen'))->findAll();

        $ids = [];

        foreach ($matkulIds as $matkul) {
            $ids[] = $matkul['id'];
        }

        $ketidakhadiranModel = new KetidakhadiranModel();

        $ketidakhadiran = $ketidakhadiranModel->select('
                                        ketidakhadiran.id,
                                        id_mahasiswa,
                                        keterangan,
                                        tanggal,
                                        deskripsi,
                                        file,
                                        status_pengajuan,
                                        id_matkul,
                                        mahasiswa.nama,
                                        matkul.matkul
            ')
            ->whereIn('id_matkul', $ids)
            ->join('matkul', 'matkul.id = ketidakhadiran.id_matkul')
            ->join('mahasiswa', 'mahasiswa.id = ketidakhadiran.id_mahasiswa')
            ->orderBy('id', 'DESC')
            ->findAll();


        $data = [
            'title' => 'Ketidakhadiran',
            'ketidakhadiran' => $ketidakhadiran
        ];

        return view('dosen/ketidakhadiran', $data);
    }

    public function terimaKetidakhadiran($id)
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $ketidakhadiranModel->update($id, ['status_pengajuan' => 'Accept']);
        return redirect()->back();
    }

    public function tolakKetidakhadiran($id)
    {
        $ketidakhadiranModel = new KetidakhadiranModel();
        $ketidakhadiranModel->update($id, ['status_pengajuan' => 'Reject']);
        return redirect()->back();
    }

    private function getMataKuliah()
    {
        $matkulModel = new MatkulModel();
        return $matkulModel->select('matkul.matkul, kelas.hari, kelas.jam_masuk, kelas.jam_pulang, kelas.jenis_kelas, kelas.id as id_kelas')
            ->where('dosen_pengampu', session('id_dosen'))
            ->join('kelas', 'kelas.id_matkul = matkul.id')
            ->findAll();
    }

    public function updateJenisKelas()
    {
        $id = $this->request->getPost('id');
        $jenis = $this->request->getPost('jenis');
        $kelasModel = new KelasModel();
        try {
            $kelasModel->update($id, ['jenis_kelas' => $jenis]);
        } catch (Exception $e) {
            log_message('error', $e);
            return response()->setStatusCode(400)->setJSON(array(
                'message' => 'Permintaan tidak dapat diproses.'
            ));
        }

        return response()->setStatusCode(200)->setJSON(array(
            'message' => 'Jenis kelas berhasil diperbarui.'
        ));
    }
}
