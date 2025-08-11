<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\KetidakhadiranModel;
use App\Models\MahasiswaModel;
use App\Models\MatkulMahasiswa;
use App\Models\PresensiModel;
use App\Models\MatkulModel;
use App\Models\ProdiModel;
use CodeIgniter\HTTP\ResponseInterface;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class RekapPresensi extends BaseController
{
    private $presensiModel;
    private $dosenModel;
    private $matkulModel;
    private $prodiModel;
    private $mahasiswaModel;
    private $ketidakhadiranModel;

    function __construct()
    {
        $this->presensiModel = new PresensiModel();
        $this->dosenModel = new DosenModel();
        $this->matkulModel = new MatkulModel();
        $this->prodiModel = new ProdiModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->ketidakhadiranModel = new KetidakhadiranModel();
    }

    public function index()
    {
        $matkul_mhs = new MatkulMahasiswa();
        $matkulModel = new MatkulModel();
        $id_mhs = session()->get('id_mahasiswa');
        $matkul = $matkul_mhs->where('mhs_id', $id_mhs)->findAll();

        $dataMatkul = $matkulModel->whereIn('id', array_column($matkul, 'id'))->findAll();

        $presensiModel = new PresensiModel();
        $filter_matkul = $this->request->getVar('matkul');

        if (!empty($filter_matkul)) {

            $matkulModel = new MatkulModel();
            $namaMatkul = $matkulModel->find($filter_matkul)['matkul'];

            if (isset($_GET['excel'])) {
                $rekap_presensi = $presensiModel->rekap_presensi_mahasiswa_filter($filter_matkul);
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $activeWorksheet = $spreadsheet->getActiveSheet();

                // Merge title cells
                $activeWorksheet->mergeCells('A1:I1');
                $activeWorksheet->mergeCells('A3:B3');
                $activeWorksheet->mergeCells('C3:E3');

                // Set title and header
                $activeWorksheet->setCellValue('A1', 'REKAP PRESENSI HARIAN');
                $activeWorksheet->setCellValue('A3', 'TANGGAL');
                $activeWorksheet->setCellValue('C3', $filter_matkul);
                $activeWorksheet->setCellValue('A4', 'NO');
                $activeWorksheet->setCellValue('B4', 'Nama Mahasiswa');
                $activeWorksheet->setCellValue('C4', 'Mata Kuliah');
                $activeWorksheet->setCellValue('D4', 'Tanggal Masuk');
                $activeWorksheet->setCellValue('E4', 'Jam Masuk');
                $activeWorksheet->setCellValue('G4', 'Jam Keluar');
                $activeWorksheet->setCellValue('H4', 'Total Jam Kerja');
                $activeWorksheet->setCellValue('I4', 'Total Terlambat');
                $activeWorksheet->setCellValue('J4', 'Total Cepat Pulang');

                // Bold title
                $activeWorksheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $activeWorksheet->getStyle('A4:J4')->getFont()->setBold(true);

                $rows = 5;
                $no = 1;

                foreach ($rekap_presensi as $rekap) {
                    $timestamp_jam_masuk = strtotime($rekap['jam_masuk']);
                    $timestamp_jam_keluar = strtotime($rekap['jam_keluar']);
                    $selisih = $timestamp_jam_keluar - $timestamp_jam_masuk;
                    $jam = floor($selisih / 3600);
                    $menit = floor(($selisih % 3600) / 60);

                    // Hitung keterlambatan hanya jika ada presensi keluar
                    $jam_terlambat = 0;
                    $menit_terlambat = 0;
                    $selisih_terlambat = 0;

                    if ($rekap['jam_keluar'] != '00:00:00') {
                        $jam_masuk_real = strtotime($rekap['jam_masuk']);
                        $jam_masuk_kampus = isset($rekap['jam_masuk_kampus']) ? strtotime($rekap['jam_masuk_kampus']) : $jam_masuk_real;

                        if ($jam_masuk_real > $jam_masuk_kampus) {
                            $selisih_terlambat = $jam_masuk_real - $jam_masuk_kampus;
                            $jam_terlambat = floor($selisih_terlambat / 3600);
                            $menit_terlambat = floor(($selisih_terlambat % 3600) / 60);
                        }
                    }

                    // Hitung pulang cepat hanya jika ada presensi keluar
                    $jam_cepat_pulang = 0;
                    $menit_cepat_pulang = 0;
                    $selisih_cepat_pulang = 0;

                    if ($rekap['jam_keluar'] != '00:00:00') {
                        $jam_keluar_real = strtotime($rekap['jam_keluar']);
                        $jam_pulang_kampus = strtotime($rekap['jam_pulang_kampus']);

                        if ($jam_keluar_real < $jam_pulang_kampus) {
                            $selisih_cepat_pulang = $jam_pulang_kampus - $jam_keluar_real;
                            $jam_cepat_pulang = floor($selisih_cepat_pulang / 3600);
                            $menit_cepat_pulang = floor(($selisih_cepat_pulang % 3600) / 60);
                        }
                    }

                    $activeWorksheet->setCellValue('A' . $rows, $no++);
                    $activeWorksheet->setCellValue('B' . $rows, $rekap['nama']);
                    $activeWorksheet->setCellValue('C' . $rows, $rekap['nama_matkul']);
                    $activeWorksheet->setCellValue('D' . $rows, $rekap['tanggal']);
                    $activeWorksheet->setCellValue('E' . $rows, $rekap['jam_masuk']);
                    $activeWorksheet->setCellValue('G' . $rows, $rekap['jam_keluar']);
                    $activeWorksheet->setCellValue('H' . $rows, $jam . ' jam ' . $menit . ' menit');
                    $activeWorksheet->setCellValue('I' . $rows, $selisih_terlambat > 0 ? $jam_terlambat . ' jam ' . $menit_terlambat . ' menit' : '-');
                    $activeWorksheet->setCellValue('J' . $rows, $selisih_cepat_pulang > 0 ? $jam_cepat_pulang . ' jam ' . $menit_cepat_pulang . ' menit' : '-');

                    $rows++;
                }

                $lastRow = $rows - 1;

                // Auto-size columns
                foreach (range('A', 'J') as $col) {
                    $activeWorksheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Apply border to data
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $activeWorksheet->getStyle('A4:J' . $lastRow)->applyFromArray($styleArray);

                // Bold header
                $spreadsheet->getActiveSheet()->getStyle('A4:J4')->getFont()->setBold(true);

                // Export
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="rekap_presensi_mahasiswa.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;
            } else {
                $rekap_presensi = $presensiModel->rekap_presensi_mahasiswa_filter($filter_matkul);
            }
        } else {
            $rekap_presensi = $presensiModel->rekap_presensi_mahasiswa();
        }

        $data = [
            'title' => 'Rekap Presensi',
            'matkul' => $filter_matkul,
            'nama_matkul' => $namaMatkul ?? '',
            'rekap_presensi' => $rekap_presensi,
            'data_matkul' => $dataMatkul,
        ];

        return view('mahasiswa/rekap_presensi', $data);
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

    public function exportPdf()
    {

        $id = $this->request->getGet('matkul');
        $idMahasiswa = session()->get('id_mahasiswa');
        $dataMahasiswa = $this->mahasiswaModel->where('id', $idMahasiswa)->first();
        $semester = $dataMahasiswa['semester'];
        $idDosen = $this->matkulModel->select('dosen_pengampu')->where('id', $id)->first()['dosen_pengampu'];
        $namaDosen = $this->dosenModel->select('nama_dosen')->find($idDosen)['nama_dosen'];
        $pertemuan = $this->request->getGet('pertemuan');
        $pertemuanText = $this->request->getGet('text');
        $namaMatkul = $this->matkulModel->select('matkul.matkul')->find($id)['matkul'];
        $namaProdi = $this->prodiModel->select('nama')->where('matkul.id', $id)->join('matkul', 'matkul.prodi_id = prodi.id')->first()['nama'];
        $totalPertemuan = 14;

        if (!isset($pertemuanText)) {
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

            $presensi = null;

            for ($i = count($tanggal) + 1; $i <= $totalPertemuan; $i++) {
                $tanggal[] = array(
                    'index' => 'P' . $i,
                    'pertemuan' => "Pertemuan ke-" . $i,
                    'tanggal' => null
                );
            }

            $kehadiran = [];
            foreach ($tanggal as $t) {
                if (!empty($t['tanggal'])) {
                    $result = $this->presensiModel->select('jam_masuk, jam_keluar')
                        ->where('id_mahasiswa', $dataMahasiswa['id'])
                        ->where('id_matkul', $id)
                        ->where('tanggal', $t['tanggal'])
                        ->first();

                    $izin = $this->ketidakhadiranModel->where('id_mahasiswa', $dataMahasiswa['id'])
                        ->where('id_matkul', $id)
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

            $presensi = array(
                'id_mahasiswa' => $dataMahasiswa['id'],
                'npm_mahasiswa' => $dataMahasiswa['npm'],
                'nama_mahasiswa' => $dataMahasiswa['nama'],
                'semester' => $semester,
                'kehadiran' => $kehadiran
            );

            $data = array(
                'program_studi' => $namaProdi,
                'nama_dosen' => $namaDosen,
                'mata_kuliah' => $namaMatkul,
                'presensi' => $presensi,
                'pertemuan_is_empty' => true,
            );

            $html = view('mahasiswa/rekap_pdf', $data);

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
            $kehadiran = null;

            $dataTanggal = $this->presensiModel->select('tanggal')->where('id_matkul', $id)->groupBy('tanggal')->orderBy('tanggal', 'ASC')->findAll();

            $presensi = [];

            $kehadiran = [];
            $result = $this->presensiModel->select('jam_masuk, jam_keluar')
                ->where('id_mahasiswa', $dataMahasiswa['id'])
                ->where('id_matkul', $id)
                ->where('tanggal', $pertemuan)
                ->first();

            $izin = $this->ketidakhadiranModel->where('id_mahasiswa', $dataMahasiswa['id'])
                ->where('id_matkul', $id)
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
                'id_mahasiswa' => $dataMahasiswa['id'],
                'npm_mahasiswa' => $dataMahasiswa['npm'],
                'nama_mahasiswa' => $dataMahasiswa['nama'],
                'jam_masuk' => $kehadiran[0]['jam_masuk'],
                'jam_keluar' => $kehadiran[0]['jam_keluar'] != '00:00:00' ? $kehadiran[0]['jam_keluar'] : '-',
                'izin' => $kehadiran[0]['izin'],
                'keterangan' => $kehadiran[0]['keterangan'],
            );

            $data = array(
                'program_studi' => $namaProdi,
                'nama_dosen' => $namaDosen,
                'mata_kuliah' => $namaMatkul,
                'pertemuan_is_empty' => false,
                'pertemuan_text' => $pertemuanText,
                'tanggal' => Carbon::createFromFormat('Y-m-d', $pertemuan, 'Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y'),
                'presensi' => $presensi,
            );

            $html = view('mahasiswa/rekap_pdf', $data);

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
    }

    public function tableData()
    {
        sleep(1);
        $id = $this->request->getGet('matkul');
        $idMahasiswa = session()->get('id_mahasiswa');
        $dataMahasiswa = $this->mahasiswaModel->where('id', $idMahasiswa)->first();
        $semester = $dataMahasiswa['semester'];
        $idDosen = $this->matkulModel->select('dosen_pengampu')->where('id', $id)->first()['dosen_pengampu'];
        $namaDosen = $this->dosenModel->select('nama_dosen')->find($idDosen)['nama_dosen'];
        $pertemuan = $this->request->getGet('pertemuan');
        $pertemuanText = $this->request->getGet('text');
        $namaMatkul = $this->matkulModel->select('matkul.matkul')->find($id)['matkul'];
        $namaProdi = $this->prodiModel->select('nama')->where('matkul.id', $id)->join('matkul', 'matkul.prodi_id = prodi.id')->first()['nama'];
        $totalPertemuan = 14;

        if (!isset($pertemuanText)) {
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

            for ($i = count($tanggal) + 1; $i <= $totalPertemuan; $i++) {
                $tanggal[] = array(
                    'index' => 'P' . $i,
                    'pertemuan' => "Pertemuan ke-" . $i,
                    'tanggal' => null
                );
            }

            $kehadiran = [];
            foreach ($tanggal as $t) {
                if (!empty($t['tanggal'])) {
                    $result = $this->presensiModel->select('jam_masuk, jam_keluar')
                        ->where('id_mahasiswa', $dataMahasiswa['id'])
                        ->where('id_matkul', $id)
                        ->where('tanggal', $t['tanggal'])
                        ->first();

                    $izin = $this->ketidakhadiranModel->where('id_mahasiswa', $dataMahasiswa['id'])
                        ->where('id_matkul', $id)
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
                        'keterangan' => isset($izin) ? $izin['keterangan'] : ''
                    );
                } else {
                    $kehadiran[] = array(
                        'index' => $t['index'],
                        'tanggal' => '-',
                        'pertemuan' => $t['pertemuan'],
                        'jam_masuk' => '-',
                        'jam_keluar' => '-',
                        'izin' => false,
                        'keterangan' => ''
                    );
                }
            }

            $data = array(
                'mata_kuliah' => $namaMatkul,
                'set' => $namaMatkul,
                'presensi' => $kehadiran,
            );

            return response()->setJSON($data);
        } else {
            $kehadiran = null;

            $dataTanggal = $this->presensiModel->select('tanggal')->where('id_matkul', $id)->groupBy('tanggal')->orderBy('tanggal', 'ASC')->findAll();

            $kehadiran = [];
            $result = $this->presensiModel->select('jam_masuk, jam_keluar')
                ->where('id_mahasiswa', $dataMahasiswa['id'])
                ->where('id_matkul', $id)
                ->where('tanggal', $pertemuan)
                ->first();

            $izin = $this->ketidakhadiranModel->where('id_mahasiswa', $dataMahasiswa['id'])
                ->where('id_matkul', $id)
                ->where('status_pengajuan', 'Accept')
                ->where('tanggal', $pertemuan)
                ->first();

            $kehadiran[] = array(
                'tanggal' => Carbon::createFromFormat('Y-m-d', $pertemuan, 'Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y'),
                'pertemuan' => $pertemuanText,
                'jam_masuk' => $result['jam_masuk'] ?? '-',
                'jam_keluar' => $result['jam_keluar'] ?? '-',
                'izin' => isset($izin) ? true : false,
                'keterangan' => isset($izin) ? $izin['keterangan'] : ''
            );

            $data = array(
                'program_studi' => $namaProdi,
                'nama_dosen' => $namaDosen,
                'mata_kuliah' => $namaMatkul,
                'pertemuan_is_empty' => false,
                'pertemuan_text' => $pertemuanText,
                'tanggal' => Carbon::createFromFormat('Y-m-d', $pertemuan, 'Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y'),
                'presensi' => $kehadiran,
            );

            return response()->setJSON($data);
        }
    }
}
