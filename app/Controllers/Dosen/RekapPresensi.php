<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\KelasModel;
use App\Models\KetidakhadiranModel;
use App\Models\MatkulMahasiswa;
use App\Models\MatkulModel;
use App\Models\PresensiModel;
use App\Models\ProdiModel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class RekapPresensi extends BaseController
{
    private $matkulModel;
    private $presensiModel;
    private $matkulMahasiswaModel;
    private $kelasModel;
    private $ketidakhadiranModel;
    private $dosenModel;
    private $prodiModel;

    function __construct()
    {
        $this->matkulModel = new MatkulModel();
        $this->presensiModel = new PresensiModel();
        $this->matkulMahasiswaModel = new MatkulMahasiswa();
        $this->kelasModel = new KelasModel();
        $this->ketidakhadiranModel = new KetidakhadiranModel();
        $this->dosenModel = new DosenModel();
        $this->prodiModel = new ProdiModel();
    }

    function index()
    {
        $id = session()->get('id_dosen');

        $mata_kuliah = $this->matkulModel->select('id, matkul.matkul as nama_matkul')->where('dosen_pengampu', $id)->findAll();

        $namaHari = Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('l');

        $tanggal = Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('Y-m-d');

        $kelasHariIni = $this->kelasModel->select('kelas.id_matkul, matkul.matkul as nama_matkul')
            ->where('hari', $namaHari)
            ->where('matkul.dosen_pengampu', $id)
            ->join('matkul', 'matkul.id = kelas.id_matkul')
            ->findAll();

        $presensi = null;

        foreach ($kelasHariIni as $khi) {
            $mahasiswa = $this->matkulMahasiswaModel->select('matkul_mahasiswa.matkul_id as id_matkul, matkul_mahasiswa.mhs_id as id_mahasiswa, mahasiswa.npm, mahasiswa.nama')
                ->where('matkul_mahasiswa.matkul_id', $khi['id_matkul'])
                ->join('mahasiswa', 'mahasiswa.id = matkul_mahasiswa.mhs_id')
                ->findAll();

            $kehadiran = null;

            foreach ($mahasiswa as $mhs) {
                $result = $this->presensiModel->select('jam_masuk, jam_keluar')
                    ->where('id_mahasiswa', $mhs['id_mahasiswa'])
                    ->where('tanggal', $tanggal)
                    ->where('id_matkul', $mhs['id_matkul'])
                    ->first();

                $izin = $this->ketidakhadiranModel->where('id_mahasiswa', $mhs['id_mahasiswa'])
                    ->where('tanggal', $tanggal)
                    ->where('id_matkul', $mhs['id_matkul'])
                    ->where('status_pengajuan', 'Accept')
                    ->first();

                $kehadiran[] = array(
                    'id_mahasiswa' => $mhs['id_mahasiswa'],
                    'npm_mahasiswa' => $mhs['npm'],
                    'nama_mahasiswa' => $mhs['nama'],
                    'jam_masuk' => $result['jam_masuk'] ?? '-',
                    'jam_keluar' => $result['jam_keluar'] ?? '-',
                    'izin' => isset($izin) ? true : false,
                    'keterangan' => isset($izin) ? $izin['keterangan'] : null
                );
            }

            $presensi[] = array(
                'id_matkul' => $khi['id_matkul'],
                'nama_matkul' => $khi['nama_matkul'],
                'tanggal' => Carbon::createFromFormat('Y-m-d', $tanggal)->locale('id')->translatedFormat('l, j F Y'),
                'kehadiran' => $kehadiran
            );
        }

        $data = [
            'title' => 'Rekap Presensi',
            'mata_kuliah' => $mata_kuliah,
            'presensi_hari_ini' => $presensi
        ];

        return view('dosen/rekap_presensi', $data);
    }

    public function cekPertemuan()
    {
        $id_matkul = $this->request->getPost('id_matkul');
        $data = $this->presensiModel->select('tanggal')->where('id_matkul', $id_matkul)->groupBy('tanggal')->orderBy('tanggal', 'ASC')->findAll();

        if (empty($data)) {
            return response()->setJSON(array(
                'message' => 'Belum ada data perkuliahan.'
            ))->setStatusCode(404);
        }

        $return = null;

        foreach ($data as $key => $value) {
            $return[] = array(
                'key' => "Pertemuan ke-" . $key + 1,
                'value' => $value['tanggal']
            );
        }

        sleep(1);

        return response()->setJSON(array(
            'message' => 'Berhasil',
            'data' => $return
        ))->setStatusCode(200);
    }

    public function unduh()
    {
        $id = $this->request->getGet('matkul');
        $idDosen = session()->get('id_dosen');
        $namaDosen = $this->dosenModel->select('nama_dosen')->find($idDosen)['nama_dosen'];
        $pertemuan = $this->request->getGet('pertemuan');
        $pertemuanText = $this->request->getGet('text');
        $namaMatkul = $this->matkulModel->select('matkul.matkul')->find($id)['matkul'];
        $namaProdi = $this->prodiModel->select('nama')->where('matkul.id', $id)->join('matkul', 'matkul.prodi_id = prodi.id')->first()['nama'];
        $totalPertemuan = 14;

        if (!isset($pertemuanText)) {
            $mahasiswa = $this->matkulMahasiswaModel->select('matkul_mahasiswa.matkul_id as id_matkul, matkul_mahasiswa.mhs_id as id_mahasiswa, mahasiswa.npm, mahasiswa.nama')
                ->where('matkul_mahasiswa.matkul_id', $id)
                ->join('mahasiswa', 'mahasiswa.id = matkul_mahasiswa.mhs_id')
                ->findAll();

            $kehadiran = null;

            $dataTanggal = $this->presensiModel->select('tanggal')->where('id_matkul', $id)->groupBy('tanggal')->orderBy('tanggal', 'ASC')->findAll();

            $tanggal = [];

            foreach ($dataTanggal as $key => $value) {
                $tanggal[] = array(
                    'index' => 'P' . $key + 1,
                    'pertemuan' => "Pertemuan ke-" . $key + 1,
                    'tanggal' => $value['tanggal']
                );
            }

            $presensi = [];

            for ($i = count($tanggal) + 1; $i <= $totalPertemuan; $i++) {
                $tanggal[] = array(
                    'index' => 'P' . $i,
                    'pertemuan' => "Pertemuan ke-" . $i,
                    'tanggal' => null
                );
            }

            foreach ($mahasiswa as $mhs) {
                $kehadiran = [];
                foreach ($tanggal as $t) {
                    if (!empty($t['tanggal'])) {
                        $result = $this->presensiModel->select('jam_masuk, jam_keluar')
                            ->where('id_mahasiswa', $mhs['id_mahasiswa'])
                            ->where('id_matkul', $mhs['id_matkul'])
                            ->where('tanggal', $t['tanggal'])
                            ->first();

                        $izin = $this->ketidakhadiranModel->where('id_mahasiswa', $mhs['id_mahasiswa'])
                            ->where('id_matkul', $mhs['id_matkul'])
                            ->where('status_pengajuan', 'Accept')
                            ->where('tanggal', $t['tanggal'])
                            ->first();

                        $kehadiran[] = array(
                            'index' => $t['index'],
                            'tanggal' => Carbon::createFromFormat('Y-m-d', $t['tanggal'], 'Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y'),
                            'pertemuan' => $t['pertemuan'],
                            'jam_masuk' => $result['jam_masuk'] ?? '-',
                            'jam_keluar' => $result['jam_keluar'] ?? '-',
                            'izin' => isset($izin) ? true : false,
                            'keterangan' => isset($izin) ? $izin['keterangan'] : null
                        );
                    } else {
                        $kehadiran[] = array(
                            'index' => $t['index'],
                            'tanggal' => '-',
                            'pertemuan' => $t['pertemuan'],
                            'jam_masuk' => '-',
                            'jam_keluar' => '-',
                            'izin' => false,
                            'keterangan' => null
                        );
                    }
                }

                $presensi[] = array(
                    'id_mahasiswa' => $mhs['id_mahasiswa'],
                    'npm_mahasiswa' => $mhs['npm'],
                    'nama_mahasiswa' => $mhs['nama'],
                    'kehadiran' => $kehadiran
                );
            }

            $data = array(
                'program_studi' => $namaProdi,
                'nama_dosen' => $namaDosen,
                'mata_kuliah' => $namaMatkul,
                'presensi' => $presensi,
                'pertemuan_is_empty' => true,
            );

            // return response()->setJS ON($data);

            $html = view('dosen/rekap_pdf', $data);

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);

            // Load HTML ke Dompdf
            $dompdf->loadHtml($html);

            // (Optional) Setup ukuran kertas dan orientasi
            $dompdf->setPaper('A4', 'landscape');

            // Render HTML ke PDF
            $dompdf->render();

            // Output PDF
            return $dompdf->stream('document.pdf', ['Attachment' => false]);
        } else {
            $mahasiswa = $this->matkulMahasiswaModel->select('matkul_mahasiswa.matkul_id as id_matkul, matkul_mahasiswa.mhs_id as id_mahasiswa, mahasiswa.npm, mahasiswa.nama')
                ->where('matkul_mahasiswa.matkul_id', $id)
                ->join('mahasiswa', 'mahasiswa.id = matkul_mahasiswa.mhs_id')
                ->findAll();

            $kehadiran = null;

            $dataTanggal = $this->presensiModel->select('tanggal')->where('id_matkul', $id)->groupBy('tanggal')->orderBy('tanggal', 'ASC')->findAll();


            $presensi = [];


            foreach ($mahasiswa as $mhs) {
                $kehadiran = [];
                $result = $this->presensiModel->select('jam_masuk, jam_keluar')
                    ->where('id_mahasiswa', $mhs['id_mahasiswa'])
                    ->where('id_matkul', $mhs['id_matkul'])
                    ->where('tanggal', $pertemuan)
                    ->first();

                $izin = $this->ketidakhadiranModel->where('id_mahasiswa', $mhs['id_mahasiswa'])
                    ->where('id_matkul', $mhs['id_matkul'])
                    ->where('status_pengajuan', 'Accept')
                    ->where('tanggal', $pertemuan)
                    ->first();

                $kehadiran[] = array(
                    'tanggal' => Carbon::createFromFormat('Y-m-d', $pertemuan, 'Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y'),
                    'pertemuan' => $pertemuanText,
                    'jam_masuk' => $result['jam_masuk'] ?? '-',
                    'jam_keluar' => $result['jam_keluar'] ?? '-',
                    'izin' => isset($izin) ? true : false,
                    'keterangan' => isset($izin) ? $izin['keterangan'] : null
                );

                $presensi[] = array(
                    'id_mahasiswa' => $mhs['id_mahasiswa'],
                    'npm_mahasiswa' => $mhs['npm'],
                    'nama_mahasiswa' => $mhs['nama'],
                    'jam_masuk' => $kehadiran[0]['jam_masuk'],
                    'jam_keluar' => $kehadiran[0]['jam_keluar'] != '00:00:00' ? $kehadiran[0]['jam_keluar'] : '-',
                    'izin' => $kehadiran[0]['izin'],
                    'keterangan' => $kehadiran[0]['keterangan'],
                );
            }

            $data = array(
                'program_studi' => $namaProdi,
                'nama_dosen' => $namaDosen,
                'mata_kuliah' => $namaMatkul,
                'pertemuan_is_empty' => false,
                'pertemuan_text' => $pertemuanText,
                'tanggal' => Carbon::createFromFormat('Y-m-d', $pertemuan, 'Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y'),
                'presensi' => $presensi,
            );

            // return response()->setJSON($data);

            $html = view('dosen/rekap_pdf', $data);

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);

            // Load HTML ke Dompdf
            $dompdf->loadHtml($html);

            // (Optional) Setup ukuran kertas dan orientasi
            $dompdf->setPaper('A4', 'landscape');

            // Render HTML ke PDF
            $dompdf->render();

            // Output PDF
            return $dompdf->stream('document.pdf', ['Attachment' => false]);
        }

        return response()->setJSON($presensi);
    }
}
