<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\MatkulMahasiswa;
use App\Models\PresensiModel;
use App\Models\MatkulModel;
use CodeIgniter\HTTP\ResponseInterface;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class RekapPresensi extends BaseController
{
    public function index()
    {
        $matkul_mhs = new MatkulMahasiswa();
        $matkulModel = new MatkulModel();
        $id_mhs = session()->get('id_mahasiswa');
        $matkul = $matkul_mhs->where('mhs_id', $id_mhs)->findAll();
        $ids = [];

        foreach($matkul as $mtkl) {
            $ids[] = $mtkl['matkul_id'];
        }

        $dataMatkul = $matkulModel->whereIn('id', $ids)->findAll();

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
}
