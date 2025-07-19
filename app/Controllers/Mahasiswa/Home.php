<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LokasiPresensiMOdel;
use App\Models\MahasiswaModel;
use App\Models\PresensiModel;
use App\Models\KelasModel;
use App\Models\MatkulMahasiswa;
use App\Models\MatkulModel;
use Carbon\Carbon;

class Home extends BaseController
{
    public function index()
    {
        $matkul_mhs = new MatkulMahasiswa();
        $matkulModel = new MatkulModel();
        $mahasiswaModel = new MahasiswaModel();
        $id_mhs = session()->get('id_mahasiswa');
        $matkul = $matkul_mhs->where('mhs_id', $id_mhs)->findAll();
        $mhs = $mahasiswaModel->find($id_mhs);

        $data = [
            'title' => 'Pilih Mata Kuliah',
            'matkul' => $matkulModel->where('prodi_id', $mhs['prodi'])->findAll(),
            'info' => 'Anda belum memilih Mata Kuliah, silakan <strong>Pilih</strong> dan <strong>Tambahkan</strong> Mata Kuliah untuk melanjutkan.',
            'validation' => \Config\Services::validation(),
        ];

        if (empty($matkul)) return view('Mahasiswa/pilih_matkul', $data);

        $matkulIds = [];

        foreach ($matkul as $mtkl) {
            $matkulIds[] = $mtkl['matkul_id'];
        }

        date_default_timezone_set('Asia/Jakarta'); // Pastikan zona waktu sesuai

        $lokasi_presensi = new LokasiPresensiModel();
        $mahasiswa_model = new MahasiswaModel();
        $presensi_model = new PresensiModel();
        $kelas_model = new KelasModel();
        $matkul_model = new MatkulModel();

        $id_mahasiswa = session()->get('id_mahasiswa');
        $mahasiswa = $mahasiswa_model->where('id', $id_mahasiswa)->first();

        // Dapatkan hari ini dalam bahasa Indonesia
        $hari_en = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $hari_id = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hari_ini = date('l');
        $hari_ini = str_replace($hari_en, $hari_id, $hari_ini);

        // Ambil semua data kelas dengan join ke matkul untuk mendapatkan dosen pengampu
        $kelas_list = $kelas_model->select('
            kelas.*, 
            matkul.matkul as nama_matkul,
            matkul.dosen_pengampu,
            dosen.nama_dosen,
            lokasi_presensi.nama_ruangan,
            lokasi_presensi.tipe_lokasi,
            lokasi_presensi.alamat_lokasi,
        ')
            ->join('matkul', 'matkul.id = kelas.id_matkul')
            ->join('dosen', 'dosen.id = matkul.dosen_pengampu')
            ->join('lokasi_presensi', 'lokasi_presensi.id = kelas.ruangan')
            ->whereIn('id_matkul', $matkulIds)
            ->orderBy('kelas.jam_masuk', 'ASC')
            ->findAll();

        // Ambil presensi masuk hari ini dengan data lokasi lengkap
        $ambil_presensi_masuk = $presensi_model
            ->select('presensi.*, lokasi_presensi.nama_ruangan, lokasi_presensi.latitude, lokasi_presensi.longitude, lokasi_presensi.radius, kelas.jenis_kelas')
            ->join('lokasi_presensi', 'lokasi_presensi.id = presensi.id_lokasi_presensi', 'left')
            ->join('kelas', 'kelas.id_matkul = presensi.id_matkul')
            ->where('presensi.id_mahasiswa', $id_mahasiswa)
            ->where('presensi.tanggal', date('Y-m-d'))
            ->where('kelas.hari', Carbon::createFromFormat('Y-m-d',date('Y-m-d'))->locale('id')->translatedFormat('l'))
            ->orderBy('presensi.jam_masuk', 'DESC')
            ->first();

        // Debug log untuk presensi masuk
        log_message('debug', 'Data presensi masuk: ' . json_encode($ambil_presensi_masuk));

        // Ambil presensi keluar hari ini
        $ambil_presensi_keluar = null;
        if ($ambil_presensi_masuk) {
            $ambil_presensi_keluar = $presensi_model
                ->where('id', $ambil_presensi_masuk['id'])
                ->where('jam_keluar IS NOT NULL')
                ->where('jam_keluar !=', '00:00:00')
                ->first();
        }

        // Debug log untuk presensi keluar
        log_message('debug', 'Data presensi keluar: ' . json_encode($ambil_presensi_keluar));

        // Ambil semua presensi hari ini untuk mahasiswa ini
        $presensi_hari_ini = $presensi_model
            ->where('id_mahasiswa', $id_mahasiswa)
            ->where('tanggal', date('Y-m-d'))
            ->orderBy('jam_masuk', 'DESC')
            ->findAll();

        // Debug log untuk presensi hari ini
        log_message('debug', 'Data presensi hari ini: ' . json_encode($presensi_hari_ini));

        // Hitung jumlah presensi hari ini berdasarkan mata kuliah yang sedang berlangsung
        $current_time = strtotime(date('H:i:s'));
        $cek_presensi = 0;
        $matkul_sudah_presensi = [];

        // Kumpulkan id_matkul yang sudah dipresensi
        foreach ($presensi_hari_ini as $presensi) {
            $matkul_sudah_presensi[] = $presensi['id_matkul'];
        }

        // Cek apakah sudah presensi untuk mata kuliah yang sedang berlangsung
        foreach ($kelas_list as $kelas) {
            if ($kelas['hari'] === $hari_ini) {
                $jam_masuk = strtotime($kelas['jam_masuk']);
                $jam_pulang = strtotime($kelas['jam_pulang']);

                if ($current_time >= $jam_masuk && $current_time <= $jam_pulang) {
                    // Jika mata kuliah ini sudah dipresensi, tambahkan ke counter
                    if (in_array($kelas['id_matkul'], $matkul_sudah_presensi)) {
                        $cek_presensi++;
                    }
                }
            }
        }

        $data = [
            'title' => 'Home',
            'lokasi_presensi_list' => $lokasi_presensi->findAll(),
            'mahasiswa' => $mahasiswa,
            'kelas_list' => $kelas_list,
            'hari_ini' => $hari_ini,
            'ambil_presensi_masuk' => $ambil_presensi_masuk,
            'ambil_presensi_keluar' => $ambil_presensi_keluar,
            'cek_presensi' => $cek_presensi,
            'matkul_sudah_presensi' => $matkul_sudah_presensi,
            'presensi_hari_ini' => $presensi_hari_ini
        ];

        return view('mahasiswa/home', $data);
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
        $id_matkul = $this->request->getPost('id_matkul');
        $jenis_kelas = $this->request->getPost('jenis_kelas');

        if ($jenis_kelas == 'Luring') {
            $id_lokasi_presensi = $this->request->getPost('id_lokasi_presensi');
        } else {
            $id_lokasi_presensi = '0000';
        }

        if (empty($id_lokasi_presensi)) {
            session()->setFlashdata('gagal', 'Silakan pilih lokasi presensi.');
            return redirect()->to(base_url('mahasiswa/home'));
        }

        if (!$id_matkul) {
            session()->setFlashdata('gagal', 'ID Mata Kuliah tidak ditemukan.');
            return redirect()->to(base_url('mahasiswa/home'));
        }

        if ($jenis_kelas == 'Luring') {
            // Ambil data lokasi presensi yang dipilih
            $lokasi_presensi_model = new LokasiPresensiModel();
            $lokasi_presensi = $lokasi_presensi_model->find($id_lokasi_presensi);
            if (!$lokasi_presensi) {
                session()->setFlashdata('gagal', 'Lokasi presensi tidak ditemukan.');
                return redirect()->to(base_url('mahasiswa/home'));
            }

            $latitude_mahasiswa = (float) $this->request->getPost('latitude_mahasiswa');
            $longitude_mahasiswa = (float) $this->request->getPost('longitude_mahasiswa');
            $latitude_kampus = (float) $lokasi_presensi['latitude'];
            $longitude_kampus = (float) $lokasi_presensi['longitude'];
            $radius = (int) $lokasi_presensi['radius'];

            // Cek apakah variabel berisi nilai
            if (!$latitude_mahasiswa || !$longitude_mahasiswa || !$latitude_kampus || !$longitude_kampus || !$radius) {
                session()->setFlashdata('gagal', 'Data lokasi tidak lengkap.');
                return redirect()->to(base_url('mahasiswa/home'));
            }

            // Hitung jarak
            $jarak_meter = $this->hitungJarak($latitude_mahasiswa, $longitude_mahasiswa, $latitude_kampus, $longitude_kampus);

            if ($jarak_meter > $radius) {
                session()->setFlashdata('gagal', 'Presensi Gagal, Lokasi Anda Berada di luar Radius Kampus.');
                return redirect()->to(base_url('mahasiswa/home'));
            }
        }

        $data = [
            'title' => "Ambil Foto Selfie",
            'id_mahasiswa' => $this->request->getPost('id_mahasiswa'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'id_lokasi_presensi' => $id_lokasi_presensi,
            'id_matkul' => $id_matkul
        ];
        return view('mahasiswa/ambil_foto', $data);
    }

    public function presensi_masuk_aksi()
    {
        $request = service('request');

        $id_mahasiswa = $request->getPost('id_mahasiswa');
        $tanggal_masuk = date('Y-m-d');

        $jam_masuk = $request->getPost('jam_masuk');
        $id_lokasi_presensi = $request->getPost('id_lokasi_presensi');
        $id_matkul = $request->getPost('id_matkul');

        $presensiModel = new PresensiModel();

        // Debug log
        log_message('debug', 'Data presensi: ' . json_encode([
            'id_mahasiswa' => $id_mahasiswa,
            'tanggal' => $tanggal_masuk,
            'jam_masuk' => $jam_masuk,
            'id_lokasi_presensi' => $id_lokasi_presensi,
            'id_matkul' => $id_matkul
        ]));

        // Validasi id_matkul
        if (empty($id_matkul)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID Mata Kuliah tidak ditemukan!'
            ]);
        }

        // Update lokasi presensi mahasiswa
        $mahasiswaModel = new MahasiswaModel();
        // $mahasiswaModel->update($id_mahasiswa, [
        //     'lokasi_presensi' => $id_lokasi_presensi
        // ]);

        // Simpan Foto di Folder
        if ($file = $request->getFile('foto_masuk')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $namaFile = 'absensi_' . $id_mahasiswa . '_' . time() . '.jpg';
                $file->move(ROOTPATH . 'public/uploads/absensi', $namaFile);
                $fotoPath = 'uploads/absensi/' . $namaFile;
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Foto tidak valid!']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Foto tidak ditemukan!']);
        }

        try {
            // Simpan Data ke Database
            $presensiModel = new PresensiModel();
            $data = [
                'id_mahasiswa'    => $id_mahasiswa,
                'tanggal' => $tanggal_masuk,
                'jam_masuk'     => $jam_masuk,
                'jam_keluar'     => '00:00:00',
                'foto_masuk'    => $namaFile,
                'id_lokasi_presensi' => $id_lokasi_presensi,
                'id_matkul' => $id_matkul,
            ];

            // Debug log
            log_message('debug', 'Menyimpan data presensi: ' . json_encode($data));

            $presensiModel->insert($data);

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Error saat menyimpan presensi: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan presensi: ' . $e->getMessage()
            ]);
        }
    }

    public function presensi_keluar($id)
    {
        $id_matkul = $this->request->getPost('id_matkul');
        $jenis_kelas = $this->request->getPost('jenis_kelas');

        if ($jenis_kelas == 'Luring') {
            $id_lokasi_presensi = $this->request->getPost('id_lokasi_presensi');
        } else {
            $id_lokasi_presensi = '0000';
        }

        if (empty($id_lokasi_presensi)) {
            session()->setFlashdata('gagal', 'Silakan pilih lokasi presensi.');
            return redirect()->to(base_url('mahasiswa/home'));
        }

        if (!$id_matkul) {
            session()->setFlashdata('gagal', 'ID Mata Kuliah tidak ditemukan.');
            return redirect()->to(base_url('mahasiswa/home'));
        }

        if ($jenis_kelas == 'Luring') {
            // Ambil data lokasi presensi yang dipilih
            $lokasi_presensi_model = new LokasiPresensiModel();
            $lokasi_presensi = $lokasi_presensi_model->find($id_lokasi_presensi);
            if (!$lokasi_presensi) {
                session()->setFlashdata('gagal', 'Lokasi presensi tidak ditemukan.');
                return redirect()->to(base_url('mahasiswa/home'));
            }

            $latitude_mahasiswa = (float) $this->request->getPost('latitude_mahasiswa');
            $longitude_mahasiswa = (float) $this->request->getPost('longitude_mahasiswa');
            $latitude_kampus = (float) $lokasi_presensi['latitude'];
            $longitude_kampus = (float) $lokasi_presensi['longitude'];
            $radius = (int) $lokasi_presensi['radius'];

            // Cek apakah variabel berisi nilai
            if (!$latitude_mahasiswa || !$longitude_mahasiswa || !$latitude_kampus || !$longitude_kampus || !$radius) {
                session()->setFlashdata('gagal', 'Data lokasi tidak lengkap.');
                return redirect()->to(base_url('mahasiswa/home'));
            }

            // Hitung jarak
            $jarak_meter = $this->hitungJarak($latitude_mahasiswa, $longitude_mahasiswa, $latitude_kampus, $longitude_kampus);

            if ($jarak_meter > $radius) {
                session()->setFlashdata('gagal', 'Presensi gagal, lokasi Anda berada di luar radius kantor.');
                return redirect()->to(base_url('mahasiswa/home'));
            }
        }

        $data = [
            'title' => "Ambil Foto Selfie",
            'id_presensi' => $id,
            'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
            'jam_keluar' => $this->request->getPost('jam_keluar'),
            'id_lokasi_presensi' => $id_lokasi_presensi,
            'id_matkul' => $id_matkul
        ];
        return view('mahasiswa/ambil_foto_keluar', $data);
    }

    public function presensi_keluar_aksi($id)
    {
        $request = service('request');

        $tanggal_keluar = $request->getPost('tanggal_keluar');
        $jam_keluar = $request->getPost('jam_keluar');
        $id_lokasi_presensi = $request->getPost('id_lokasi_presensi');
        $id_matkul = $request->getPost('id_matkul');

        // Debug log
        log_message('debug', 'Data presensi keluar: ' . json_encode([
            'id_presensi' => $id,
            'tanggal_keluar' => $tanggal_keluar,
            'jam_keluar' => $jam_keluar,
            'id_lokasi_presensi' => $id_lokasi_presensi,
            'id_matkul' => $id_matkul
        ]));

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

        try {
            // Simpan Data ke Database
            $presensiModel = new PresensiModel();
            $data = [
                'tanggal_keluar' => $tanggal_keluar,
                'jam_keluar'     => $jam_keluar,
                'foto_keluar'    => $namaFile,
                'id_lokasi_presensi_keluar' => $id_lokasi_presensi,
                'id_matkul' => $id_matkul
            ];

            // Debug log
            log_message('debug', 'Menyimpan data presensi keluar: ' . json_encode($data));

            $presensiModel->update($id, $data);

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Error saat menyimpan presensi keluar: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan presensi: ' . $e->getMessage()
            ]);
        }
    }
}
